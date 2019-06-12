<?php
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
            ->setDescription('Page views report');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $db Connection */
        $io = new SymfonyStyle($input, $output);

        $i = 0;

        // Insist that the user provides a valid year
        do {
            if ($i > 0) {
                $io->caution('The provided year is not a valid 4 digit intiger. Please try again.');
            }
            $year = intval($io->ask('For what year do you want the report?', date('Y')));
            $i++;
        } while (strlen($year) != 4);

        // Query the database for views in the provided year
        $db = $this->getContainer()->get('database_connection');
        $sql = '
            SELECT
                P.profile_name,
                FORMAT(SUM(CASE WHEN MONTH(V.date) = 1 THEN V.views ELSE 0 END), 0),
                FORMAT(SUM(CASE WHEN MONTH(V.date) = 2 THEN V.views ELSE 0 END), 0),
                FORMAT(SUM(CASE WHEN MONTH(V.date) = 3 THEN V.views ELSE 0 END), 0),
                FORMAT(SUM(CASE WHEN MONTH(V.date) = 4 THEN V.views ELSE 0 END), 0),
                FORMAT(SUM(CASE WHEN MONTH(V.date) = 5 THEN V.views ELSE 0 END), 0),
                FORMAT(SUM(CASE WHEN MONTH(V.date) = 6 THEN V.views ELSE 0 END), 0),
                FORMAT(SUM(CASE WHEN MONTH(V.date) = 7 THEN V.views ELSE 0 END), 0),
                FORMAT(SUM(CASE WHEN MONTH(V.date) = 8 THEN V.views ELSE 0 END), 0),
                FORMAT(SUM(CASE WHEN MONTH(V.date) = 9 THEN V.views ELSE 0 END), 0),
                FORMAT(SUM(CASE WHEN MONTH(V.date) = 10 THEN V.views ELSE 0 END), 0),
                FORMAT(SUM(CASE WHEN MONTH(V.date) = 11 THEN V.views ELSE 0 END), 0),
                FORMAT(SUM(CASE WHEN MONTH(V.date) = 12 THEN V.views ELSE 0 END), 0)
            FROM profiles P
            LEFT JOIN views V ON V.profile_id = P.profile_id AND YEAR(V.date) = ' . $year . '
            GROUP BY P.profile_id
            ORDER BY P.profile_name
            ';

        $profiles = $db->query($sql)->fetchAll();

        // Show data in a table - headers, data
        $headers = ['Profile ' . $year,];

        for ($i = 1; $i <= 12; $i++) {
            array_push($headers, date('M', mktime(0, 0, 0, $i, 10)));
        }

        $io->table($headers, $profiles);
    }
}
