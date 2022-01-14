<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;
    protected $fillable = [
        'tanggal_masuk', 'nama_supplier', 'alamat', 'barang_id', 'qty', 'harga'
    ];
}
