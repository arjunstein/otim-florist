<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function overview(): Response
    {
        return Inertia::render('Dashboard/Overview', [
            'stats' => [
                ['label' => 'Revenue today', 'value' => 'Rp 4.2M', 'delta' => '+12% vs yesterday', 'up' => true, 'icon' => '💰'],
                ['label' => 'Orders today', 'value' => '18', 'delta' => '+3 vs yesterday', 'up' => true, 'icon' => '🧾'],
                ['label' => 'Bouquets in stock', 'value' => '132', 'delta' => '6 sold today', 'up' => false, 'icon' => '💐'],
                ['label' => 'Pending orders', 'value' => '5', 'delta' => '2 need arranging', 'up' => false, 'icon' => '⏳'],
            ],
            'sales' => [
                ['label' => 'Mon', 'value' => 32],
                ['label' => 'Tue', 'value' => 45],
                ['label' => 'Wed', 'value' => 28],
                ['label' => 'Thu', 'value' => 52],
                ['label' => 'Fri', 'value' => 68],
                ['label' => 'Sat', 'value' => 91],
                ['label' => 'Sun', 'value' => 74],
            ],
            'orders' => [
                ['id' => 'OF-1041', 'customer' => 'Ayu Lestari', 'item' => 'Rose Bouquet M', 'total' => 'Rp 350K', 'status' => 'Delivered'],
                ['id' => 'OF-1042', 'customer' => 'Budi Santoso', 'item' => 'Lily Basket', 'total' => 'Rp 275K', 'status' => 'Arranging'],
                ['id' => 'OF-1043', 'customer' => 'Citra Dewi', 'item' => 'Sunflower Wrap', 'total' => 'Rp 180K', 'status' => 'Pending'],
                ['id' => 'OF-1044', 'customer' => 'Dedi Prasetyo', 'item' => 'Orchid Box', 'total' => 'Rp 520K', 'status' => 'Arranging'],
                ['id' => 'OF-1045', 'customer' => 'Eka Putri', 'item' => 'Tulip Bouquet S', 'total' => 'Rp 220K', 'status' => 'Pending'],
            ],
            'lowStock' => [
                ['name' => 'White roses', 'left' => 8],
                ['name' => 'Baby breath', 'left' => 5],
                ['name' => 'Kraft wrap', 'left' => 12],
            ],
        ]);
    }

    public function products(): Response
    {
        return Inertia::render('Dashboard/Products', [
            'categories' => ['Bouquet', 'Basket', 'Box', 'Wrap'],
            'products' => [
                ['id' => 1, 'name' => 'Rose Bouquet M', 'category' => 'Bouquet', 'price' => 'Rp 350K', 'stock' => 14],
                ['id' => 2, 'name' => 'Lily Basket', 'category' => 'Basket', 'price' => 'Rp 275K', 'stock' => 6],
                ['id' => 3, 'name' => 'Orchid Box', 'category' => 'Box', 'price' => 'Rp 520K', 'stock' => 9],
                ['id' => 4, 'name' => 'Sunflower Wrap', 'category' => 'Wrap', 'price' => 'Rp 180K', 'stock' => 22],
                ['id' => 5, 'name' => 'Tulip Bouquet S', 'category' => 'Bouquet', 'price' => 'Rp 220K', 'stock' => 0],
                ['id' => 6, 'name' => 'Peony Basket L', 'category' => 'Basket', 'price' => 'Rp 410K', 'stock' => 4],
            ],
        ]);
    }

    public function settings(): Response
    {
        return Inertia::render('Dashboard/Settings', [
            'store' => [
                'name' => 'Otim Florist',
                'phone' => '+62 812-3456-7890',
                'address' => 'Jl. Mawar No. 12, Jakarta',
                'hours' => '08:00–20:00 daily',
            ],
        ]);
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:255'],
            'hours' => ['required', 'string', 'max:100'],
        ]);

        return back()->with('success', 'Store settings saved (dummy, nothing persisted).');
    }
}
