<?php

namespace App\Models;

use App\Http\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    public function created_authors() {
        return $this->belongsTo(User::class, 'created_author');
    }

    public function updated_authors() {
        return $this->belongsTo(User::class, 'updated_author');
    }

    public function checkingApiUrl($apiUrl, $idGlobal = null) {
       if($idGlobal){
           if(($typeContentExistence = TypeContent::where('api_url', $apiUrl)->whereNotIn('id_global', [$idGlobal])->first()) !== null){
               return 'error';
           }
       } else {
           if(($typeContentExistence = TypeContent::where('api_url', $apiUrl)->first()) !== null){
               return 'error';
           }
       }
    }
}