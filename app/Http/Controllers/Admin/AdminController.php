<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    /**
     * Display the admin index view.
     *
     * @return \Inertia\Response
     */
    public function index(): Response
    {
        return Inertia::render('admin/Index');
    }
}
