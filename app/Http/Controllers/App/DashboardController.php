<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display the dashboard view.
     *
     * @return \Inertia\Response
     */
    public function index(): Response
    {
        return Inertia::render('app\Dashboard');
    }
}
