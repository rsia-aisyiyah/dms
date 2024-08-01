<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPerawatan extends Model
{
    use HasFactory;
    protected $table = 'kategori_perawatan';

    public function jnsPerawatan()
    {
        return $this->hasMany(JnsPerawatan::class, 'kd_kategori', 'kd_kategori');
    }
}
