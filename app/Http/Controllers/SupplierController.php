<?php
// app/Http/Controllers/SupplierController.php
namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
    $search = $request->get('search');
    
    $suppliers = Supplier::when($search, function ($query, $search) {
        return $query->where('nama_supplier', 'like', "%{$search}%")
            ->orWhere('alamat', 'like', "%{$search}%")
            ->orWhere('no_hp', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%");
    })->paginate(10);
    
    return view('admin.supplier.index', compact('suppliers', 'search'));
    }

    public function create()
    {
        return view('admin.supplier.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_supplier' => 'required',
            'alamat' => 'required',
            'no_hp' => 'required'
        ]);

        Supplier::create($request->all());
        return redirect()->route('admin.supplier.index')->with('success', 'Supplier berhasil ditambahkan');
    }

    public function edit(Supplier $supplier)
    {
        return view('admin.supplier.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'nama_supplier' => 'required',
            'alamat' => 'required',
            'no_hp' => 'required'
        ]);

        $supplier->update($request->all());
        return redirect()->route('admin.supplier.index')->with('success', 'Supplier berhasil diupdate');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('admin.supplier.index')->with('success', 'Supplier berhasil dihapus');
    }
}