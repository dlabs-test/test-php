<?php
namespace BOF;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class Application
 * @package BOF
 */
class Application extends ConsoleApplication
{
    /**
     * @var ContainerBuilder
     */
    protected $container;

    /**
     * @param string $name    The name of the application
     * @param string $version The version of the application
     */
    public function __construct($name = 'app', $version = '1')
    {
        $this->container = new ContainerBuilder();
        $loader = new YamlFileLoader($this->container, new FileLocator(__DIR__.'/../app'));
        $loader->load('config.yml');
        $loader->load('services.yml');

        // Initiate app
        parent::__construct($name, $version);

        // Add configured commands
        foreach ($this->getConfiguredCommands() as $command) {
            $this->add($command);
        }
    }

    /**
     * @return Command[] An array of default Command instances
     */
    protected function getConfiguredCommands()
    {
        $commands = [];
        foreach ($this->container->findTaggedServiceIds('console.command') as $commandId => $command) {
            $commands[] = $this->container->get($commandId);
        }
        return $commands;
    }

    /**
     * @return ContainerBuilder
     */
    public function getContainer()
    {
        return $this->container;
    }
}