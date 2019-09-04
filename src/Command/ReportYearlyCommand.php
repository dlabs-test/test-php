<?php
namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;

use Symfony\Component\Console\Helper\Table;



class ReportYearlyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report')
            ->addArgument(
                'year',
                InputArgument::OPTIONAL,
                'Of which year do you want to see the user views?'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $year = $input->getArgument('year');
        
        if ($year == null){
            
            // Render the results of all the years
            $availableYears = $this->getAvailableYears();
            foreach ($availableYears as $key => $result) {
                foreach ($result as $key => $availableYear) {
                    $this->renderYear($availableYear, $output);
                }
            }
        }
        else{
            // Render the results of the specified year
            if ($this->checkIfYearHasResults($year)){
                $this->renderYear($year, $output);
            }
            else{
                $output->writeln([
                    '<info>The year specified has no results</>',
                ]);
            }
        }
    }
    
    /***************************************************************************/

    /**
     * Render in the console the table with the specified year
     *
     *  @param int $year
     *  @param OutputInterface $output
     *  @return array $ret
     */
     
    protected function renderYear($year, $output){
        
        /** @var $db Connection */
        $db = $this->getContainer()->get('database_connection');

        $profiles = $db->query("SELECT 
                                    p.profile_name, 
                                    Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, December as 'Dec'
                                FROM profiles p

                                LEFT JOIN (SELECT 
                                                profile_id,
                                                COALESCE(SUM(CASE WHEN MONTH(date) = 1 THEN views END), 'n/a') As 'Jan',
                                                COALESCE(SUM(CASE WHEN MONTH(date) = 2 THEN views END), 'n/a') As 'Feb',
                                                COALESCE(SUM(CASE WHEN MONTH(date) = 3 THEN views END), 'n/a') As 'Mar',
                                                COALESCE(SUM(CASE WHEN MONTH(date) = 4 THEN views END), 'n/a') As 'Apr',
                                                COALESCE(SUM(CASE WHEN MONTH(date) = 5 THEN views END), 'n/a') As 'May',
                                                COALESCE(SUM(CASE WHEN MONTH(date) = 6 THEN views END), 'n/a') As 'Jun',
                                                COALESCE(SUM(CASE WHEN MONTH(date) = 7 THEN views END), 'n/a') As 'Jul',
                                                COALESCE(SUM(CASE WHEN MONTH(date) = 8 THEN views END), 'n/a') As 'Aug',
                                                COALESCE(SUM(CASE WHEN MONTH(date) = 9 THEN views END), 'n/a') As 'Sep',
                                                COALESCE(SUM(CASE WHEN MONTH(date) = 10 THEN views END), 'n/a') As 'Oct',
                                                COALESCE(SUM(CASE WHEN MONTH(date) = 11 THEN views END), 'n/a') As 'Nov',
                                                COALESCE(SUM(CASE WHEN MONTH(date) = 12 THEN views END), 'n/a') As 'December'
                                            FROM views 
                                            WHERE YEAR(date) = '$year'
                                            GROUP BY profile_id) v
                                ON (p.profile_id = v.profile_id)
                                ORDER BY p.profile_name
                        ")->fetchAll();
        
        if ($profiles != null){
            // Show data in a table - headers, data
            $table = new Table($output);
            $table
                ->setHeaders(['Profile        '.$year, 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov','Dec'])
                ->setRows($profiles)
            ;
            $table->render();
        }
        else{
            $output->writeln([
                '<info>The profile table is empty</>',
            ]);
        }
    }
    

    /***************************************************************************/

    /**
     * Return the available years in the views table
     *
     *  @return array $ret
     */
 
    protected function getAvailableYears(){
        /** @var $db Connection */
        $db = $this->getContainer()->get('database_connection');
        
        $ret = $db->query("SELECT DISTINCT YEAR(date) as year FROM views ")->fetchAll();
                        
        return $ret;
    }
    
    /***************************************************************************/

    /**
     * Check if the specified year return any result
     *
     *  @param int $year
     *  @return array $ret
     */
 
    protected function checkIfYearHasResults($year){
        /** @var $db Connection */
        $db = $this->getContainer()->get('database_connection');
        
        $result = $db->query("SELECT * FROM views WHERE YEAR(date) = $year")->fetchAll();
        
        if (!empty($result)) {
            return true;
        }
        else{
            return false;
        }
    }
    
    
}
