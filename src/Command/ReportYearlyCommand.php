<?php

namespace BOF\Command;

use Symfony\Component\Console\Input\InputInterface as InputInterfaceAlias;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ReportYearlyCommand extends ContainerAwareCommand
{
    /**
     * Data array for output. Contains monthly views SUM, based on year and 'profile_id'
     *
     * @var array
     */
    private $_formattedHistory = [];

    /**
     * All data entries of view table
     *
     * @var array
     */
    private $_reports;

    /**
     * All profiles
     *
     * @var array
     */
    private $_profiles;

    /**
     *
     * @var array
     */
    private $_yearsScope = [];

    /**
     * Months in a year formatted as 'M'
     *
     * @var array
     */
    private $_monthsInYear;

    /**
     * Instance of DB ServiceProvider
     *
     * @var
     */
    private $_db;

    /**
     * Instance of ConsoleOutputProvider
     *
     * @var
     */
    private $_io;

    /**
     * Array of formatted data, to be outputted to console
     *
     * @var array
     */
    private $_output = [];

    /**
     * ReportYearlyCommand constructor
     */
    protected function configure(): void
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report');
    }

    protected function execute(InputInterfaceAlias $input, OutputInterface $output)
    {
        // make instance of Output Interface
        $this->_io = new SymfonyStyle($input, $output);
        // make instance of DB client
        $this->_db = $this->getContainer()->get('database_connection');
        // Get months in a year
        $this->_monthsInYear = $this->getMonthsInYear();
        // get profiles
        $this->_profiles = $this->getProfiles();
        // get view stats entries
        $this->_reports = $this->getViewStats();
        // SUM based on month
        $this->sumViews();
        // validate output stats: define null as 'n/a' or format number as grouped thousands
        $this->checkNullValuesAndFormat();
        // write reports to output
        $this->generateReport();
    }

    /**
     * Write reports to output
     */
    private function generateReport(): void
    {
        foreach ($this->_yearsScope as $yearSingle) {
            foreach ($this->_formattedHistory as $userKey => $user) {

                // push Profile name to beginning of the row
                array_unshift($this->_formattedHistory[$userKey][$yearSingle], $this->_profiles[$userKey - 1]['profile_name']);
                // add user yearly stats to specific year
                $this->_output[$yearSingle][] = $this->_formattedHistory[$userKey][$yearSingle];
            }
            // define yearly header
            $header = ['Profile         '.$yearSingle, 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Non', 'Dec'];

            $this->_io->table($header,$this->_output[$yearSingle]);
        }
    }

    /**
     * SUM all views by month, year and profile
     */
    private function sumViews(): void
    {
        foreach ($this->_reports as $report) {
            $userId = $report['profile_id'];
            $unixTime = strtotime($report['date']);
            $year = date('Y', $unixTime);
            $month = date('M', $unixTime);
            echo $month . ' ' . $year . ' ' . $userId;

            // check if year exists, otherwise set year array
            if (!in_array($year, $this->_yearsScope, false)) {
                $this->_yearsScope[] = $year;
            }

            // check if count already exists and SUM, otherwise set it as initial value
            if (isset($this->_formattedHistory[$userId][$year][$month])) {
                $this->_formattedHistory[$userId][$year][$month] += $report['views'];
            } else {
                $this->_formattedHistory[$userId][$year][$month] = $report['views'];
            }
        }
    }

    /**
     * Validate and set default values if no views for specific month are present
     */
    private function checkNullValuesAndFormat(): void
    {
        // loop users
        foreach ($this->_formattedHistory as $userKey => $userValue) {
            // loop years
            foreach ($userValue as $yearKey => $yearValue) {
                // loop months
                for ($i = 1; $i <= 12; $i++) {
                    $currentMonthName = $this->_monthsInYear[$i];
                    // set 'n/a' value if there are no stats for this month
                    if (!isset($this->_formattedHistory[$userKey][$yearKey][$currentMonthName])) {
                        $this->_formattedHistory[$userKey][$yearKey][$currentMonthName] = 'n/a';
                    } else {
                        // format views count as grouped thousands
                        $this->_formattedHistory[$userKey][$yearKey][$currentMonthName] = number_format($this->_formattedHistory[$userKey][$yearKey][$currentMonthName]);
                    }
                }
            }
        }
    }

    /**
     * Returns all view stats entries
     *
     * @return array
     */
    private function getViewStats(): array
    {
        return $this->_db->query('SELECT * FROM views;')->fetchAll();
    }

    /**
     * Get all Profiles
     *
     * @return array
     */
    private function getProfiles(): array
    {
        return $this->_db->query('SELECT profile_id, profile_name FROM profiles')->fetchAll();
    }

    /**
     * Returns array of month names
     *
     * @return array
     */
    private function getMonthsInYear(): array
    {
        return array_reduce(range(1, 12), static function ($result, $month) {
            $result[$month] = date('M', mktime(0, 0, 0, $month, 10));

            return $result;
        });
    }
}
