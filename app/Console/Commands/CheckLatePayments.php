<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckLatePayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:check-late';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for registrations pending for more than 7 days without payment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dateThreshold = now()->subDays(7);

        // Find registrations that are still pending and created more than 7 days ago
        $lateRegistrations = \App\Models\Registration::where('status', 'pending')
            ->where('created_at', '<', $dateThreshold)
            ->get();

        $count = 0;
        foreach ($lateRegistrations as $reg) {
            // Check if they have any completed payments that cover the amount, or pending payments
            $totalPaid = $reg->payments()->where('status', 'completed')->sum('amount');
            $hasPendingPayments = $reg->payments()->where('status', 'pending')->exists();

            if ($totalPaid < $reg->amount && !$hasPendingPayments) {
                // Determine course name
                $courseName = $reg->training ? $reg->training->title : ($reg->bundle ? $reg->bundle->name : 'une formation');
                $clientName = $reg->client ? $reg->client->name : 'Un étudiant';

                \Illuminate\Support\Facades\Notification::send(
                    \App\Models\User::all(),
                    new \App\Notifications\AdminNotification(
                        'Paiement en retard',
                        "L'inscription de {$clientName} à {$courseName} est en attente depuis plus de 7 jours.",
                        route('admin.registrations'),
                        'bi-exclamation-triangle'
                    )
                );
                $count++;
            }
        }

        $this->info("Checked late payments. Sent {$count} notifications.");
    }
}
