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
        $data = Penjual::with('user')->get();

        $data->map(function ($penjual) {
        $penjual->foto_profil_url = $penjual->foto_profil 
            ? url('storage/' . $penjual->foto_profil) 
            : null;

        $penjual->foto_kios_url = $penjual->foto_kios 
            ? url('storage/' . $penjual->foto_kios) 
            : null;

        return $penjual;
        });

        return response()->json($data);
    }

    public function details($lokasi)
    {
        $data = Penjual::where('lokasi', $lokasi)->firstOrFail();
        
        return response()->json([
            'message' => 'Data penjual ditemukan',
            'data' => $data
        ], 200);
    }

    // Admin membuat data penjual untuk user tertentu (user harus bertipe seller).
    // Seller tidak boleh membuat penjual baru.
    // public function store(Request $request)
    // {
    //     // Route/Group harus memanggil middleware role:admin
    //     $data = $request->validate([
    //         'user_id' => 'required|exists:users,id',
    //         'nama' => 'nullable|string|max:255',
    //         'deskripsi' => 'nullable|string',
    //         'produk' => 'nullable|string',
    //         'lokasi' => 'nullable|string',
    //         'patokan' => 'nullable|string',
    //         'kontak' => 'nullable|string',
    //         'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png',
    //         'foto_kios' => 'nullable|image|mimes:jpg,jpeg,png',
    //     ]);

    //     $user = User::findOrFail($data['user_id']);
    //     if ($user->role !== 'seller') {
    //         return response()->json(['message' => 'Target user harus berrole seller'], 422);
    //     }

    //     if ($user->penjual) {
    //         return response()->json(['message' => 'User ini sudah memiliki penjual'], 422);
    //     }

    //     // handle file uploads
    //     if ($request->hasFile('foto_profil')) {
    //         $data['foto_profil'] = $request->file('foto_profil')->store('penjual/foto_profil', 'public');
    //     }
    //     if ($request->hasFile('foto_kios')) {
    //         $data['foto_kios'] = $request->file('foto_kios')->store('penjual/foto_kios', 'public');
    //     }

    //     $data['user_id'] = $user->id;
    //     $penjual = Penjual::create($data);

    //     return response()->json(['message' => 'Penjual dibuat', 'data' => $penjual], 201);
    // }

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
   
    public function getMyKios()
    {
        $user = auth()->user();

        if (!$user || !$user->penjual) {
            return response()->json(['message' => 'Data kios tidak ditemukan'], 404);
        }

        $penjual = $user->penjual;

        $penjual->foto_profil_url = $penjual->foto_profil 
        ? url('storage/' . $penjual->foto_profil) 
        : null;

        $penjual->foto_kios_url = $penjual->foto_kios 
        ? url('storage/' . $penjual->foto_kios) 
        : null;

        return response()->json($penjual);
    }

    // Helper: seller update kios miliknya lewat endpoint /penjual/me
    public function updateMyKios(Request $request)
    {
        $penjual = Penjual::where('user_id', Auth::id())->firstOrFail();
        return $this->update($request, $penjual->id);
    }

    public function updateDenahSVG(Request $request) 
    {
        $user = Auth::user();
    
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    
        $request->validate([
            'denah_svg' => 'required|mimes:svg|max:2048', // max 2MB atau lbih ya
        ]);
        
        if ($request->hasFile('denah_svg')) {
            // Buat folder jika belum ada
            Storage::disk('public')->makeDirectory('denah');
            
            // Hapus file lama jika ada
            if (Storage::disk('public')->exists('denah/pasar-owi.svg')) {
                Storage::disk('public')->delete('denah/pasar-owi.svg');
            }
            
            // Simpan file nama pasar-owi.svg
            $path = $request->file('denah_svg')->storeAs(
                'denah', 
                'pasar-owi.svg', 
                'public'
            );
            
            // Update path di database
            // $penjual->update(['denah_svg' => $path]); gausah ah aha aahah
            
            return response()->json([
                'success' => true,
                'message' => 'Denah SVG berhasil diupload',
                'path' => Storage::url($path)
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Tidak ada file yang diupload'
        ]);
    }

    public function getDenahSVG()
    {
            return response()->json([
            'success' => true,
            'svg' => Storage::disk('public')->get('denah/pasar-owi.svg'),
            'url' => Storage::url('denah/pasar-owi.svg'),
        ]);
    }
}
