<?php declare(strict_types=1);

namespace BOF\Repositories\Report;

interface ReportRepositoryInterface
{
    public function getYearlyProfileViews(int $year): array;
}