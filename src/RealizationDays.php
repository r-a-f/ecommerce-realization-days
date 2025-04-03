<?php

declare(strict_types=1);

namespace RealizationDays;

class RealizationDays
{
    private \DateTimeImmutable $now;
    private ?\DateTimeImmutable $avaibleFromDate = null;
    private int $realizationDays = 0;
    private int $goldenHour = 0;
    private int $hourNow;

    /** @var int[] every week days-off (6=Sat, 7=Sun) */
    private array $dateWeekOff = [6, 7];

    /** @var string[] dates day off (yyyy-mm-dd) */
    private array $dateDaysOff = [];

    public function __construct(null|\DateTimeImmutable|string $now = null)
    {
        $this->now = $now instanceof \DateTimeImmutable
            ? $now
            : new \DateTimeImmutable($now ?: 'now');

        $this->hourNow = (int) $this->now->format('G');
        $this->now = $this->now->setTime(0, 0, 0);
    }

    public function setAvaibleFromDate(null|\DateTimeImmutable|string $avaibleFromDate): void
    {
        if ($avaibleFromDate instanceof \DateTimeImmutable) {
            $this->avaibleFromDate = $avaibleFromDate->setTime(0, 0, 0);
        } elseif (is_string($avaibleFromDate)) {
            $this->avaibleFromDate = (new \DateTimeImmutable($avaibleFromDate))->setTime(0, 0, 0);
        } else {
            $this->avaibleFromDate = null;
        }
    }

    /**
     * @param int[] $dateWeekOff
     */
    public function setDateWeekOff(array $dateWeekOff): void
    {
        $filtered = array_map('intval', $dateWeekOff);

        foreach ($filtered as $day) {
            if ($day < 1 || $day > 7) {
                throw new \InvalidArgumentException('Each week days-off must be an integer between 1 and 7.');
            }
        }
        $this->dateWeekOff = $filtered;
    }

    /**
     * @param string[] $dateDaysOff
     */
    public function setDateDaysOff(array $dateDaysOff): void
    {
        foreach ($dateDaysOff as $date) {
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                throw new \InvalidArgumentException("Invalid day-off dates date format: {$date}");
            }
        }
        $this->dateDaysOff = $dateDaysOff;
    }

    public function setRealizationDays(int $realizationDays): void
    {
        if ($realizationDays < 0) {
            throw new \InvalidArgumentException('RealizationDays must be > 0');
        }

        $this->realizationDays = $realizationDays;
    }

    public function setGoldenHour(int $goldenHour): void
    {
        if ($goldenHour < 0 || $goldenHour > 23) {
            throw new \InvalidArgumentException('GoldenHour must be between 0 and 23');
        }

        $this->goldenHour = $goldenHour;
    }

    public function calc(): RealizationViewContext
    {
        $afterGoldenHour = $this->hourNow >= $this->goldenHour || $this->isNonWorkingDay($this->now);
        $realizationDate = $this->avaibleFromDate ?? $this->now;

        if (!$this->avaibleFromDate && $afterGoldenHour) {
            $realizationDate = $this->addDays($realizationDate, 1);
            $this->realizationDays = max(0, $this->realizationDays - 1);
        }

        $realizationDate = $this->addDays($realizationDate, $this->realizationDays);

        while ($this->isNonWorkingDay($realizationDate)) {
            $realizationDate = $this->addDays($realizationDate, 1);
        }

        $daysDiff = $realizationDate->diff($this->now)->days ?: 0;

        return new RealizationViewContext(
            $realizationDate,
            $daysDiff,
            $afterGoldenHour,
            $this->goldenHour,
        );
    }

    private function isNonWorkingDay(\DateTimeImmutable $date): bool
    {
        return in_array((int) $date->format('N'), $this->dateWeekOff, true)
               || in_array($date->format('Y-m-d'), $this->dateDaysOff, true);
    }

    private function addDays(\DateTimeImmutable $date, int $days): \DateTimeImmutable
    {
        return $date->add(new \DateInterval("P{$days}D"));
    }
}
