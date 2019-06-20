<?php declare(strict_types=1);

namespace BOF\Services;

use BOF\Repositories\ReportYearlyRepositoryInterface;

class ReportYearlyService implements ReportYearlyServiceInterface
{
    /**
     * @var ReportYearlyRepositoryInterface
     */
    private $reportYearlyRepository;

    public function __construct(ReportYearlyRepositoryInterface $reportYearlyRepository)
    {
        $this->reportYearlyRepository = $reportYearlyRepository;
    }

    /**
     * @param int $year
     * @return array
     */
    public function getYearlyReports(int $year): array
    {
        return $this->reportYearlyRepository->getTotalViewsPerProfile($year);
    }

    /**
     * @param int $year
     * @return array
     */
    public function formatHeaders(int $year): array
    {
        if (!isset($year)) {
            throw new \InvalidArgumentException("Year parameter not set!");
        }

        $headers = ["Profile $year"];

        for ($i = 1; $i <= 12; $i++) {
            $month = date('M', mktime(0, 0, 0, $i, 1));
            $headers[] = $month;
        }

        return [$headers];
    }

    /**
     * @param array $profiles
     * @return array
     */
    public function formatContent(array $profiles): array
    {
        $content = [];
        $profileId = null;
        $columns = null;

        if (!isset($profiles)) {
            throw new \InvalidArgumentException('No profiles data to format!');
        }

        array_map(function ($item) use (&$profileId, &$content, &$columns) {
            if (!isset($profileId) || $profileId !== $item['profile_id']) {
                if (isset($profileId)) {
                    $content[] = $columns;
                }

                $profileId = $item['profile_id'];
                $columns = $this->setDefaultColumnValues();
            }

            if (isset($item['profile_name'])) {
                $columns[0] = $item['profile_name'];
            }

            if (isset($item['date'])) {
                $columnIdx = date('n', strtotime($item['date']));
                if (isset($item['nr_of_views']) && is_numeric($item['nr_of_views']) && (float)$item['nr_of_views'] >= 0) {
                    $numberOfViews = (float)$item['nr_of_views'];
                    $columns[$columnIdx] = number_format($numberOfViews, 0, '.', ',');
                }
            }
        }, $profiles);

        return $content;
    }


    /**
     * @return array
     */
    protected function setDefaultColumnValues()
    {
        $defaultColumns = [];
        for ($i = 0; $i <= 12; $i++) {
            $defaultColumns[$i] = 'n/a';
        }

        return $defaultColumns;
    }

}
