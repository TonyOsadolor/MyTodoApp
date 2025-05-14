<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Pluralizer;

class MakeServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';

    /**
     * Filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * Create a new command instance.
     *
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    /**
     * Return the singular, capitalized name.
     *
     * @param string $name
     * @return string
     */
    public function getSingularClassName($name)
    {
        return ucwords(Pluralizer::singular($name));
    }

    /**
     * Generate the service class content.
     *
     * @param string $namespace
     * @param string $className
     * @return string
     */
    public function getClassContent($namespace, $className)
    {
        return <<<PHP
<?php

namespace {$namespace};

class {$className}
{
    //
}
PHP;
    }

    /**
     * Get the full path of the generated class.
     *
     * @return string
     */
    public function getSourceFilePath()
    {
        return base_path('app/Services') . '/' . $this->getSingularClassName($this->argument('name')) . '.php';
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param string $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $namespace = 'App\\Services';
        $className = $this->getSingularClassName($this->argument('name'));
        $path = $this->getSourceFilePath();

        $this->makeDirectory(dirname($path));

        if (!$this->files->exists($path)) {
            $contents = $this->getClassContent($namespace, $className);
            $this->files->put($path, $contents);
            $this->info("File: {$path} created successfully.");
            return Command::SUCCESS;
        } else {
            $this->warn("File: {$path} already exists.");
            return Command::FAILURE;
        }
    }
}
