<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MunicipalArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'responsible_name',
        'institutional_email',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
