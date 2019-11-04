<?php
namespace BOF\Service;

use Doctrine\DBAL\Driver\Connection;
use BOF\Model\ViewsRetriever;
use BOF\Model\ViewsReport;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Exception\InvalidArgumentException;

class ReportCreator
{
    private $dbContainer;
    
    public function __construct($container)
    {
        $this->dbContainer = $container;
    }

    public function createAndRenderReport($year, $output)
    {
        $viewsRetriever = new ViewsRetriever();
        $views = $viewsRetriever->retrieveViewsPerProfile($this->dbContainer, $year);

        $report = new ViewsReport($year);
        try {
            $table = new Table($output);
            $table
                ->setHeaders($report->structureHead())
                ->setRows($report->structureBody($views));

            $table->render();
        } catch (InvalidArgumentException $e) {
            $output->writeln('Please make sure you have some data available for report creation.');
        }
    }
}