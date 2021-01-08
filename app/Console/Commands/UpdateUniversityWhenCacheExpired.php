<?php

namespace App\Console\Commands;

use App\Events\UniversityCacheExpired;
use App\Models\University;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class UpdateUniversityWhenCacheExpired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:update-university-when-cache-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the university record when its cache expired';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $universities = University::all()->filter(function ($uni) {
            return $uni->expired();
        });

        foreach ($universities as $university) {
            UniversityCacheExpired::dispatch($university);
        }
    }
}
