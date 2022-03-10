<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\Uuids;
class TypeContent extends Model
{
    use Uuids;
    use HasFactory;
    protected $fillable = [
        'id_global',
        'name',
        'description',
        'owner',
        'icon',
        'active_from',
        'active_after',
        'status',
        'version_major',
        'version_minor',
        'api_url',
        'body',
        'based_type',
        'created_author',
        'updated_author',
    ];
}
