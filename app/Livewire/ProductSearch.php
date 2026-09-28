<?php

namespace App\Livewire;

use App\Models\Produk;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Contracts\View\View;

class ProductSearch extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $products = Produk::with('kategori')
            ->where('nama_produk', 'like', '%' . trim($this->search) . '%')
            ->paginate(10);

        return view('livewire.product-search', [
            'products' => $products,
            // kolom harga modal hanya ditampilkan untuk Pemilik
            'tampilkanHargaModal' => (bool) auth()->user()?->isPemilik(),
        ]);
    }
}
