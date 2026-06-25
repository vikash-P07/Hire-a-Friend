<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AutoCompleteBookings extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'bookings:auto-complete {--dry-run : Preview bookings that would be completed without saving}';

    /**
     * The console command description.
     */
    protected $description = 'Automatically mark approved/ongoing bookings as completed if their session time has passed.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');
        $now = now();

        // Find approved/ongoing bookings whose booking_date + duration has passed
        $expiredBookings = Booking::whereIn('status', ['approved', 'ongoing', 'paid'])
            ->whereNotNull('booking_date')
            ->whereNotNull('start_time')
            ->whereNotNull('duration_hours')
            ->with('partner', 'customer')
            ->get()
            ->filter(function ($booking) use ($now) {
                // Calculate the expected end datetime
                try {
                    $startDateTime = \Carbon\Carbon::parse(
                        $booking->booking_date->format('Y-m-d') . ' ' . $booking->start_time
                    );
                    $endDateTime = $startDateTime->addHours((int) $booking->duration_hours);
                    return $endDateTime->lessThan($now);
                } catch (\Exception $e) {
                    return false;
                }
            });

        if ($expiredBookings->isEmpty()) {
            $this->info('No expired bookings found to auto-complete.');
            return Command::SUCCESS;
        }

        $this->info("Found {$expiredBookings->count()} booking(s) eligible for auto-completion.");

        if ($isDryRun) {
            $this->table(
                ['Booking ID', 'Customer', 'Partner', 'Date', 'Status'],
                $expiredBookings->map(fn ($b) => [
                    $b->id,
                    $b->customer->name ?? 'N/A',
                    $b->partner->name ?? 'N/A',
                    $b->booking_date->format('Y-m-d'),
                    $b->status,
                ])->toArray()
            );
            $this->warn('Dry run mode — no changes made.');
            return Command::SUCCESS;
        }

        $completed = 0;

        foreach ($expiredBookings as $booking) {
            try {
                $booking->status = 'completed';
                $booking->save();

                // Update partner earnings status to 'available' (ready for withdrawal)
                $earning = \App\Models\PartnerEarning::where('booking_id', $booking->id)->first();
                if ($earning && $earning->status === 'pending') {
                    $earning->status = 'available';
                    $earning->save();
                }

                // Send DB notification to customer
                if ($booking->customer_id) {
                    DB::table('notifications')->insert([
                        'id'              => Str::uuid()->toString(),
                        'type'            => 'App\Notifications\BookingCompleted',
                        'notifiable_type' => 'App\Models\User',
                        'notifiable_id'   => $booking->customer_id,
                        'data'            => json_encode([
                            'message'    => 'Your session with ' . ($booking->partner->name ?? 'your companion') . ' has been automatically marked as completed. Please leave a review!',
                            'booking_id' => $booking->id,
                        ]),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Send DB notification to partner
                if ($booking->partner_id) {
                    DB::table('notifications')->insert([
                        'id'              => Str::uuid()->toString(),
                        'type'            => 'App\Notifications\BookingCompleted',
                        'notifiable_type' => 'App\Models\User',
                        'notifiable_id'   => $booking->partner_id,
                        'data'            => json_encode([
                            'message'    => 'Booking #' . $booking->id . ' has been automatically completed. Your earnings are now available for withdrawal.',
                            'booking_id' => $booking->id,
                        ]),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                $completed++;
                Log::info("Auto-completed booking #{$booking->id}");
            } catch (\Exception $e) {
                Log::error("Failed to auto-complete booking #{$booking->id}: " . $e->getMessage());
                $this->error("Error on booking #{$booking->id}: " . $e->getMessage());
            }
        }

        $this->info("✅ Auto-completed {$completed} booking(s) successfully.");

        return Command::SUCCESS;
    }
}
