<?php

declare(strict_types=1);

namespace RealizationDays;

readonly class RealizationViewContext
{
    public function __construct(
        public \DateTimeImmutable $date,
        public int $realizationDays,
        public bool $afterGoldenHour,
        public int $goldenHour,
    ) {}
}
