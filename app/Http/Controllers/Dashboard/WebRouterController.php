<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WebRouterController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard/Index');
    }

    public function createIndex()
    {
        return Inertia::render('Dashboard/Evaluations/Create');
    }

}
