<?php

namespace App\Http\Controllers\kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KasirMainController extends Controller
{
    //
    public function index()
    {
        return view('kasir.dashboard');
    }
}
