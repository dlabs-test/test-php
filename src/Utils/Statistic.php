<?php
namespace BOF\Utils;

use Doctrine\DBAL\Connection;

class Statistic
{
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function yearlyViews(int $year = null)
    {
        if (!isset($year)) {
            $year = date('Y');
        }

        $sql = '
            SELECT
                P.profile_name,
                IFNULL(NULLIF(CAST(FORMAT(SUM(CASE WHEN MONTH(V.date) = 1 THEN V.views ELSE 0 END), 0)as char), "0"), "n/a"),
                IFNULL(NULLIF(CAST(FORMAT(SUM(CASE WHEN MONTH(V.date) = 2 THEN V.views ELSE 0 END), 0)as char), "0"), "n/a"),
                IFNULL(NULLIF(CAST(FORMAT(SUM(CASE WHEN MONTH(V.date) = 3 THEN V.views ELSE 0 END), 0)as char), "0"), "n/a"),
                IFNULL(NULLIF(CAST(FORMAT(SUM(CASE WHEN MONTH(V.date) = 4 THEN V.views ELSE 0 END), 0)as char), "0"), "n/a"),
                IFNULL(NULLIF(CAST(FORMAT(SUM(CASE WHEN MONTH(V.date) = 5 THEN V.views ELSE 0 END), 0)as char), "0"), "n/a"),
                IFNULL(NULLIF(CAST(FORMAT(SUM(CASE WHEN MONTH(V.date) = 6 THEN V.views ELSE 0 END), 0)as char), "0"), "n/a"),
                IFNULL(NULLIF(CAST(FORMAT(SUM(CASE WHEN MONTH(V.date) = 7 THEN V.views ELSE 0 END), 0)as char), "0"), "n/a"),
                IFNULL(NULLIF(CAST(FORMAT(SUM(CASE WHEN MONTH(V.date) = 8 THEN V.views ELSE 0 END), 0)as char), "0"), "n/a"),
                IFNULL(NULLIF(CAST(FORMAT(SUM(CASE WHEN MONTH(V.date) = 9 THEN V.views ELSE 0 END), 0)as char), "0"), "n/a"),
                IFNULL(NULLIF(CAST(FORMAT(SUM(CASE WHEN MONTH(V.date) = 10 THEN V.views ELSE 0 END), 0)as char), "0"), "n/a"),
                IFNULL(NULLIF(CAST(FORMAT(SUM(CASE WHEN MONTH(V.date) = 11 THEN V.views ELSE 0 END), 0)as char), "0"), "n/a"),
                IFNULL(NULLIF(CAST(FORMAT(SUM(CASE WHEN MONTH(V.date) = 12 THEN V.views ELSE 0 END), 0)as char), "0"), "n/a")
            FROM profiles P
            LEFT JOIN views V ON V.profile_id = P.profile_id AND YEAR(V.date) = ' . $year . '
            GROUP BY P.profile_id
            ORDER BY P.profile_name
            ';

        $profiles = $this->connection->query($sql)->fetchAll();

        return $profiles;
    }
}
