<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['customer', 'admin', 'owner'])->default('customer');
            
            // ✅ Customer-specific fields (nullable for admin/owner)
            $table->string('no_hp', 12)->nullable();
            $table->string('no_hp2', 12)->nullable();
            $table->string('nama_no_hp2')->nullable();
            $table->string('relasi_no_hp2')->nullable();
            $table->string('NIK', 16)->nullable();
            $table->string('Norek', 20)->nullable();
            $table->string('Nama_Ibu')->nullable();
            $table->string('Pekerjaan')->nullable();
            $table->string('Gaji', 16)->nullable();
            $table->text('alamat')->nullable();
            $table->string('kode_bank', 10)->nullable();
            
            $table->foreign('kode_bank')->references('kode_bank')->on('banks')->onDelete('set null');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
