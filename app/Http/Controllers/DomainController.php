<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DomainController extends Controller
{
    /**
     * Display a listing of the user's domains
     */
    public function index(): View
    {
        return view('collection', []);
    }

    /**
     * Display a listing of the user's domains
     */
    public function showcase(): View
    {
        return view('showcase', []);
    }
}
