<?php

declare(strict_types=1);

namespace IkonizerCore\Console;

use IkonizerCore\Base\Exception\BaseLogicException;
use IkonizerCore\Console\ConsoleCommandInterface;
use IkonizerCore\Console\Exception\ConsoleCommandFileMissingException;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\FactoryCommandLoader;
use IkonizerCore\Base\BaseApplication;
use IkonizerCore\Base\Exception\BaseInvalidArgumentException;
use IkonizerCore\Utility\Yaml;


class Console implements ConsoleInterface
{

    /* @var object */
    private $application;
    /* @var string console name */
    private string $consoleName = 'IkonizerCore Console Application';
    /* @var string console version */
    private string $consoleVersion = '1.0.0 (beta)';

    /**
     * Create the bootstrap console application. By initializing symfony application object
     * and executing the run() method
     *
     * @throws \Exception
     * @return void
     */
    public function create()
    {
        $this->application = new Application($this->consoleName, $this->consoleVersion);
        $this->application->setCommandLoader($this->registerCommands());
        return $this->application->run();
    }

    /**
     * Returns the core config commands.yml file which defines all the core command
     * @return array
     */
    private function getCommands(): array
    {
        return Yaml::file('commands');
    }

    private function getCommandsValue()
    {
        // $commandName = $commandClass = '';
        // (is_string($commandName) ? $commandName : throw new BaseInvalidArgumentException("Invalid {$commandName}. This should be a string"));
        // (class_exists($commandClass) ? $commandName : throw new BaseLogicException("{$commandClass} is missing from the commander directory."));

        foreach ($this->getCommands() as $name => $command) {
            if (isset($name)) {
                $commandName = $command['name'] ?? null;
                $commandClass = $command['class'] ?? null;
                (is_string($commandName) ? $commandName : throw new BaseInvalidArgumentException("Invalid {$commandName}. This should be a string"));
                (class_exists($commandClass) ? $commandClass : throw new BaseLogicException("{$commandClass} is missing from the commander directory."));
                return [
                    $commandName,
                    $commandClass,
                    $command['description'] ?? null,
                    $command['help'] ?? null,
                    $command['stubs'] ?? null,
                    $command['argument'] ?? null,
                    $command['options'] ?? null
                ];
            }
        }
    }

    /**
     * Register our core command with the symfony application add method
     */
    public function registerCommands()
    {
        list(
            $commandName,
            $commandClass,
            $description,
            $help,
            $stubs,
            $arguments,
            $options) = $this->getCommandsValue();
        try {
            return new FactoryCommandLoader([
                'magma:make' => function () { return new \IkonizerCore\Console\Commands\MakeCommand(); },
                'magma:make:migration' => function () { return new \IkonizerCore\Console\Commands\MakeMigration(); }
            ]);
        } catch(BaseLogicException) {

        }

    }


}
