<?php
namespace BOF\Model;

use Doctrine\DBAL\Driver\Connection;

class ViewsRetriever
{
    public function retrieveViewsPerProfile($databaseContainer, $year)
    {
        $views = $databaseContainer->fetchAll($this->queryViewsPerProfilePerYear(), [':year' => $year]);

        return $views;
    }

    public function retrieveYears($databaseContainer)
    {
        $data = $databaseContainer->fetchAll($this->queryViewsYears());

        return $data;
    }

    private function queryViewsPerProfilePerYear()
    {
      return '
        SELECT profiles.profile_name as profile, SUM(views.views) as sum_views, 
          EXTRACT(MONTH from views.date) as month,
          EXTRACT(YEAR from views.date) as year
        FROM profiles
        INNER JOIN views ON profiles.profile_id = views.profile_id
        WHERE EXTRACT(YEAR FROM views.date) = :year
        GROUP BY EXTRACT(YEAR_MONTH FROM views.date), views.profile_id
        ORDER BY profiles.profile_name, month;
      ';
    }

    private function queryViewsYears()
    {
      return '
        SELECT EXTRACT(YEAR from date)
        FROM views
        GROUP BY EXTRACT(YEAR from date);
      ';
    }
}