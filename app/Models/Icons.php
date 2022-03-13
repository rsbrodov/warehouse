<?php

namespace App\Models;

use App\Http\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Icons extends Model
{
    use HasFactory, Uuids;

    protected $table = 'icons';
    protected $fillable = [
        'code',
        'name',
    ];

}
