<?php
namespace BOF\Model;

class ViewsReport
{
    const MONTHS = [
        1 => 'Jan',
        2 => 'Feb',
        3 => 'Mar',
        4 => 'Apr',
        5 => 'May',
        6 => 'Jun',
        7 => 'Jul',
        8 => 'Aug',
        9 => 'Sep',
        10 => 'Oct',
        11 => 'Nov',
        12 => 'Dec'
    ];

    private $year;

    private $title;

    public function __construct($year, $title = 'Profile')
    {
        $this->title = $title;
        $this->year = $year;
    }

    public function structureHead()
    {
        return [$this->title.' '.$this->year] + self::MONTHS;
    }

    public function structureBody($views)
    {
        $rows = [];
        $profile = '';
        foreach($views as $view) {
            if($view['profile'] !== $profile) {
                if(!empty($row)) {
                    $rows[] = $row;
                }
                
                $row = $this->emptyRow();
                $profile = $view['profile'];
            }

            $row[$view['month']] = $view['sum_views'];
            $row[0] = $profile;
        }

        $rows[] = $row;

        return $rows;
    }

    private function emptyRow()
    {
        return [
            0 => '',
            1 => 'n/a',
            2 => 'n/a',
            3 => 'n/a',
            4 => 'n/a',
            5 => 'n/a',
            6 => 'n/a',
            7 => 'n/a',
            8 => 'n/a',
            9 => 'n/a',
            10 => 'n/a',
            11 => 'n/a',
            12 => 'n/a'
        ];
    }
}