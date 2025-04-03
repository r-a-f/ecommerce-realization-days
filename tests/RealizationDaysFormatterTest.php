<?php

declare(strict_types=1);

namespace RealizationDays\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RealizationDays\RealizationDaysException;
use RealizationDays\RealizationDaysFormatter;
use RealizationDays\RealizationViewContext;

class RealizationDaysFormatterTest extends TestCase
{
    /**
     * @return array<int, array{
     *     0: RealizationViewContext,
     *     1: string
     * }>
     */
    public static function sampleDataFormatter(): array
    {
        return [
            [
                new RealizationViewContext(
                    new \DateTimeImmutable('2025-04-02'),
                    0,
                    false,
                    9,
                ),
                'Zamów dzisiaj do godz. 09:00 - wysyłka dzisiaj!',
            ],
            [
                new RealizationViewContext(
                    new \DateTimeImmutable('2025-04-02'),
                    2,
                    false,
                    10,
                ),
                'Zamów dzisiaj do godz. 10:00 - wysyłka do środy',
            ],
            [
                new RealizationViewContext(
                    new \DateTimeImmutable('2025-01-10'),
                    2,
                    true,
                    10,
                ),
                'Zamów dzisiaj - wysyłka do piątku',
            ],
            [
                new RealizationViewContext(
                    new \DateTimeImmutable('2025-01-10'),
                    1,
                    true,
                    10,
                ),
                'Zamów dzisiaj - wysyłka jutro!',
            ],
            [
                new RealizationViewContext(
                    new \DateTimeImmutable('2025-01-06'),
                    0,
                    true,
                    10,
                ),
                'Zamów dzisiaj - wysyłka dzisiaj!',
            ],
            [
                new RealizationViewContext(
                    new \DateTimeImmutable('2025-01-06'),
                    10,
                    true,
                    10,
                ),
                'Zamów dzisiaj - wysyłka do 06-01-2025',
            ],
        ];
    }

    #[DataProvider('sampleDataFormatter')]
    public function testFormatter(RealizationViewContext $context, string $result): void
    {
        $formatter = new RealizationDaysFormatter($context);

        $weekDays = [
            'poniedziałku',
            'wtorku',
            'środy',
            'czwartku',
            'piątku',
            'soboty',
            'niedzieli',
        ];

        $formatter->setTranslation(
            $weekDays,
            [
                'BEFORE_NEXT_DAYS' => 'Zamów dzisiaj do godz. %GOLDEN_HOUR% - wysyłka do %DATE_OR_WEEKDAY%',
                'BEFORE_TOMORROW' => 'Zamów dzisiaj do godz. %GOLDEN_HOUR% - wysyłka jutro!',
                'BEFORE_TODAY' => 'Zamów dzisiaj do godz. %GOLDEN_HOUR% - wysyłka dzisiaj!',
                'AFTER_NEXT_DAYS' => 'Zamów dzisiaj - wysyłka do %DATE_OR_WEEKDAY%',
                'AFTER_TOMORROW' => 'Zamów dzisiaj - wysyłka jutro!',
                'AFTER_TODAY' => 'Zamów dzisiaj - wysyłka dzisiaj!',
            ],
        );

        static::assertSame($result, $formatter->format('d-m-Y'));
    }

    public function testSetTranslation(): void
    {
        $formatter = $this->createFormatter();
        $weekDays = ['Pn', 'Wt', 'Śr'];
        $templates = ['FOO' => 'Bar'];

        $formatter->setTranslation($weekDays, $templates);

        $this->assertSame($weekDays, $this->getProperty($formatter, 'weekDays'));
        $this->assertSame($templates, $this->getProperty($formatter, 'templates'));
    }

    public function testSetShowOnlyDates(): void
    {
        $formatter = $this->createFormatter();
        $formatter->setShowOnlyDates(true);

        $this->assertTrue($this->getProperty($formatter, 'showOnlyDates'));
    }

    public function testSetHourFormat(): void
    {
        $formatter = $this->createFormatter();
        $formatter->setHourFormat('G:i');

        $this->assertSame('G:i', $this->getProperty($formatter, 'hourFormat'));
    }

    public function testResolveTemplate(): void
    {
        $templates = ['BEFORE_TOMORROW' => 'Wysyłka jutro'];
        $formatter = $this->createFormatter($templates);

        $result = $this->callPrivateMethod($formatter, 'resolveTemplate', [false, 1]);
        $this->assertSame('Wysyłka jutro', $result);
    }

    public function testResolveTemplateThrowsIfNotFound(): void
    {
        $this->expectException(RealizationDaysException::class);
        $this->expectExceptionMessage('Text template not found: BEFORE_TOMORROW');

        $formatter = $this->createFormatter([]); // brak szablonów
        $this->callPrivateMethod($formatter, 'resolveTemplate', [false, 1]);
    }

    public function testParseTemplate(): void
    {
        $templates = ['BEFORE_TOMORROW' => 'Zamów do %GOLDEN_HOUR% - wysyłka %DATE_OR_WEEKDAY%'];
        $formatter = $this->createFormatter($templates);
        $formatter->setHourFormat('H:i');

        $result = $this->callPrivateMethod(
            $formatter,
            'parseTemplate',
            ['Zamów do %GOLDEN_HOUR% - wysyłka %DATE_OR_WEEKDAY%', 'poniedziałek']
        );

        $this->assertSame('Zamów do 10:00 - wysyłka poniedziałek', $result);
    }

    public function testFormatHour(): void
    {
        $formatter = $this->createFormatter();
        $formatter->setHourFormat('H:i');

        $result = $this->callPrivateMethod($formatter, 'formatHour', [15]);

        $this->assertSame('15:00', $result);
    }

    private function getProperty(object $object, string $property): mixed
    {
        $ref = new \ReflectionProperty($object, $property);
        // @noinspection PhpExpressionResultUnusedInspection
        $ref->setAccessible(true);

        return $ref->getValue($object);
    }

    // @phpstan-ignore-next-line
    private function callPrivateMethod(object $object, string $method, array $args = []): mixed
    {
        $ref = new \ReflectionMethod($object, $method);
        // @noinspection PhpExpressionResultUnusedInspection
        $ref->setAccessible(true);

        return $ref->invokeArgs($object, $args);
    }

    /**
     * @param string[] $templates
     */
    private function createFormatter(array $templates = []): RealizationDaysFormatter
    {
        $context = new RealizationViewContext(
            new \DateTimeImmutable('2025-04-01'),
            1,
            false,
            10,
        );

        $formatter = new RealizationDaysFormatter($context);
        $formatter->setTranslation(['Pon', 'Wt', 'Śr', 'Czw', 'Pt', 'Sob', 'Nd'], $templates);

        return $formatter;
    }
}
