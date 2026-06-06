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

        return view('student.dashboard', compact('client', 'registrations'));
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

        Payment::create([
            'registration_id' => $registration->id,
            'amount' => $request->input('amount'),
            'method' => $request->input('method'),
            'status' => 'pending', // En attente de validation par l'administrateur
            'reference' => $request->input('reference'),
            'paid_at' => null,
        ]);

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
}
