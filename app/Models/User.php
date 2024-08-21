<?php

namespace App\Models;

use App\Models\Departemen;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;
    protected $table = 'rsia_user';
    protected $primaryKey = 'id_user';

    public function id_user()
    {
        return "aes_encrypt('', 'windi')";
    }
    public function getAuthPassword()
    {
        return $this->passwd;
    }
    public function departemen(): BelongsTo
    {
        return $this->belongsTo(Departemen::class, 'dep_id', 'dep_id');
    }
}
