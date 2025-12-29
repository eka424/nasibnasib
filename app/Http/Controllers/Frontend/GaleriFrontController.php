<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\View\View;

class GaleriFrontController extends Controller
{
    public function index(): View
    {
        $galeris = Galeri::latest()->paginate(20);

        return view('front.galeri.index', compact('galeris'));
    }
}
