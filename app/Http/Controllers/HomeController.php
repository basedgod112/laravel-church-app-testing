<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    /**
     * Show the application home page.
     *
     * @return View
     */
    public function index(): View
    {
        return view('home');
    }
}

