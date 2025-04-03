<?php

declare(strict_types=1);

namespace RealizationDays;

class RealizationDaysFormatter
{
    private const WEEK_DURATION = 7;

    /** @var string[] */
    private array $templates = [];

    /** @var string[] */
    private array $weekDays = [];

    private string $hourFormat = 'H:i';
    private bool $showOnlyDates = false;

    public function __construct(
        private readonly RealizationViewContext $context,
    ) {}

    /**
     * @param string[] $weekDays
     * @param string[] $templates
     */
    public function setTranslation(array $weekDays, array $templates): void
    {
        $this->weekDays = $weekDays;
        $this->templates = $templates;
    }

    public function setShowOnlyDates(bool $showOnlyDates): void
    {
        $this->showOnlyDates = $showOnlyDates;
    }

    public function setHourFormat(string $hourFormat): void
    {
        $this->hourFormat = $hourFormat;
    }

    public function format(string $dateFormat = 'Y-m-d'): string
    {
        $dayOfWeek = (int) $this->context->date->format('N');
        $template = $this->resolveTemplate($this->context->afterGoldenHour, $this->context->realizationDays);

        $label = $this->context->date->format($dateFormat);

        if ($this->context->realizationDays <= self::WEEK_DURATION && !$this->showOnlyDates) {
            $label = $this->weekDays[$dayOfWeek - 1] ?? $label;
        }

        return $this->parseTemplate($template, $label);
    }

    private function resolveTemplate(bool $afterGoldenHour, int $realizationDays): string
    {
        $templateKey = match (true) {
            $afterGoldenHour && $realizationDays <= 0 => 'AFTER_TODAY',
            $afterGoldenHour && 1 === $realizationDays => 'AFTER_TOMORROW',
            $afterGoldenHour && $realizationDays > 1 => 'AFTER_NEXT_DAYS',
            !$afterGoldenHour && $realizationDays <= 0 => 'BEFORE_TODAY',
            !$afterGoldenHour && 1 === $realizationDays => 'BEFORE_TOMORROW',
            default => 'BEFORE_NEXT_DAYS',
        };

        if (!isset($this->templates[$templateKey])) {
            throw new RealizationDaysException("Text template not found: {$templateKey}");
        }

        return $this->templates[$templateKey];
    }

    private function parseTemplate(string $template, string $label): string
    {
        return str_replace(
            ['%DATE_OR_WEEKDAY%', '%GOLDEN_HOUR%'],
            [$label, $this->formatHour($this->context->goldenHour)],
            $template
        );
    }

    private function formatHour(int $hour): string
    {
        return (new \DateTimeImmutable())
            ->setTime($hour, 0)
            ->format($this->hourFormat)
        ;
    }
}
