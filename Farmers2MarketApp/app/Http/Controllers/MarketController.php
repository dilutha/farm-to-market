<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    public function index()
    {
        // Only show Approved crops
        $crops = Crop::where('status', 'Approved')->latest()->get();

        return view('market', compact('crops'));
    }
}
