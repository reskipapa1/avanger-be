<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    // USER: Lihat riwayat pinjaman sendiri
    public function myLoans()
    {
        $loans = Peminjaman::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        // ✅ Wrap in 'data' key untuk konsistensi dengan frontend
        return response()->json(['data' => $loans]);
    }

    // ADMIN/OWNER: Lihat SEMUA pinjaman
    public function index()
    {
        $loans = Peminjaman::with('user')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // ✅ Wrap in 'data' key
        return response()->json(['data' => $loans]);
    }

    // Detail pinjaman (user lihat punya sendiri, admin/owner lihat semua)
    public function show($id)
    {
        $loan = Peminjaman::with('user')->find($id);
        
        if (!$loan) {
            return response()->json(['message' => 'Pinjaman tidak ditemukan'], 404);
        }
        
        // ✅ Fix: role adalah 'customer' bukan 'user'
        if (Auth::user()->role === 'customer' && $loan->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'Unauthorized. Anda hanya bisa melihat pinjaman sendiri.'
            ], 403);
        }

        return response()->json(['data' => $loan]);
    }

    // USER: Ajukan pinjaman baru
    public function store(Request $request)
    {
        $request->validate([
            'nominal' => 'required|string|max:16', // ✅ Accept string karena dari frontend
            'rentang' => 'required|in:3 Bulan,6 Bulan,12 Bulan'
        ]);

        // Cek apakah user sudah ada pinjaman pending
        $existingPending = Peminjaman::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->exists();
        
        if ($existingPending) {
            return response()->json([
                'message' => 'Anda masih memiliki pinjaman yang sedang diproses. Tunggu hingga selesai.'
            ], 422);
        }

        $loan = Peminjaman::create([
            'user_id' => Auth::id(),
            'nominal' => $request->nominal,
            'rentang' => $request->rentang,
            'Waktu' => now(), // ✅ Add Waktu field
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Pinjaman berhasil diajukan',
            'data' => $loan
        ], 201);
    }

    // ADMIN: Approve pinjaman
    public function approve($id)
    {
        // ✅ Check role admin OR owner
        if (!in_array(Auth::user()->role, ['admin', 'owner'])) {
            return response()->json([
                'message' => 'Unauthorized. Hanya admin yang bisa approve pinjaman.'
            ], 403);
        }

        $loan = Peminjaman::find($id);
        
        if (!$loan) {
            return response()->json(['message' => 'Pinjaman tidak ditemukan'], 404);
        }

        if ($loan->status !== 'pending') {
            return response()->json([
                'message' => 'Pinjaman sudah diproses sebelumnya.'
            ], 422);
        }

        $loan->update(['status' => 'disetujui']);
        
        return response()->json([
            'message' => 'Pinjaman berhasil disetujui',
            'data' => $loan
        ]);
    }

    // ADMIN: Tolak pinjaman
    public function reject($id)
    {
        // ✅ Check role admin OR owner
        if (!in_array(Auth::user()->role, ['admin', 'owner'])) {
            return response()->json([
                'message' => 'Unauthorized. Hanya admin yang bisa menolak pinjaman.'
            ], 403);
        }

        $loan = Peminjaman::find($id);
        
        if (!$loan) {
            return response()->json(['message' => 'Pinjaman tidak ditemukan'], 404);
        }

        if ($loan->status !== 'pending') {
            return response()->json([
                'message' => 'Pinjaman sudah diproses sebelumnya.'
            ], 422);
        }

        $loan->update(['status' => 'ditolak']);
        
        return response()->json([
            'message' => 'Pinjaman berhasil ditolak',
            'data' => $loan
        ]);
    }

    // ADMIN: Update status pinjaman
    public function updateStatus(Request $request, $id)
    {
        // ✅ Check role admin OR owner
        if (!in_array(Auth::user()->role, ['admin', 'owner'])) {
            return response()->json([
                'message' => 'Unauthorized. Hanya admin yang bisa update status pinjaman.'
            ], 403);
        }

        $request->validate([
            'status' => 'required|in:pending,disetujui,ditolak,selesai'
        ]);

        $loan = Peminjaman::find($id);
        
        if (!$loan) {
            return response()->json(['message' => 'Pinjaman tidak ditemukan'], 404);
        }

        $loan->update(['status' => $request->status]);
        
        return response()->json([
            'message' => 'Status pinjaman berhasil diupdate',
            'data' => $loan
        ]);
    }
}
