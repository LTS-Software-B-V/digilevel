<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class locationType extends Model implements Auditable
{
    use SoftDeletes;

    use \OwenIt\Auditing\Auditable;
    protected $casts = [
        'options' => 'array',
    ];

}
