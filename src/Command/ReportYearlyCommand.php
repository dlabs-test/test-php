<?php
namespace BOF\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use BOF\Utils\Statistic;

class ReportYearlyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $db Connection */
        $io = new SymfonyStyle($input, $output);

        // Insist that the user provides a valid year
        $i = 0;
        do {
            if ($i > 0) {
                $io->caution('The provided year is not a valid 4 digit integer. Please try again.');
            }
            $year = intval($io->ask('For what year do you want the report?', date('Y')));
            $i++;
        } while (strlen($year) != 4);

        // Get views for the provided year
        $connection = $this->getContainer()->get('database_connection');
        $statistics = new Statistic($connection);
        $views = $statistics->yearlyViews($year);

        // Show data in a table - headers, data
        $headers = ['Profile ' . $year,];

        for ($i = 1; $i <= 12; $i++) {
            array_push($headers, date('M', mktime(0, 0, 0, $i, 10)));
        }

        $io->table($headers, $views);
    }
}
