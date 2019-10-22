<?php declare(strict_types=1);

namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;

class ReportYearlyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report')
            ->addArgument('year', InputArgument::REQUIRED, 'For which year do you want a report?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input,$output);
        $reportService = $this->getContainer()->get('app.services.report_service');

        $selectedYear = (int) $input->getArgument('year');

        $headers = $reportService->getReportHeaders($selectedYear);
        $report = $reportService->generateYearlyReportData($selectedYear);

        $content = count($report) > 0 ? $report : [['No views for requested year!']];
        $io->table($headers, $content);
    }
}
