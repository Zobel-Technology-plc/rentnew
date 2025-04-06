<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    protected $fillable = [
        'company_name',
        'logo_path',
        'currency_code',
        'currency_symbol'
    ];

    public static function getSettings()
    {
        return self::first() ?? self::create();
    }
} 