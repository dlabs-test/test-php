<?php

namespace BOF\Command;

use BOF\Services\ReportYearlyServiceInterface;
use Exception;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ReportYearlyCommand extends ContainerAwareCommand
{
    /**
     * @var ReportYearlyServiceInterface
     */
    private $reportYearlyService;

    public function __construct(ReportYearlyServiceInterface $reportYearlyService, $name = null)
    {
        $this->reportYearlyService = $reportYearlyService;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setName('report:profiles:yearly')
            ->addArgument('year', InputArgument::OPTIONAL, 'Year filter')
            ->setDescription('Page views report');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $year = $input->getArgument('year');
            $year = isset($year) ? (int)$year : (int)date('Y');

            if (!$this->isValidYear($year)) {
                $io->writeln('Invalid year supplied!');
                return;
            }

            $profiles = $this->reportYearlyService->getYearlyReports($year);

            $headers = $this->reportYearlyService->formatHeaders($year);

            $content = $this->reportYearlyService->formatContent($profiles);
        } catch (Exception $ex) {
            //ToDo: logger implementation
            throw $ex;
        }

        $content = is_array($content) && count($content) > 0 ? $content : [['No data available for specified year!']];

        $io->table($headers, $content);
    }

    private function isValidYear($year)
    {
        return !(strtotime($year) === false);
    }
}
