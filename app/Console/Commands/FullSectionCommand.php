<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use App\Services\Command\Requestervice;
use Illuminate\Support\Facades\Artisan;
use App\Services\Command\ModelMigrationService;
use App\Services\Command\DashboardCrud\RouteService;
use App\Services\Command\DashboardCrud\ControllerService;
use App\Services\Command\DashboardCrud\TranslationService;
use App\Services\Command\DashboardCrud\Blades\CreateBladesService;

class FullSectionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:fullSection {name=name} {arSingleName=arSingleName} {arpluraleName=arpluraleName} {--seed} {--request} {--resource}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a full section for the given model';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $model         = $this->argument('name');
        $arSingleName  = $this->argument('arSingleName');
        $arpluraleName = $this->argument('arpluraleName');
        $folderName    = strtolower(Str::plural(class_basename($model)));
        $singleName    = strtolower(class_basename($model));

        if ($this->confirm('sure you want to continue with model name ' . $model, true)) {

            // #create model with mogration and model content
            (new ModelMigrationService)->createModel($model, $folderName);
            // #create model with mogration and model content

            // create Controller
            (new ControllerService)->createController($model, $folderName, $singleName, $arSingleName, $arpluraleName);
            // #create Controller

            // create folder and blade file
            (new CreateBladesService)->CreateBlades($folderName, $singleName);
            // create folder and blade file

            // create web routes
            (new RouteService)->createRoute($folderName, $singleName);
            // create web routes

            // create  translations at routes.php
            (new TranslationService)->createTranslation($folderName, $arSingleName, $arpluraleName, $singleName);
            // create  translations at routes.php

            // create seeder (optional)
            if ($this->option('seed')) {
                Artisan::call('make:seeder', ['name' => $model . 'TableSeeder']);
            }
            // #create seeder (optional)

            // create request (optional)
            if ($this->option('request')) {
                (new Requestervice)->createRequest($model);
            }
            // #create request (optional)

            // create request (optional)
            if ($this->option('resource')) {
                Artisan::call('make:resource', ['name' => 'Api/' . $model . 'Resource']);
            }
            // #create request (optional)

            // call back
            $this->info('Dashboard Controller , Model , DataBase Migrate , optional commands [ database seeder , admin section form request ] , Blade Folder And Blade File on dashboard , basic [index - store - update - delete] routes in web.php file for dashboard are created successfully ! ');
            // #call back
        }
    }
}
