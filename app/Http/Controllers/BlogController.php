<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class BlogController extends Controller
{
    public function __invoke(): View
    {
        return view('blog');
    }
}
