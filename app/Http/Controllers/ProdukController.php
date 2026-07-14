<?php
// app/Http/Controllers/ProdukController.php
namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
    $search = $request->get('search');
    
    $produk = Produk::when($search, function ($query, $search) {
        return $query->where('kode_produk', 'like', "%{$search}%")
            ->orWhere('nama_produk', 'like', "%{$search}%")
            ->orWhere('ukuran', 'like', "%{$search}%")
            ->orWhere('bahan', 'like', "%{$search}%")
            ->orWhere('satuan', 'like', "%{$search}%");
    })->paginate(10);
    
    return view('admin.produk.index', compact('produk', 'search'));
    }

    public function create()
    {
        return view('admin.produk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_produk' => 'required|unique:produk',
            'nama_produk' => 'required',
            'ukuran' => 'required',
            'bahan' => 'required',
            'satuan' => 'required'
        ]);

        Produk::create($request->all());
        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit(Produk $produk)
    {
        return view('admin.produk.edit', compact('produk'));
    }

    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'kode_produk' => 'required|unique:produk,kode_produk,' . $produk->id,
            'nama_produk' => 'required',
            'ukuran' => 'required',
            'bahan' => 'required',
            'satuan' => 'required'
        ]);

        $produk->update($request->all());
        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diupdate');
    }

    public function destroy(Produk $produk)
    {
        $produk->delete();
        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus');
    }
}