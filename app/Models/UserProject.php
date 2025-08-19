<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProject extends Model
{
    protected $fillable = [
        'project_name', 'user_id', 'start_date', 'end_date', 'project_price', 'project_status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'project_price' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
