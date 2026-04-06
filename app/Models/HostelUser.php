<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class HostelUser extends Pivot
{
    protected $table = 'hostel_user';

    protected $fillable = [
        'hostel_id',
        'user_id',
        'role',
        'status',
    ];

    public $incrementing = true;

    protected function casts(): array
    {
        return [
            'status' => 'string',
        ];
    }
}
