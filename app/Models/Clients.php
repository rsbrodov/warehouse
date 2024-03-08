<?php

namespace App\Models;

use App\Http\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    use HasFactory;
    use Uuids;
    protected $table = 'clients';
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
