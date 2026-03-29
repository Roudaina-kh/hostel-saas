<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = ['hostel_id', 'description', 'amount', 'category', 'date'];
}
