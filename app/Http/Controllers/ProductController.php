<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;

class ProductController extends Controller
{
    // Menampilkan semua produk untuk pembeli (public)
    public function index()
    {
        $products = Produk::with('user')
            ->where('status', 'active')
            ->latest()
            ->paginate(12);

        return view('produk.index', compact('products')); // Public view
    }

    // Menampilkan produk milik petani
    public function myProducts()
    {
        $products = Produk::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('farmers.produk.my-products', compact('products'));
    }

    public function create()
    {
        return view('farmers.produk.create');
    }

    public function show($encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
            $product = Produk::with('user')->findOrFail($id);

            return view('produk.show', compact('product')); // Public view

        } catch (\Exception $e) {
            abort(404, 'Produk tidak ditemukan.');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'kategori' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // Handle upload foto
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('products', 'public');
        }

        Produk::create([
            'user_id' => Auth::id(),
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'kategori' => $request->kategori,
            'lokasi' => $request->lokasi,
            'foto' => $fotoPath,
            'status' => 'active'
        ]);

        // PERBAIKAN: Gunakan route yang benar
        return redirect()->route('products.my-products')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit($encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
            $product = Produk::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            return view('farmers.produk.edit', compact('product', 'encryptedId'));

        } catch (\Exception $e) {
            abort(404, 'Produk tidak ditemukan.');
        }
    }

    public function update(Request $request, $encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
            $product = Produk::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $request->validate([
                'nama_produk' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'harga' => 'required|numeric|min:0',
                'stok' => 'required|integer|min:0',
                'kategori' => 'required|string|max:255',
                'lokasi' => 'required|string|max:255',
                'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
            ]);

            $data = [
                'nama_produk' => $request->nama_produk,
                'deskripsi' => $request->deskripsi,
                'harga' => $request->harga,
                'stok' => $request->stok,
                'kategori' => $request->kategori,
                'lokasi' => $request->lokasi,
            ];

            // Handle upload foto
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($product->foto) {
                    Storage::disk('public')->delete($product->foto);
                }
                $fotoPath = $request->file('foto')->store('products', 'public');
                $data['foto'] = $fotoPath;
            }

            $product->update($data);

            // PERBAIKAN: Gunakan route yang benar
            return redirect()->route('products.my-products')
                ->with('success', 'Produk berhasil diperbarui!');

        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return redirect()->route('products.my-products')
                ->with('error', 'ID produk tidak valid.');
        } catch (\Exception $e) {
            return redirect()->route('products.my-products')
                ->with('error', 'Gagal memperbarui produk: ' . $e->getMessage());
        }
    }

    public function destroy($encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
            $product = Produk::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // Hapus foto jika ada
            if ($product->foto) {
                Storage::disk('public')->delete($product->foto);
            }

            $product->delete();

            // PERBAIKAN: Gunakan route yang benar
            return redirect()->route('products.my-products')
                ->with('success', 'Produk berhasil dihapus!');

        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return redirect()->route('products.my-products')
                ->with('error', 'ID produk tidak valid.');
        } catch (\Exception $e) {
            return redirect()->route('products.my-products')
                ->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }
}