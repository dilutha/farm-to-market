<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Crop;
use Illuminate\Support\Facades\Auth;
class ListingController extends Controller
{
// Show listing page
public function index()
{
// Get all crops of the logged-in farmer
$farmerId = Auth::id(); // assuming farmer's user_id
$crops = Crop::where('farmer_id', $farmerId)->get();
return view('listing', compact('crops'));
}
}
?>