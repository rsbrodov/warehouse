<?php

namespace App\Models;

use App\Http\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\ElementContentService;

class ElementContent extends Model
{
    use Uuids;
    use HasFactory;
    protected $table = 'element_contents';
    public $timestamps = false;

    protected $fillable = [
        'id_global',
        'type_content_id',
        'label',
        'api_url',
        'description',
        'active_from',
        'active_after',
        'status',
        'version_major',
        'version_minor',
        'body',
        'based_element',
        'created_author',
        'updated_author',
        'update_date',
        'created_date',
    ];

    public function createdAuthor()
    {
        return $this->belongsTo(User::class, 'created_author');
    }

    public function updatedAuthor()
    {
        return $this->belongsTo(User::class, 'updated_author');
    }

    public function typeContent()
    {
        return $this->belongsTo(TypeContent::class, 'type_content_id');
    }
}
