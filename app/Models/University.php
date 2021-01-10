<?php

namespace App\Models;

use App\Jobs\UpdateUniversityCache;
use App\Models\UniversityDomain;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    use HasFactory;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    public static function booted()
    {
        static::created(function ($university) {
            UpdateUniversityCache::dispatch($university)
                                ->delay(now()->addMinutes($university->ttl));
        });

        static::updated(function ($university) {
            UpdateUniversityCache::dispatch($university)
                                ->delay(now()->addMinutes($university->ttl));
        });
    }

    const SOURCE_API = 'http://universities.hipolabs.com/search?';

    protected $casts = [
        'domains' => 'array',
    ];

    public $fillable = [
        'alpha_two_code', 'country', 'state_province', 'name', 'domains', 'ttl'
    ];

    protected $attributes = [
        'domains' => '{}'
    ];

    public function expired()
    {
        return now()->greaterThanOrEqualTo($this->updated_at->addMinutes($this->ttl));
    }
}
