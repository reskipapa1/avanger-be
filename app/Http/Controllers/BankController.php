<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    // GET /api/bank
    public function index()
    {
        return response()->json(Bank::all());
    }

    // GET /api/bank/{kode_bank}
    public function show($kode_bank)
    {
        $bank = Bank::find($kode_bank);

        if (!$bank) {
            return response()->json(['message' => 'Bank tidak ditemukan'], 404);
        }

        return response()->json($bank);
    }

    // POST /api/bank
    public function store(Request $request)
    {
        $request->validate([
            'kode_bank' => 'required|max:6|unique:bank,kode_bank',
            'nama' => 'required|unique:bank,nama',
        ]);

        $bank = Bank::create($request->all());

        return response()->json([
            'message' => 'Bank berhasil ditambahkan',
            'data' => $bank
        ], 201);
    }

    // PUT /api/bank/{kode_bank}
    public function update(Request $request, $kode_bank)
    {
        $bank = Bank::find($kode_bank);

        if (!$bank) {
            return response()->json(['message' => 'Bank tidak ditemukan'], 404);
        }

        $bank->update($request->all());

        return response()->json([
            'message' => 'Bank berhasil diupdate',
            'data' => $bank
        ]);
    }

    // DELETE /api/bank/{kode_bank}
    public function destroy($kode_bank)
    {
        $bank = Bank::find($kode_bank);

        if (!$bank) {
            return response()->json(['message' => 'Bank tidak ditemukan'], 404);
        }

        $bank->delete();

        return response()->json(['message' => 'Bank berhasil dihapus']);
    }
}
