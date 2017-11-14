<?php
namespace BOF\Command;
use \Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class BaseCommand
 * @package BOF\Command
 */
abstract class ContainerAwareCommand extends Command
{
    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->getApplication()->getContainer();
    }
}