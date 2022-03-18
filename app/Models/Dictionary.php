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
    protected $fillable = [
        'name',
        'code',
        'description',
        'archive',
        'created_author',
        'updated_author',
    ];
   /* protected $guarded = [

    ];*/
    public function created_author() {
        return $this->belongsTo(User::class, 'created_author');
    }

    public function updated_author() {
        return $this->belongsTo(User::class, 'updated_author');
    }

}
