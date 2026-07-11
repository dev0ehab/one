<?php

namespace App\Console\Commands;

use App\Builders\MakeCrudBuilder;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeCrud extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud {name} {singular_ar} {plural_ar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a Dashboard CRUD for the given model';

    protected $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();
        $this->filesystem = $filesystem;
    }

    public function handle()
    {
        $name        = ucfirst($this->argument('name'));
        $singular_ar = $this->argument('singular_ar');
        $plural_ar   = $this->argument('plural_ar');

        // Confirmation step
        if (!$this->confirm("Are you sure you want to create CRUD for '{$name}'?")) {
            $this->info('CRUD generation canceled.');
            return;
        }

        $result = (new MakeCrudBuilder(
            $this->filesystem,
            $name,
            $singular_ar,
            $plural_ar
        ))
            ->createModel()
            ->createMigration()
            ->createService()
            ->createRequests()
            ->createController()
            ->createSeeder()
            ->createViews()
            ->createRoutes()
            ->createTranslations()
            ->build();

        $this->info($result);
    }
}
