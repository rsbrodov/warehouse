<?php

namespace App\Models;

use App\Http\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\TypeContentService;

class TypeContent extends Model
{
    use Uuids;
    use HasFactory;
    protected $table = 'type_contents';

    protected $fillable = [
        'id_global',
        'name',
        'description',
        'owner',
        'icon',
        'active_from',
        'active_after',
        'status',
        'api_url',
        'body',
        'version_major',
        'version_minor',
        'created_author',
        'updated_author',
    ];

    public function createdAuthor()
    {
        return $this->belongsTo(User::class, 'created_author');
    }

    public function updatedAuthor()
    {
        return $this->belongsTo(User::class, 'updated_author');
    }
}
