<?php declare(strict_types=1);

namespace BOF\Repositories;

use Doctrine\DBAL\Driver\Connection;

class ReportYearlyRepository implements ReportYearlyRepositoryInterface
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param int $year
     * @return array
     */
    public function getTotalViewsPerProfile(int $year): array
    {
        $sql = '
            SELECT
                p.profile_id,
                p.profile_name,
                v.date,
                SUM(v.views) AS nr_of_views
            FROM profiles p
                LEFT JOIN views v ON v.profile_id = p.profile_id
            WHERE YEAR(v.date) = :year OR v.date IS NULL
            GROUP BY p.profile_id, MONTH(v.date)
            ORDER BY p.profile_name
        ';

        $statement = $this->connection->prepare($sql);
        $statement->bindValue('year', $year);
        $statement->execute();

        $profiles = $statement->fetchAll();

        return $profiles ?? [];
    }
}
