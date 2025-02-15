<?php

namespace iamchris\LivewireAutoCrud\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class MakeLivewireCrudCommand extends Command
{
    protected $signature = 'make:livewire-crud {model? : The name of the model}';
    protected $description = 'Generate Livewire CRUD scaffolding for a model.';

    public function handle()
    {
        // Check if Livewire is installed
        if (!class_exists(\Livewire\Livewire::class)) {
            $this->error("Livewire is not installed. Please install it first:");
            $this->info("composer require livewire/livewire");
            return;
        }

        // Prompt for the model name if not provided
        $modelName = $this->argument('model') ?? $this->ask('Enter the model name (e.g., Product)');

        // Validate the model name
        if (empty($modelName)) {
            $this->error("Model name cannot be empty.");
            return;
        }

        $modelClass = "App\\Models\\$modelName";

        // Check if the model exists
        if (!class_exists($modelClass)) {
            $this->error("Model $modelClass does not exist.");
            return;
        }

        // Get the model's fields
        $fields = $this->getModelFields($modelClass);

        // Generate Livewire components
        $this->generateLivewireComponents($modelName, $fields);

        $this->info("Livewire CRUD scaffolding for $modelName generated successfully!");
    }

    protected function getModelFields($modelClass)
    {
        $model = new $modelClass;
        $fillable = $model->getFillable();

        if (empty($fillable)) {
            $this->error("No fillable fields found in the model.");
            return [];
        }

        return $fillable;
    }

    protected function generateLivewireComponents($modelName, $fields)
    {
        $componentName = Str::kebab($modelName);

        // Generate Livewire components
        Artisan::call('make:livewire', ['name' => "$componentName.index"]);
        Artisan::call('make:livewire', ['name' => "$componentName.create"]);
        Artisan::call('make:livewire', ['name' => "$componentName.edit"]);
        Artisan::call('make:livewire', ['name' => "$componentName.show"]);

        // Update the generated components with CRUD logic
        $this->updateLivewireComponents($modelName, $fields);

        $this->info("Livewire components created for $modelName.");
    }

    protected function updateLivewireComponents($modelName, $fields)
    {
        $componentName = Str::kebab($modelName);
        $modelVariable = Str::camel($modelName);

        // Update Index Component
        $indexComponentPath = app_path("Livewire/$componentName/Index.php");
        $indexViewPath = resource_path("views/livewire/$componentName/index.blade.php");

        $indexComponentContent = $this->getIndexComponentStub($modelName, $fields);
        $indexViewContent = $this->getIndexViewStub($modelName, $fields);

        file_put_contents($indexComponentPath, $indexComponentContent);
        file_put_contents($indexViewPath, $indexViewContent);

        // Repeat for Create, Edit, and Show components...
    }

    protected function getIndexComponentStub($modelName, $fields)
    {
        $stub = file_get_contents(__DIR__ . '/../../../stubs/livewire-index.stub');
        $stub = str_replace('{{modelName}}', $modelName, $stub);
        $stub = str_replace('{{modelVariable}}', Str::camel($modelName), $stub);

        return $stub;
    }

    protected function getIndexViewStub($modelName, $fields)
    {
        $stub = file_get_contents(__DIR__ . '/../../../stubs/livewire-index-view.stub');
        $stub = str_replace('{{modelName}}', $modelName, $stub);
        $stub = str_replace('{{modelVariable}}', Str::camel($modelName), $stub);
        $stub = str_replace('{{fields}}', $this->getFieldsForTable($fields), $stub);

        return $stub;
    }

    protected function getFieldsForTable($fields)
    {
        $tableHeaders = [];
        $tableRows = [];

        foreach ($fields as $field) {
            $tableHeaders[] = "<th>$field</th>";
            $tableRows[] = "<td>{{ \${$modelVariable}->$field }}</td>";
        }

        return [
            'headers' => implode("\n", $tableHeaders),
            'rows' => implode("\n", $tableRows),
        ];
    }
}
