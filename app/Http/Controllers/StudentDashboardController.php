<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Payment;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth('client')->check()) {
                return redirect()->route('student.login');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $client = auth('client')->user();
        
        $registrations = Registration::where('client_id', $client->id)
            ->where('status', '!=', 'canceled')
            ->with(['training.resources', 'bundle.trainings.resources', 'payments' => function ($q) {
                $q->orderByDesc('created_at');
            }])
            ->orderByDesc('created_at')
            ->get();

        $allTrainings = \App\Models\Training::where('is_active', true)->get();
        $allBundles = \App\Models\Bundle::all();

        $stats = [
            'en_cours' => 0,
            'a_venir' => 0,
            'paiements_attente' => 0,
            'paiements_retard' => 0,
        ];

        $now = now();
        foreach ($registrations as $reg) {
            $isStarted = false;
            
            if ($reg->training) {
                if ($reg->training->start_date && $reg->training->start_date > $now) {
                    $stats['a_venir']++;
                } else {
                    $stats['en_cours']++;
                    $isStarted = true;
                }
            } elseif ($reg->bundle) {
                $stats['en_cours']++;
                $isStarted = true;
            }

            if ($isStarted && $reg->balance_due > 0) {
                $stats['paiements_retard']++;
            }

            $stats['paiements_attente'] += $reg->payments->where('status', 'pending')->count();
        }

        return view('student.dashboard', compact('client', 'registrations', 'allTrainings', 'allBundles', 'stats'));
    }

    public function storePaymentDeclaration(Request $request)
    {
        $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            'amount' => 'required|numeric|min:100',
            'method' => 'required|string|max:255',
            'reference' => 'nullable|string|max:255',
        ]);

        $client = auth('client')->user();
        $registration = Registration::where('client_id', $client->id)
            ->findOrFail($request->input('registration_id'));

        $payment = Payment::create([
            'registration_id' => $registration->id,
            'amount' => $request->input('amount'),
            'method' => $request->input('method'),
            'status' => 'pending', // En attente de validation par l'administrateur
            'reference' => $request->input('reference'),
            'paid_at' => null,
        ]);

        try {
            $courseName = $registration->training ? $registration->training->title : ($registration->bundle ? $registration->bundle->name : 'une formation');
            \Illuminate\Support\Facades\Notification::send(
                \App\Models\User::all(),
                new \App\Notifications\AdminNotification(
                    'Nouveau paiement',
                    $client->name . ' a déclaré un versement de ' . number_format($payment->amount, 0, ',', ' ') . ' CFA pour ' . $courseName,
                    route('admin.payments'),
                    'bi-cash-coin'
                )
            );
        } catch (\Exception $e) {}

        return redirect()->route('student.dashboard')->with('success', 'Votre versement de ' . number_format($request->amount, 0, ',', ' ') . ' CFA a été déclaré avec succès. Il apparaîtra sur votre tableau de bord dès sa validation par l\'administrateur.');
    }

    public function destroyRegistration(Registration $registration)
    {
        $client = auth('client')->user();
        if ($registration->client_id !== $client->id) {
            abort(403);
        }

        // Check if there are any declared or validated payments
        $payments = $registration->payments()->whereIn('status', ['completed', 'pending'])->count();
        if ($payments > 0) {
            return redirect()->route('student.dashboard')->with('error', 'Impossible d\'annuler cette inscription car un paiement en cours ou validé y est associé.');
        }

        // Logical cancellation
        $notes = json_decode($registration->notes ?? '[]', true) ?: [];
        $notes['canceled_at'] = now()->toDateTimeString();
        $notes['canceled_by_student'] = true;

        $registration->update([
            'status' => 'canceled',
            'notes' => json_encode($notes, JSON_UNESCAPED_UNICODE)
        ]);

        return redirect()->route('student.dashboard')->with('success', 'Votre inscription a été annulée avec succès.');
    }
    public function markNotificationsAsRead()
    {
        auth('client')->user()->unreadNotifications->markAsRead();
        return back();
    }
}
