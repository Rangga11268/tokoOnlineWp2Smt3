<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FotoProduk;
use App\Models\Kategori;
use App\Models\Produk;

class BerandaController extends Controller
{
    public function berandaBackend()
    {
        return view('backend.v_beranda.index', [
            'judul' => 'Halaman Beranda',
        ]);
    }

    public function index()
    {
        $produk = Produk::where('status', 1)->orderBy('created_at', 'desc')->paginate(6);
        return view('v_beranda.index', [
            'judul' => 'Halaman Beranda',
            'produk' => $produk
        ]);
    }
};
