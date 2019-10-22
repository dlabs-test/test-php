<?php declare(strict_types=1);

namespace BOF\Services\Report;

interface ReportServiceInterface
{
    public function generateYearlyReportData(int $year): array;

    public function getReportHeaders(int $year): array;
}