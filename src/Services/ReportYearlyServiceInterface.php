<?php declare(strict_types=1);

namespace BOF\Services;

interface ReportYearlyServiceInterface
{
    public function getYearlyReports(int $year): array;

    public function formatHeaders(int $year): array;

    public function formatContent(array $profiles): array;
}
