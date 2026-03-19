<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxSetting extends Model
{
    protected $fillable = [
        'hostel_id', 'taxes_enabled', 'vat_percentage',
        'city_tax_per_night', 'per_person_tax_per_night',
        'service_fee_percentage', 'extras_taxable',
    ];

    protected $casts = [
        'taxes_enabled'  => 'boolean',
        'extras_taxable' => 'boolean',
    ];

    public function hostel()
    {
        return $this->belongsTo(Hostel::class);
    }
}