<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    // 🔹 Menampilkan semua laporan di halaman utama
    public function index()
    {
        $laporans = Laporan::latest()->paginate(6);
        return view('home', compact('laporans'));
    }

    // 🔹 Menampilkan form membuat laporan baru
    public function create()
    {
        return view('laporan.create');
    }

    // 🔹 Menyimpan laporan baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'foto' => 'nullable|image|max:2048',
        ]);

        $fotoPath = $request->hasFile('foto')
            ? $request->file('foto')->store('foto_laporan', 'public')
            : null;

        // ✅ Status default disesuaikan dengan enum di database
        Laporan::create([
            'user_id' => Auth::id(),
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'lokasi' => $request->lokasi,
            'status' => 'Dilaporkan',
            'foto' => $fotoPath,
        ]);

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dikirim!');
    }

    // 🔹 Menampilkan detail laporan
// 🔹 Menampilkan detail laporan
    public function show($id)
    {
        // Cari laporan berdasarkan ID, jika tidak ketemu tampilkan 404
        $laporan = Laporan::findOrFail($id);

        // Tampilkan view detail
        return view('laporan.show', compact('laporan'));
    }

    // 🔹 Menampilkan form edit laporan
    public function edit($id)
    {
        $laporan = Laporan::findOrFail($id);
        return view('laporan.edit', compact('laporan'));
    }

    // 🔹 Update laporan
    public function update(Request $request, Laporan $laporan)
{
    // Otorisasi akan mengecek policy yang sudah kita ubah
    $this->authorize('update', $laporan);

    // Aturan validasi dasar
    $rules = [
        'judul' => 'required|string|max:255',
        'deskripsi' => 'required|string',
        'lokasi' => 'required|string|max:255',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ];

    // Tambahkan aturan validasi status HANYA JIKA user adalah admin
    /** @var \App\Models\User|null $user */
$user = Auth::user();

if ($user && $user->isAdmin()) {
    $rules['status'] = 'required|in:Dilaporkan,Diproses,Selesai Ditangani';
}

    // Jalankan validasi
    $validated = $request->validate($rules);

    // Proses upload foto (jika ada file baru)
    if ($request->hasFile('foto')) {
        // Hapus foto lama jika ada
        if ($laporan->foto) {
            Storage::disk('public')->delete($laporan->foto); 
        }
        // Simpan file baru dan simpan path lengkapnya
        $path = $request->file('foto')->store('fotos_laporan', 'public');
        $validated['foto'] = $path;
    }

    // Update laporan
    $laporan->update($validated);

    return redirect()->route('laporan.show', $laporan)->with('success', 'Laporan berhasil diperbarui!');
}
    // 🔹 Hapus laporan
    public function destroy($id)
    {
        $laporan = Laporan::findOrFail($id);

        if ($laporan->foto && Storage::disk('public')->exists($laporan->foto)) {
            Storage::disk('public')->delete($laporan->foto);
        }

        $laporan->delete();

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dihapus!');
    }
}
