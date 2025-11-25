<?php

namespace App\Http\Controllers\Api;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;


class LaporanApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    /** @var \App\Models\User $user */
    $user = Auth::user();

    if ($user->isAdmin()) {
        // Admin melihat semua laporan
        $laporans = Laporan::with('user')->latest()->get();
    } else {
        // User biasa melihat laporan sendiri
        $laporans = Laporan::with('user')->where('user_id', $user->id)->latest()->get();
    }

    return response()->json([
        'message' => 'List laporan retrieved successfully',
        'data' => $laporans
    ]);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'judul' => 'required|string|max:255',
        'deskripsi' => 'required|string',
        'lokasi' => 'required|string|max:255',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $path = null;
    if ($request->hasFile('foto')) {
        // Simpan file dan dapatkan path
        $path = $request->file('foto')->store('fotos_laporan', 'public');
    }

    $laporan = Laporan::create([
        'user_id' => Auth::id(),
        'judul' => $request->judul,
        'deskripsi' => $request->deskripsi,
        'lokasi' => $request->lokasi,
        'foto' => $path, // Simpan path lengkap
        'status' => 'Dilaporkan', // Default status
    ]);

    return response()->json([
        'message' => 'Laporan created successfully',
        'data' => $laporan
    ], 201);
}

    /**
     * Display the specified resource.
     */
public function show(string $id)
{
    $laporan = Laporan::with('user')->find($id);

    if (!$laporan) {
        return response()->json(['message' => 'Laporan not found'], 404);
    }

    // Keamanan: Cek apakah user berhak melihat (Admin atau Pemilik)
    /** @var \App\Models\User $user */
    $user = Auth::user();
    
    if (!$user->isAdmin() && $laporan->user_id !== $user->id) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    return response()->json(['data' => $laporan]);
}

    /**
     * Update the specified resource in storage.
     */
 public function update(Request $request, string $id)
{
    $laporan = Laporan::find($id);
    if (!$laporan) return response()->json(['message' => 'Not found'], 404);

    /** @var \App\Models\User $user */
    $user = Auth::user();

    // Cek Otorisasi (Sama seperti Policy)
    if (!$user->isAdmin() && $laporan->user_id !== $user->id) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }
    // User biasa tidak boleh edit jika status bukan 'Dilaporkan'
    if (!$user->isAdmin() && $laporan->status !== 'Dilaporkan') {
        return response()->json(['message' => 'Laporan tidak dapat diedit karena sudah diproses'], 403);
    }

    // Validasi
    $rules = [
        'judul' => 'required|string|max:255',
        'deskripsi' => 'required|string',
        'lokasi' => 'required|string|max:255',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ];

    // Admin boleh update status
    if ($user->isAdmin()) {
        $rules['status'] = 'required|in:Dilaporkan,Diproses,Selesai Ditangani';
    }

    $request->validate($rules);

    // Handle File
    if ($request->hasFile('foto')) {
        if ($laporan->foto) {
            Storage::disk('public')->delete($laporan->foto);
        }
        $laporan->foto = $request->file('foto')->store('fotos_laporan', 'public');
    }

    // Update Data
    $laporan->judul = $request->judul;
    $laporan->deskripsi = $request->deskripsi;
    $laporan->lokasi = $request->lokasi;
    
    if ($user->isAdmin() && $request->has('status')) {
        $laporan->status = $request->status;
    }

    $laporan->save();

    return response()->json([
        'message' => 'Laporan updated successfully',
        'data' => $laporan
    ]);
}

    /**
     * Remove the specified resource from storage.
     */
public function destroy(string $id)
{
    $laporan = Laporan::find($id);
    if (!$laporan) return response()->json(['message' => 'Not found'], 404);

    /** @var \App\Models\User $user */
    $user = Auth::user();

    // Cek Otorisasi
    if (!$user->isAdmin()) {
        if ($laporan->user_id !== $user->id) return response()->json(['message' => 'Unauthorized'], 403);
        if ($laporan->status !== 'Dilaporkan') return response()->json(['message' => 'Cannot delete processed report'], 403);
    }

    // Hapus Foto
    if ($laporan->foto) {
        Storage::disk('public')->delete($laporan->foto);
    }

    $laporan->delete();

    return response()->json(['message' => 'Laporan deleted successfully']);
}
}
