<?php

namespace App\Models;

use App\Http\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElementContent extends Model
{
    use Uuids;
    use HasFactory;
    protected $table = 'element_contents';

    protected $fillable = [
        'id_global',
        'label',
        'url',
        'owner',
        'active_from',
        'active_after',
        'status',
        'version_major',
        'version_minor',
        'body',
        'based_element',
        'created_author',
        'updated_author',
    ];
    public function created_authors() {
        return $this->belongsTo(User::class, 'created_author');
    }

    public function updated_authors() {
        return $this->belongsTo(User::class, 'updated_author');
    }
}
