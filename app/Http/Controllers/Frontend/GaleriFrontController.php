<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GaleriFrontController extends Controller
{
    public function index(Request $request): View
    {
        $galeris = Galeri::latest()->paginate(12)->withQueryString();
        return view('front.galeri.index', compact('galeris'));
    }

    public function show(Galeri $galeri): View
    {
        return view('front.galeri.show', compact('galeri'));
    }
}
