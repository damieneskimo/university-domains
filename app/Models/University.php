<?php

namespace App\Models;

use App\Models\UniversityDomain;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $casts = [
        'domains' => 'array',
    ];

    public $fillable = [
        'alpha_two_code', 'country', 'state_province', 'name', 'domains', 'ttl'
    ];

    protected $attributes = [
        'domains' => '{}'
    ];
}