<?php declare(strict_types=1);

namespace BOF\Services\Report;

use BOF\Repositories\Report\ReportRepository;

class ReportService implements ReportServiceInterface
{
    private $reportRepository;

    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    public function generateYearlyReportData(int $year): array
    {
        $data = $this->reportRepository->getYearlyProfileViews($year);
        $defaultColumns = $this->getDefaultColumns();

        $profiles = array_reduce($data, function($profiles, $views) use ($defaultColumns) {

            if(!isset($profiles[$views['profile_id']])) {
                $profiles[$views['profile_id']] = $defaultColumns;
            }

            $profiles[$views['profile_id']][0] = $views['profile_name'];
            $profiles[$views['profile_id']][(int) $views['month']] = number_format((int)$views['views'], 0, '.', ',');

            return $profiles;

        }, []);

        ksort($profiles);

        return $profiles ?? [];
    }

    public function getReportHeaders(int $year): array
    {
        return array_reduce(range(1,12), function($headers, $month) {
            $headers[] = date('M',mktime(0,0,0,$month,10));
            return $headers;
        }, [$year . ' views report']);
    }

    private function getDefaultColumns()
    {
        return array_reduce(range(0,12), function($values, $count) {
            $values[$count] = "n/a";
            return $values;
        }, []);
    }
}