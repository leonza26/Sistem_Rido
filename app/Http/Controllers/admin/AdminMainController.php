<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminMainController extends Controller
{
    // dashboard view
    public function admin()
    {
        return view('admin.dashboard');
    }

    // manage products
    public function manageProducts()
    {
        return view('admin.manage_produk');
    }

    // manage laporan
    public function manageLaporan()
    {
        return view('admin.laporan');
    }

    // manage pengguna
    public function managePengguna()
    {
        return view('admin.manage_pengguna');
    }



}
