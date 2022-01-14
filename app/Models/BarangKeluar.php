<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    use HasFactory;
    protected $fillable = [
        'tanggal_keluar', 'nama_customer', 'alamat', 'barang_id', 'qty', 'harga'
    ];
}
