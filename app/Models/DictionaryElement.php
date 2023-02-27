<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\Uuids;

class DictionaryElement extends Model
{
    use HasFactory;
    use Uuids;

    protected $table = 'dictionary_element';
    protected $fillable = [
        'dictionary_id',
        'value',
        'created_author',
        'updated_author',
    ];

    public function createdAuthor() {
        return $this->belongsTo(User::class, 'created_author');
    }

    public function updatedAuthor() {
        return $this->belongsTo(User::class, 'updated_author');
    }
}
