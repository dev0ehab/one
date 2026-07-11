<?php

namespace App\Builders;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeCrudBuilder
{
    protected $filesystem;
    protected $capitalName;
    protected $smallName;
    protected $smallPluralName;
    protected $smallPluralNameWithUnderscore;
    protected $pluralCamelCaseName;
    protected $capitalPluralName;
    protected $singularAr;
    protected $pluralAr;
    protected $modelCreated        = false;
    protected $migrationCreated    = false;
    protected $serviceCreated      = false;
    protected $requestsCreated     = false;
    protected $seederCreated       = false;
    protected $controllerCreated   = false;
    protected $viewsCreated        = false;
    protected $routesCreated       = false;
    protected $translationsCreated = false;

    public function __construct(Filesystem $filesystem, $name, $singularAr, $pluralAr)
    {
        $this->filesystem      = $filesystem;

        // Set Names with all lower , underscores and singular cases
        $this->setNames($name, $singularAr, $pluralAr);
    }
    //------------------------------------------------------------------------------------------------------------------------------

    public function createModel()
    {
        $modelPath    = app_path("Models/{$this->capitalName}.php");
        $sourceModel  = base_path('App/Console/CrudCommands/Model.stub');
        $modelContent = $this->filesystem->get($sourceModel);

        if ($this->filesystem->exists($modelPath)) {
            return $this;
        }

        $modelContent = str_replace(
            ['{{capitalName}}', '{{smallName}}', '{{smallPluralName}}'],
            [$this->capitalName, $this->smallName, $this->smallPluralName],
            $modelContent
        );

        $this->filesystem->put($modelPath, $modelContent);

        $this->modelCreated = true;

        return $this;
    }
    //------------------------------------------------------------------------------------------------------------------------------

    public function createMigration()
    {
        $timestamp       = date('Y_m_d_His');
        $tableName       = $this->toSnakeCase($this->capitalName);
        $migrationPath   = database_path("migrations/{$timestamp}_create_{$tableName}_table.php");
        $sourceMigration = base_path('App/Console/CrudCommands/Migrations/Migration.stub');

        // Extract the base migration file name without the timestamp
        $baseMigrationFileName = "create_{$tableName}_table.php";

        // Check if a migration file with the same name (excluding timestamp) already exists
        $migrationFiles = glob(database_path('migrations/*_' . $baseMigrationFileName));

        if (!empty($migrationFiles)) {
            return $this;
        }

        // Proceed with creating the migration file
        $migrationContent = $this->filesystem->get($sourceMigration);

        $migrationContent = str_replace(
            ['{{capitalName}}', '{{smallName}}', '{{smallPluralName}}'],
            [$this->capitalName, $this->smallName, $tableName],
            $migrationContent
        );

        $this->filesystem->put($migrationPath, $migrationContent);

        $this->migrationCreated = true;

        return $this;
    }
    //------------------------------------------------------------------------------------------------------------------------------

    public function createService()
    {
        $servicePath    = app_path("Services/Entity/{$this->capitalName}Service.php");
        $sourceService  = base_path('App/Console/CrudCommands/Service.stub');
        $serviceContent = $this->filesystem->get($sourceService);

        if ($this->filesystem->exists($servicePath)) {
            return $this;
        }

        if (!$this->filesystem->exists(app_path('Services/Entity'))) {
            $this->filesystem->makeDirectory(app_path('Services/Entity'), 0755, true);
        }

        $serviceContent = str_replace(
            ['{{capitalName}}', '{{smallName}}', '{{smallPluralName}}'],
            [$this->capitalName, $this->smallName, $this->smallPluralName],
            $serviceContent
        );

        $this->filesystem->put($servicePath, $serviceContent);

        $this->serviceCreated = true;

        return $this;
    }
    //------------------------------------------------------------------------------------------------------------------------------

