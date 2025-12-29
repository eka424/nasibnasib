<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\PendaftaranKegiatan;
use App\Models\PertanyaanUstadz;
use App\Models\TransaksiDonasi;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $now = now();
        $currentMonthRange = [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()];
        $previousMonthRange = [$now->copy()->subMonthNoOverflow()->startOfMonth(), $now->copy()->subMonthNoOverflow()->endOfMonth()];

        $currentDonation = TransaksiDonasi::where('status_pembayaran', 'berhasil')
            ->whereBetween('created_at', $currentMonthRange)
            ->sum('jumlah');
        $previousDonation = TransaksiDonasi::where('status_pembayaran', 'berhasil')
            ->whereBetween('created_at', $previousMonthRange)
            ->sum('jumlah');

        $currentRegistrations = PendaftaranKegiatan::whereBetween('created_at', $currentMonthRange)->count();
        $previousRegistrations = PendaftaranKegiatan::whereBetween('created_at', $previousMonthRange)->count();

        $upcomingEvents = Kegiatan::where('tanggal_mulai', '>=', $now)->count();
        $pendingQuestions = PertanyaanUstadz::where('status', 'menunggu')->count();

        $donationGrowth = $this->growthPercentage($currentDonation, $previousDonation);
        $registrationGrowth = $this->growthPercentage($currentRegistrations, $previousRegistrations);

        $latestDonations = TransaksiDonasi::with(['user', 'donasi'])
            ->latest()
            ->take(5)
            ->get();

        $latestRegistrations = PendaftaranKegiatan::with(['user', 'kegiatan'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'stats' => [
                'donations' => [
                    'value' => $currentDonation,
                    'growth' => $donationGrowth,
                ],
                'registrations' => [
                    'value' => $currentRegistrations,
                    'growth' => $registrationGrowth,
                ],
                'events' => $upcomingEvents,
                'questions' => $pendingQuestions,
            ],
            'latestDonations' => $latestDonations,
            'latestRegistrations' => $latestRegistrations,
        ]);
    }

    protected function growthPercentage(int|float $current, int|float $previous): float
    {
        if ($previous <= 0) {
            return $current > 0 ? 100.0 : 0.0;
        }

        return (($current - $previous) / $previous) * 100;
    }
}
