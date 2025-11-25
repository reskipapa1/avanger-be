<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    // GET /api/bank (PUBLIC)
    public function index()
    {
        $banks = Bank::all();
        // Frontend biasanya butuh array langsung atau dibungkus data
        return response()->json($banks); 
        
        // CATATAN: 
        // Kalau mau pakai format { message, data }, pastikan Frontend menyesuaikan.
        // Tapi format return $banks; (array langsung) lebih mudah ditangkap axios.
    }

    // GET /api/bank/{kode_bank} (PUBLIC)
    public function show($kode_bank)
    {
        $bank = Bank::find($kode_bank);

        if (!$bank) {
            return response()->json(['message' => 'Bank tidak ditemukan'], 404);
        }

        return response()->json($bank);
    }

    // POST /api/bank (PROTECTED - OWNER)
    public function store(Request $request)
    {
        $request->validate([
            // Pastikan nama tabel di sini 'bank' (singular) sesuai database kamu
            'kode_bank' => 'required|string|max:10|unique:bank,kode_bank',
            'nama_bank' => 'required|string|max:100|unique:bank,nama_bank',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:50',
            'provinsi' => 'required|string|max:50'
        ]);

        $bank = Bank::create($request->all());

        return response()->json([
            'message' => 'Bank berhasil ditambahkan',
            'data' => $bank
        ], 201);
    }

    // PUT /api/bank/{kode_bank} (PROTECTED - OWNER)
    public function update(Request $request, $kode_bank)
    {
        $bank = Bank::find($kode_bank);

        if (!$bank) {
            return response()->json(['message' => 'Bank tidak ditemukan'], 404);
        }

        $request->validate([
            // Validasi unique ignore id saat ini
            'nama_bank' => 'required|string|max:100|unique:bank,nama_bank,' . $kode_bank . ',kode_bank',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:50',
            'provinsi' => 'required|string|max:50'
        ]);

        $bank->update($request->all());

        return response()->json([
            'message' => 'Bank berhasil diupdate',
            'data' => $bank
        ]);
    }

    // DELETE /api/bank/{kode_bank} (PROTECTED - OWNER)
    public function destroy($kode_bank)
    {
        $bank = Bank::find($kode_bank);

        if (!$bank) {
            return response()->json(['message' => 'Bank tidak ditemukan'], 404);
        }

        // Cek relasi user sebelum hapus (opsional, tapi bagus)
        // Pastikan di Model Bank ada method public function users() { ... }
        try {
            if (method_exists($bank, 'users') && $bank->users()->exists()) {
                 return response()->json([
                    'message' => 'Tidak bisa menghapus bank. Masih ada user yang menggunakan bank ini.'
                ], 422);
            }
        } catch (\Exception $e) {
            // Abaikan error jika relasi belum dibuat, lanjut delete
        }

        $bank->delete();

        return response()->json([
            'message' => 'Bank berhasil dihapus'
        ]);
    }
}