    public function createRequests()
    {
        $requestFolderPath = app_path("Http/Requests/Admin/{$this->pluralCamelCaseName}");

        if ($this->filesystem->exists($requestFolderPath)) {
            return $this;
        }

        $this->filesystem->makeDirectory($requestFolderPath, 0755, true);

        $storeRequestPath    = "{$requestFolderPath}/Store.php";
        $sourceStoreRequest  = base_path('App/Console/CrudCommands/Requests/Store.stub');
        $storeRequestContent = $this->filesystem->get($sourceStoreRequest);

        if (!$this->filesystem->exists($storeRequestPath)) {
            $storeRequestContent = str_replace(
                ['{{capitalName}}', '{{smallName}}', '{{smallPluralName}}', '{{pluralCamelCaseName}}'],
                [$this->capitalName, $this->smallName, $this->smallPluralName, $this->pluralCamelCaseName],
                $storeRequestContent
            );

            $this->filesystem->put($storeRequestPath, $storeRequestContent);
        }

        $updateRequestPath    = "{$requestFolderPath}/Update.php";
        $sourceUpdateRequest  = base_path('App/Console/CrudCommands/Requests/Update.stub');
        $updateRequestContent = $this->filesystem->get($sourceUpdateRequest);

        if (!$this->filesystem->exists($updateRequestPath)) {
            $updateRequestContent = str_replace(
                ['{{capitalName}}', '{{smallName}}', '{{smallPluralName}}', '{{pluralCamelCaseName}}'],
                [$this->capitalName, $this->smallName, $this->smallPluralName, $this->pluralCamelCaseName],
                $updateRequestContent
            );

            $this->filesystem->put($updateRequestPath, $updateRequestContent);
        }

        $this->requestsCreated = true;

        return $this;
    }
    //------------------------------------------------------------------------------------------------------------------------------

    public function createController()
    {
        $controllerPath    = app_path("Http/Controllers/Admin/{$this->capitalName}Controller.php");
        $sourceController  = base_path('App/Console/CrudCommands/controller.stub');
        $controllerContent = $this->filesystem->get($sourceController);

        if ($this->filesystem->exists($controllerPath)) {
            return $this;
        }

        $controllerContent = str_replace(
            ['{{capitalName}}', '{{smallName}}', '{{smallPluralName}}'],
            [$this->capitalName, $this->smallName, $this->smallPluralName],
            $controllerContent
        );

        $this->filesystem->put($controllerPath, $controllerContent);

        $this->controllerCreated = true;

        return $this;
    }
    //------------------------------------------------------------------------------------------------------------------------------

    public function createSeeder()
    {
        $seederPath   = database_path("seeders/{$this->capitalName}TableSeeder.php");
        $sourceSeeder = base_path('App/Console/CrudCommands/Seeder.stub');

        if ($this->filesystem->exists($seederPath)) {
            return $this;
        }

        $seederContent = $this->filesystem->get($sourceSeeder);

        $seederContent = str_replace(
            ['{{capitalName}}', '{{smallName}}', '{{smallPluralName}}'],
            [$this->capitalName, $this->smallName, $this->smallPluralName],
            $seederContent
        );

        $this->filesystem->put($seederPath, $seederContent);

        $this->seederCreated = true;

        return $this;
    }
    //------------------------------------------------------------------------------------------------------------------------------

    public function createViews()
    {
        $viewsPath = resource_path("views/admin/{$this->smallPluralName}");

        if ($this->filesystem->exists($viewsPath)) {
            return $this;
        }

        $this->filesystem->makeDirectory($viewsPath, 0755, true);

        $views = ['index', 'table', 'create', 'edit', 'show'];
        foreach ($views as $view) {
            $sourceView = base_path("App/Console/CrudCommands/Blades/{$view}.blade.stub");

            $viewContent = $this->filesystem->get($sourceView);
            $viewContent = str_replace(
                ['{{capitalName}}', '{{smallName}}', '{{smallPluralName}}'],
                [$this->capitalName, $this->smallName, $this->smallPluralName],
                $viewContent
            );

            $this->filesystem->put("{$viewsPath}/{$view}.blade.php", $viewContent);
        }

        $this->viewsCreated = true;

        return $this;
    }
    //------------------------------------------------------------------------------------------------------------------------------

