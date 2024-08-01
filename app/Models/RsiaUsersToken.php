<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RsiaUsersToken extends Model
{
    use HasFactory;

    protected $table = 'rsia_users_token';

    public $timestamps = false;

    protected $fillable = [
        'identifier',
        'token',
    ];
}
