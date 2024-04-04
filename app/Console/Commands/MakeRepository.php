<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $repositoryClassName = $name . 'Repository';
        $repositoryDirectory = app_path('Repositories'); // Specify the directory path

        if (!file_exists($repositoryDirectory)) {
            mkdir($repositoryDirectory, 0755, true); // Recursive directory creation
        }
        $repositoryFile = app_path("Repositories/{$repositoryClassName}.php");

        $stubsDirectory = __DIR__ . '/stubs';
        $stub = file_get_contents("{$stubsDirectory}/repository.stub");
        $stub = str_replace('DummyClass', $repositoryClassName, $stub);

        if (!file_exists($repositoryFile)) {
            file_put_contents($repositoryFile, $stub);
            $this->info('Repository created successfully!');
        } else {
            $this->error('Repository already exists!');
        }
    }
}
