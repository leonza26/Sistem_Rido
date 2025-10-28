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

    public function transaksi()
    {
        return view('kasir.transaksi');
    }

    public function stok_barang()
    {
        return view('kasir.stok_barang');
    }
}
