<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnalysisController extends Controller
{
    public function index()
    {
        return view('analysis'); // create resources/views/analysis.blade.php
    }
}
