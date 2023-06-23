<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\Uuids;
use Illuminate\Support\Str;

class Dictionary extends Model
{
    use HasFactory;
    use Uuids;
    protected $table = 'dictionary';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'code',
        'description',
        'archive',
        'created_author',
        'updated_author',
        'update_date',
        'created_date',
    ];
    public function createdAuthor() {
        return $this->belongsTo(User::class, 'created_author');
    }

    public function updatedAuthor() {
        return $this->belongsTo(User::class, 'updated_author');
    }

}
