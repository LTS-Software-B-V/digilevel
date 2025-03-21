<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Space extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

}
