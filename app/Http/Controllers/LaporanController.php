<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    // Menampilkan semua laporan di halaman utama
    public function index()
    {
        $laporans = Laporan::latest()->paginate(6);
        return view('home', compact('laporans'));
    }

    // Menampilkan form membuat laporan baru
    public function create()
    {
        return view('laporan.create');
    }

    // Menyimpan laporan baru ke database
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

    // Menampilkan detail laporan
    public function show($id)
    {
        $laporan = Laporan::findOrFail($id);
        return view('laporan.show', compact('laporan'));
    }

    // Menampilkan form edit laporan
    public function edit($id)
    {
        $laporan = Laporan::findOrFail($id);
        return view('laporan.edit', compact('laporan'));
    }

    // Update laporan
    public function update(Request $request, $id)
    {
        // Cari laporan berdasarkan ID
        $laporan = Laporan::findOrFail($id);
        
        // Cek apakah user berhak edit laporan ini
        $this->authorize('update', $laporan);

        // Validasi input
        $rules = [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Jika user adalah admin, tambahkan validasi status
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user && $user->isAdmin()) {
            $rules['status'] = 'required|in:Dilaporkan,Diproses,Selesai Ditangani';
        }

        // Validasi
        $validated = $request->validate($rules);

        // Proses upload foto baru (jika ada)
        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($laporan->foto) {
                Storage::disk('public')->delete($laporan->foto);
            }
            // Simpan foto baru
            $validated['foto'] = $request->file('foto')->store('fotos_laporan', 'public');
        }

        // Update data laporan
        $laporan->update($validated);

        // Redirect ke halaman detail laporan dengan pesan sukses
        return redirect()->route('laporan.baca', $laporan->id)
                         ->with('success', 'Laporan berhasil diperbarui!');
    }

    // Hapus laporan
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