<?php

namespace App\Models;

use App\Http\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeContent extends Model
{
    use HasFactory;
    use Uuids;
    protected $table = 'type_contents';
    protected $fillable = [
        'name',
        'description',
        'id_global',
        'icon',
        'active_from',
        'active_after',
        'status',
        'api_url',
        'body',
        'created_author',
        'updated_author',
    ];
    /*protected $guarded = [
        'version_major',
        'version_minor',
        'based_type',
    ];*/
    public function created_author() {
        return $this->belongsTo(User::class, 'created_author');
    }

    public function updated_author() {
        return $this->belongsTo(User::class, 'updated_author');
    }
}
