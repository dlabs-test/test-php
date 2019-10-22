<?php declare(strict_types=1);

namespace BOF\Repositories\Report;

use Doctrine\DBAL\Driver\Connection;

class ReportRepository implements ReportRepositoryInterface
{
    private $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function getYearlyProfileViews(int $year): array
    {
        $sql = '
            SELECT
                p.profile_id,
                p.profile_name,
                MONTH(v.date) AS month,
                SUM(v.views) AS views
            FROM profiles p
                LEFT JOIN views v ON v.profile_id = p.profile_id
            WHERE YEAR(v.date) = :year OR v.date IS NULL
            GROUP BY p.profile_id, MONTH(v.date)
            ORDER BY p.profile_name, month DESC
        ';

        $statement = $this->db->prepare($sql);

        $statement->bindValue('year', $year);
        $statement->execute();

        $data = $statement->fetchAll();
        return $data ?? [];
    }
}