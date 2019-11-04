<?php
namespace BOF\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use BOF\Service\ReportCreator;
use BOF\Model\ViewsRetriever;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\Table;
use \Exception;

class ReportYearlyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report')
            ->addArgument('year', InputArgument::OPTIONAL)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $year = $input->getArgument('year');
        if(null === $year) {
            $output->writeln('Please provide the year argument for which this report should be created. '.
                'Data is available for years:');
            $retriever = new ViewsRetriever();
            $years = $retriever->retrieveYears($this->getContainer()->get('database_connection'));
            foreach($years as $year) {
                $output->writeln($year);
            }
        } else if(!is_numeric($year)) {
            $output->writeln('<year> argument should be a positive number.');
        } else {
            try {
                $report = new ReportCreator($this->getContainer()->get('database_connection'));
                $report->createAndRenderReport($year, $output);
            } catch (Exception $e) {
                $output->writeln('Please make sure you have some data available for report creation and '.
                    'your input is valid.');
            }
        }
    }
}
