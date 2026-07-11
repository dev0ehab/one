<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use App\Services\Command\ModelMigrationService;

class ApiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:api {name=name} {--seed} {--resource}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate an API CRUD for the given model';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $model = $this->argument('name');

        if ($this->confirm('sure you want to continue with name ' . $model, true)) {
            $folderName = strtolower(Str::plural(class_basename($model)));

            // create model with mogration and model content
            (new ModelMigrationService)->createModel($model, $folderName);
            // create model with mogration and model content

            // create seeder (optional)
            if ($this->option('seed')) {
                Artisan::call('make:seeder', ['name' => $model . 'TableSeeder']);
            }
            // create seeder (optional)

            // create request (optional)
            if ($this->option('resource')) {
                Artisan::call('make:resource', ['name' => 'Api/' . $model . 'Resource']);
            }
            // create request (optional)

            // call back
            $this->info('Dashboard Controller , Model , DataBase Migrate , optional commands [ database seeder , admin section form request ] , Blade Folder And Blade File on dashboard , basic [index - store - update - delete] routes in web.php file for dashboard are created successfully ! ');
            // #call back
        }
    }
}