    public function createRoutes()
    {
        $webRoutesPath = base_path('routes/web.php');
        $sourceRoutes = base_path('App/Console/CrudCommands/routes.stub');

        // Get the content of web.php
        $routesContent = $this->filesystem->get($webRoutesPath);

        // Define the new controller to add
        $newController = "$this->capitalName" . "Controller";

        // Check if the controller is already present in web.php
        if (str_contains($routesContent, $newController)) {
            // Controller is already present, no need to add the routes
            return $this;
        }

        // Find the 'add your routes here' comment
        $comment = '#new_routes_here';
        $commentPosition = strpos($routesContent, $comment);

        if ($commentPosition === false) {
            return $this;
        }

        // Load the routes template
        $routeTemplate = $this->filesystem->get($sourceRoutes);

        // Replace the placeholders in the template
        $routeTemplate = str_replace(
            ['{{capitalName}}', '{{smallName}}', '{{smallPluralName}}'],
            [$this->capitalName, $this->smallName, $this->smallPluralName],
            $routeTemplate
        );

        // Adjust the position to insert before the comment
        $insertPosition = $commentPosition;

        // Insert the routes before the comment
        $updatedRoutes = substr_replace($routesContent, $routeTemplate, $insertPosition, 0);

        // Save the updated content back to web.php
        $this->filesystem->put($webRoutesPath, $updatedRoutes);

        $this->routesCreated = true;

        return $this;
    }
    //------------------------------------------------------------------------------------------------------------------------------

    public function createTranslations()
    {
        $adminPaths = [
            'ar' => resource_path('lang/ar/admin.php'),
            'en' => resource_path('lang/en/admin.php')
        ];

        $routePaths = [
            'ar' => resource_path('lang/ar/routes.php'),
            'en' => resource_path('lang/en/routes.php')
        ];

        $stubs = [
            'admin' => [
                'ar' => base_path('App/Console/CrudCommands/Langs/admin_ar.stub'),
                'en' => base_path('App/Console/CrudCommands/Langs/admin_en.stub')
            ],
            'routes' => [
                'ar' => base_path('App/Console/CrudCommands/Langs/routes_ar.stub'),
                'en' => base_path('App/Console/CrudCommands/Langs/routes_en.stub')
            ]
        ];

        // Load, translate, and update files
        $this->processTranslation($adminPaths, $stubs['admin']);
        $this->processTranslation($routePaths, $stubs['routes']);

        // Set the translation files created flag
        $this->translationsCreated = true;

        return $this;
    }
    //------------------------------------------------------------------------------------------------------------------------------

    public function build()
    {
        $report = "CRUD Creation Report for {$this->capitalName}:\n \n";

        if ($this->modelCreated) {
            $report .= "- $this->capitalName Model : Created successfully.\n";
        } else {
            $report .= "- $this->capitalName Model : already exists.\n";
        }

        if ($this->migrationCreated) {
            $report .= "- $this->smallPluralName table : Created successfully.\n";
        } else {
            $report .= "- $this->smallPluralName table : already exists.\n";
        }

        if ($this->serviceCreated) {
            $report .= "- $this->capitalName Service : Created successfully.\n";
        } else {
            $report .= "- $this->capitalName Service : already exists.\n";
        }

        if ($this->requestsCreated) {
            $report .= "- $this->capitalName Requests : Created successfully.\n";
        } else {
            $report .= "- $this->capitalName Requests : already exists.\n";
        }

        if ($this->controllerCreated) {
            $report .= "- $this->capitalName Controller : Created successfully.\n";
        } else {
            $report .= "- $this->capitalName Controller : already exists.\n";
        }

        if ($this->seederCreated) {
            $report .= "- $this->capitalName Seeder : Created successfully.\n";
        } else {
            $report .= "- $this->capitalName Seeder : already exists.\n";
        }

        if ($this->viewsCreated) {
            $report .= "- $this->smallPluralName blades : Created successfully.\n";
        } else {
            $report .= "- $this->smallPluralName blades : already exist.\n";
        }

        if ($this->routesCreated) {
            $report .= "- $this->smallPluralName routes : Created successfully.\n";
        } else {
            $report .= "- $this->smallPluralName routes : already exist.\n";
        }

        if ($this->translationsCreated) {
            $report .= "- $this->smallPluralName lang : Created successfully.\n";
        } else {
            $report .= "- $this->smallPluralName lang : already exist.\n";
        }

        return $report;
    }
    //------------------------------------------------------------------------------------------------------------------------------

