<?php

namespace App\Http\Controllers;

use App\Models\Penjual;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PenjualController extends Controller
{
    // Admin: semua penjual, Seller: hanya kios miliknya
    public function index()
    {
        // $user = Auth::user();

        // if ($user->role === 'admin') {
        //     $data = Penjual::with('user')->get();
        // } else {
        //     $data = Penjual::with('user')->where('user_id', $user->id)->get();
        // }
        $data = Penjual::with('user')->get();

        return response()->json($data);
    }

    // Admin membuat data penjual untuk user tertentu (user harus bertipe seller).
    // Seller tidak boleh membuat penjual baru.
    public function store(Request $request)
    {
        // Route/Group harus memanggil middleware role:admin
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nama' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'produk' => 'nullable|string',
            'lokasi' => 'nullable|string',
            'patokan' => 'nullable|string',
            'kontak' => 'nullable|string',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png',
            'foto_kios' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        $user = User::findOrFail($data['user_id']);
        if ($user->role !== 'seller') {
            return response()->json(['message' => 'Target user harus berrole seller'], 422);
        }

        if ($user->penjual) {
            return response()->json(['message' => 'User ini sudah memiliki penjual'], 422);
        }

        // handle file uploads
        if ($request->hasFile('foto_profil')) {
            $data['foto_profil'] = $request->file('foto_profil')->store('penjual/foto_profil', 'public');
        }
        if ($request->hasFile('foto_kios')) {
            $data['foto_kios'] = $request->file('foto_kios')->store('penjual/foto_kios', 'public');
        }

        $data['user_id'] = $user->id;
        $penjual = Penjual::create($data);

        return response()->json(['message' => 'Penjual dibuat', 'data' => $penjual], 201);
    }

    // Show single penjual (admin boleh semua, seller hanya miliknya)
    public function show($id)
    {
        $penjual = Penjual::with('user')->findOrFail($id);

        $user = Auth::user();
        if ($user->role === 'seller' && $penjual->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($penjual);
    }

    // Update: admin bisa update semua, seller hanya kios miliknya
    public function update(Request $request, $id)
    {
        $penjual = Penjual::findOrFail($id);
        $user = Auth::user();

        if ($user->role === 'seller' && $penjual->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'nama' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'produk' => 'nullable|string',
            'lokasi' => 'nullable|string',
            'patokan' => 'nullable|string',
            'kontak' => 'nullable|string',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png',
            'foto_kios' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        if ($request->hasFile('foto_profil')) {
            // hapus file lama opsional
            if ($penjual->foto_profil) {
                Storage::disk('public')->delete($penjual->foto_profil);
            }
            $data['foto_profil'] = $request->file('foto_profil')->store('penjual/foto_profil', 'public');
        }
        if ($request->hasFile('foto_kios')) {
            if ($penjual->foto_kios) {
                Storage::disk('public')->delete($penjual->foto_kios);
            }
            $data['foto_kios'] = $request->file('foto_kios')->store('penjual/foto_kios', 'public');
        }

        $penjual->update($data);

        return response()->json(['message' => 'Penjual diupdate', 'data' => $penjual]);
    }

    // Hapus penjual — hanya admin
    public function destroy($id)
    {
        // route harus memakai role:admin
        $penjual = Penjual::findOrFail($id);

        // hapus file foto jika ada
        if ($penjual->foto_profil) {
            Storage::disk('public')->delete($penjual->foto_profil);
        }
        if ($penjual->foto_kios) {
            Storage::disk('public')->delete($penjual->foto_kios);
        }

        $penjual->delete();

        return response()->json(['message' => 'Penjual dihapus']);
    }

    // Helper: endpoint untuk seller akses kios dirinya sendiri dengan mudah
    // public function myKios()
    // {
    //     $penjual = Penjual::with('user')->where('user_id', Auth::id())->first();
    //     if (!$penjual) {
    //         return response()->json(['message' => 'Kios tidak ditemukan'], 404);
    //     }
    //     return response()->json($penjual);
    // }

    // Helper: seller update kios miliknya lewat endpoint /penjual/me
    public function updateMyKios(Request $request)
    {
        $penjual = Penjual::where('user_id', Auth::id())->firstOrFail();
        return $this->update($request, $penjual->id);
    }
}
