<?php namespace Netfizz\Generators\Commands;

use Way\Generators\Generators\MigrationGenerator;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Way\Generators\Commands\MigrationGeneratorCommand as WayMigrationGeneratorCommand;



class MigrationGeneratorCommand extends WayMigrationGeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'generate:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new migration.';

    /**
     * Model generator instance.
     *
     * @var Way\Generators\Generators\MigrationGenerator
     */
    protected $generator;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $name = $this->argument('name');
        //$path = $this->getMigrationPath();
        $path = $this->getPath();

        //var_dump($this->getMigrationPath(), $this->getPath());
        //die;

        $fields = $this->option('fields');

        $created = $this->generator
            ->parse($name, $fields)
            ->make($path, null);

        $this->call('dump-autoload');

        $this->printResult($created, $path);
    }

    /**
     * Get the path to the migration directory.
     *
     * @return string
     */
    protected function getMigrationPath()
    {
        $path = $this->input->getOption('path');

        // First, we will check to see if a path option has been defined. If it has
        // we will use the path relative to the root of this installation folder
        // so that migrations may be run for any path within the applications.
        if ( ! is_null($path))
        {
            return $this->laravel['path.base'].'/'.$path;
        }

        /*
        $package = $this->input->getOption('package');

        // If the package is in the list of migration paths we received we will put
        // the migrations in that path. Otherwise, we will assume the package is
        // is in the package directories and will place them in that location.
        if ( ! is_null($package))
        {
            return $this->packagePath.'/'.$package.'/src/migrations';
        }
        */

        $bench = $this->input->getOption('bench');

        // Finally we will check for the workbench option, which is a shortcut into
        // specifying the full path for a "workbench" project. Workbenches allow
        // developers to develop packages along side a "standard" app install.
        if ( ! is_null($bench))
        {
            $path = "/workbench/{$bench}/src/migrations";

            return $this->laravel['path.base'].$path;
        }

        return $this->laravel['path'].'/database/migrations';
    }

    /**
     * Get the path to the file that should be generated.
     *
     * @return string
     */
    protected function getPath()
    {
        return $this->getMigrationPath() . '/' . ucwords($this->argument('name')) . '.php';
    }


    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('bench', null, InputOption::VALUE_OPTIONAL, 'The name of the workbench to migrate.', null),
            array('path', null, InputOption::VALUE_OPTIONAL, 'The path to the migrations folder', null),
            array('fields', null, InputOption::VALUE_OPTIONAL, 'Table fields', null)
        );
    }


}
