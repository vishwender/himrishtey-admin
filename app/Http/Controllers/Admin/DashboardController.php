<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SiteDashboardService;

class DashboardController extends Controller
{
    public function index(SiteDashboardService $dashboardService)
    {
        $stats = $dashboardService->statistics();

        return view('admin.dashboard', compact('stats'));
    }
}
