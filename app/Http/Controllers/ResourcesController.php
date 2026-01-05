<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class ResourcesController extends Controller
{
    public function licenses(): Response
    {
        return Inertia::render('Resources/Licenses');
    }

    public function roadmap(): Response
    {
        return Inertia::render('Resources/Roadmap');
    }

    public function launchChecklist(): Response
    {
        return Inertia::render('Resources/LaunchChecklist');
    }
}
