<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $newsPosts = \App\Models\Post::where('type', 'news')->get();
        return view('news', compact('newsPosts'));
    }
}
