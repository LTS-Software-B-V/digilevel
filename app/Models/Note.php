<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Note extends Model implements Auditable
{

    use SoftDeletes;

    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['user_id', 'note'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
