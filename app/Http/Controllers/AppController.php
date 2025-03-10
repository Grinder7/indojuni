<?php

namespace App\Http\Controllers;

class AppController extends Controller
{
    public function home()
    {
        return view('pages.home');
    }
    public function aboutus()
    {
        return view('pages.aboutus');
    }
}
