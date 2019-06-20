<?php declare(strict_types=1);

namespace BOF\Repositories;

interface ReportYearlyRepositoryInterface
{
    public function getTotalViewsPerProfile(int $year): array;
}
