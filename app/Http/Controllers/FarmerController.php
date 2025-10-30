<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Alamat;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;

class FarmerController extends Controller
{
    use AuthorizesRequests;
    // =============== DASHBOARD ===============
    public function dashboard()
    {
        $user = Auth::user();

        // Total produk berdasarkan produk yang ditambahkan oleh petani
        $totalProducts = Produk::where('user_id', $user->id)->count();

        // Pesanan menunggu (pending) dari pembeli untuk produk petani ini
        $pendingOrders = Order::whereHas('product', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('status', 'pending')->count();

        // Total pendapatan (dari pesanan completed) bulan ini
        $currentMonthRevenue = Order::whereHas('product', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_price');

        // Total penjualan (jumlah pesanan completed)
        $totalSales = Order::whereHas('product', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('status', 'completed')->count();

        // Produk baru minggu ini
        $newProductsThisWeek = Produk::where('user_id', $user->id)
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->count();

        // Pendapatan bulan lalu untuk perbandingan
        $lastMonthRevenue = Order::whereHas('product', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->sum('total_price');

        // Hitung persentase kenaikan pendapatan
        $revenueIncrease = 0;
        if ($lastMonthRevenue > 0) {
            $revenueIncrease = (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100;
        }

        return view('farmers.dashboard', compact(
            'user',
            'totalProducts',
            'pendingOrders',
            'currentMonthRevenue',
            'totalSales',
            'newProductsThisWeek',
            'revenueIncrease'
        ));
    }

    // =============== PRODUK CRUD ===============
    public function produkIndex()
    {
        $produk = Produk::where('user_id', Auth::id())->get();
        return view('petani.produk.index', compact('produk'));
    }

    public function produkCreate()
    {
        return view('petani.produk.create');
    }

    public function produkStore(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $fotoPath = $request->file('foto') ? $request->file('foto')->store('produk', 'public') : null;

        Produk::create([
            'user_id' => Auth::id(),
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'foto' => $fotoPath
        ]);

        return redirect()->route('petani.produk.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function produkEdit($id)
    {
        $produk = Produk::findOrFail($id);
        $this->authorize('update', $produk);
        return view('petani.produk.edit', compact('produk'));
    }

    public function produkUpdate(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        $this->authorize('update', $produk);

        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('foto')) {
            if ($produk->foto)
                Storage::disk('public')->delete($produk->foto);
            $produk->foto = $request->file('foto')->store('produk', 'public');
        }

        $produk->update($request->only(['nama_produk', 'deskripsi', 'harga', 'stok', 'foto']));

        return redirect()->route('petani.produk.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function produkDestroy($id)
    {
        $produk = Produk::findOrFail($id);
        if ($produk->foto)
            Storage::disk('public')->delete($produk->foto);
        $produk->delete();
        return redirect()->route('petani.produk.index')->with('success', 'Produk berhasil dihapus!');
    }

    // =============== ALAMAT CRUD ===============
    public function alamatIndex()
    {
        $alamat = Alamat::where('user_id', Auth::id())->get();
        return view('petani.alamat.index', compact('alamat'));
    }

    public function alamatCreate()
    {
        return view('petani.alamat.create');
    }

    public function alamatStore(Request $request)
    {
        $request->validate([
            'nama_tempat' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric'
        ]);

        Alamat::create([
            'user_id' => Auth::id(),
            'nama_tempat' => $request->nama_tempat,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ]);

        return redirect()->route('petani.alamat.index')->with('success', 'Alamat berhasil ditambahkan!');
    }

    public function alamatDestroy($id)
    {
        $alamat = Alamat::findOrFail($id);
        $alamat->delete();
        return redirect()->route('petani.alamat.index')->with('success', 'Alamat berhasil dihapus!');
    }
}