    // helpers ////////////////////////////////////////////////////////////////
    protected function insertTranslation($content, $newTranslation)
    {
        // Find the 'add your translations here' comment
        $comment = '#new_comand_translations_here';
        $commentPosition = strpos($content, $comment);

        if ($commentPosition === false) {
            // If comment not found, return without changes
            return $content;
        }

        // Insert the new translation before the comment
        return substr_replace($content, $newTranslation . PHP_EOL, $commentPosition, 0);
    }

    protected function processTranslation($paths, $stubs)
    {
        foreach (['ar', 'en'] as $lang) {
            $content = $this->filesystem->get($paths[$lang]);
            $template = $this->filesystem->get($stubs[$lang]);

            $translatedContent = $this->replacePlaceholders($template, $lang);
            $content = $this->insertTranslation($content, $translatedContent);

            // Save the updated language file
            $this->filesystem->put($paths[$lang], $content);
        }
    }

    protected function replacePlaceholders($template, $lang)
    {
        $placeholders = [
            '{{capitalName}}'       => $this->capitalName,
            '{{smallName}}'         => $this->smallName,
            '{{smallPluralName}}'   => $this->smallPluralName,
            '{{capitalPluralName}}' => ucfirst($this->capitalPluralName),
        ];

        if ($lang === 'ar') {
            $placeholders['{{singularAr}}'] = $this->singularAr;
            $placeholders['{{pluralAr}}'] = $this->pluralAr;
        } else {
            $placeholders['{{capitalPluralName}}'] = ucfirst($this->smallPluralName);
        }

        return str_replace(array_keys($placeholders), array_values($placeholders), $template);
    }

    protected function setNames($name, $singularAr, $pluralAr)
    {
        $this->capitalName                   = ucfirst($name);
        $this->smallName                     = strtolower($name);
        $this->smallPluralName               = $this->pluralizeEnd($this->smallName);
        $this->smallPluralNameWithUnderscore = $this->pluralizeEnd(Str::snake($this->capitalName));
        $this->capitalPluralName             = ucfirst($this->smallPluralName);
        $this->pluralCamelCaseName           = $this->convertToPluralCamelCase($name);
        $this->singularAr                    = str_replace('_', ' ', $singularAr);
        $this->pluralAr                      = str_replace('_', ' ', $pluralAr);
    }

    protected function convertToPluralCamelCase($name)
    {
        $words          = explode('_', strtolower(Str::snake($name)));

        $firstWord      = strtolower(array_shift($words));

        $camelCaseWords = array_map('ucfirst', $words);

        array_unshift($camelCaseWords, $firstWord);

        $pluralized = $this->pluralizeEnd($camelCaseWords);

        return $pluralized;
    }

    protected function toSnakeCase($input)
    {
        // Replace uppercase letters with _ followed by the lowercase letter
        $snakeCase = preg_replace('/([a-z])([A-Z])/', '$1_$2', $input);

        $pluralSnakeCase = $this->pluralizeEnd([$snakeCase]);

        // Ensure everything is lowercase
        return strtolower($pluralSnakeCase);
    }

    protected function pluralizeEnd($input)
    {
        if (!is_array($input)) {
            $input = [$input];
        }

        $baseName = implode('', $input);

        if (substr($baseName, -1) === 'y') {
            return substr($baseName, 0, -1) . 'ies';
        }

        return $baseName . 's';
    }
}
