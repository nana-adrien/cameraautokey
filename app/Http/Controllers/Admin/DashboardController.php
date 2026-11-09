<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\Technician;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $statusCounts = Order::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

        // Commandes des 14 derniers jours pour le graphique.
        $since = now()->subDays(13)->startOfDay();
        $perDay = Order::where('created_at', '>=', $since)->get(['created_at'])
            ->groupBy(fn (Order $o) => $o->created_at->toDateString())
            ->map->count();

        $chart = collect(range(0, 13))->map(function (int $i) use ($since, $perDay) {
            $day = $since->copy()->addDays($i);

            return ['label' => $day->translatedFormat('d M'), 'value' => $perDay[$day->toDateString()] ?? 0];
        });

        return view('admin.dashboard', [
            'stats' => [
                'orders_total' => Order::count(),
                'orders_pending' => $statusCounts['pending'] ?? 0,
                'revenue' => Order::where('status', 'completed')->sum('estimated_price'),
                'revenue_month' => Order::where('status', 'completed')->where('completed_at', '>=', Carbon::now()->startOfMonth())->sum('estimated_price'),
                'technicians' => Technician::where('is_active', true)->count(),
                'unread_messages' => ContactMessage::whereNull('read_at')->count(),
            ],
            'statusCounts' => $statusCounts,
            'chart' => $chart,
            'latestOrders' => Order::with('technician')->latest()->take(8)->get(),
        ]);
    }
}
