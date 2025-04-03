<?php

declare(strict_types=1);

namespace RealizationDays\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RealizationDays\RealizationDays;

class RealizationDaysTest extends TestCase
{
    /**
     * @return array<int, array{
     *     0: int,
     *     1: int,
     *     2: \DateTimeImmutable,
     *     3: null|\DateTimeImmutable,
     *     4: string,
     *     5: int,
     *     6: bool
     * }>
     */
    public static function sampleDataCalc(): array
    {
        return [
            [
                0,
                0,
                new \DateTimeImmutable('2025-04-04 12:10:00'),
                null,

                '2025-04-07',
                3,
                true,
            ],
            [
                0,
                23,
                new \DateTimeImmutable('2025-04-04 15:10:00'),
                null,

                '2025-04-04',
                0,
                false,
            ],
            [
                0,
                14,
                new \DateTimeImmutable('2025-04-04 12:10:00'),
                null,

                '2025-04-04',
                0,
                false,
            ],
            [
                0,
                14,
                new \DateTimeImmutable('2025-04-04 15:10:00'),
                null,

                '2025-04-07',
                3,
                true,
            ],
            [
                0,
                14,
                new \DateTimeImmutable('2025-04-05 12:10:00'),
                null,

                '2025-04-07',
                2,
                true,
            ],
            [
                0,
                14,
                new \DateTimeImmutable('2025-04-05 15:10:00'),
                null,

                '2025-04-07',
                2,
                true,
            ],
            [
                0,
                14,
                new \DateTimeImmutable('2025-03-31 12:10:00'),
                null,

                '2025-03-31',
                0,
                false,
            ],
            [
                0,
                14,
                new \DateTimeImmutable('2025-03-31 15:10:00'),
                null,

                '2025-04-01',
                1,
                true,
            ],
            [
                1,
                14,
                new \DateTimeImmutable('2025-03-31 12:10:00'),
                null,

                '2025-04-01',
                1,
                false,
            ],
            [
                1,
                14,
                new \DateTimeImmutable('2025-03-31 15:10:00'),
                null,

                '2025-04-01',
                1,
                true,
            ],
            [
                2,
                14,
                new \DateTimeImmutable('2025-03-31 12:10:00'),
                null,

                '2025-04-02',
                2,
                false,
            ],
            [
                2,
                14,
                new \DateTimeImmutable('2025-03-31 15:10:00'),
                null,

                '2025-04-02',
                2,
                true,
            ],
            [
                3,
                14,
                new \DateTimeImmutable('2025-03-31 12:10:00'),
                null,

                '2025-04-03',
                3,
                false,
            ],
            [
                3,
                14,
                new \DateTimeImmutable('2025-03-31 15:10:00'),
                null,

                '2025-04-03',
                3,
                true,
            ],

            [
                4,
                14,
                new \DateTimeImmutable('2025-03-31 15:10:00'),
                null,

                '2025-04-04',
                4,
                true,
            ],
            [
                5,
                14,
                new \DateTimeImmutable('2025-03-31 15:10:00'),
                null,

                '2025-04-07',
                7,
                true,
            ],
            [
                20,
                14,
                new \DateTimeImmutable('2025-03-31 15:10:00'),
                null,

                '2025-04-21',
                21,
                true,
            ],
        ];
    }

    /**
     * @return array<int, array{
     *     0: int,
     *     1: int,
     *     2: \DateTimeImmutable,
     *     3: null|\DateTimeImmutable,
     *     4: string,
     *     5: int,
     *     6: bool
     * }>
     */
    public static function sampleDataCalcWithDaysOff(): array
    {
        return [
            [
                0,
                14,
                new \DateTimeImmutable('2025-03-31 12:10:00'),
                null,

                '2025-03-31',
                0,
                false,
            ],
            [
                0,
                14,
                new \DateTimeImmutable('2025-03-31 15:10:00'),
                null,

                '2025-04-05',
                5,
                true,
            ],
            [
                1,
                14,
                new \DateTimeImmutable('2025-03-31 12:10:00'),
                null,

                '2025-04-05',
                5,
                false,
            ],
            [
                1,
                14,
                new \DateTimeImmutable('2025-03-31 15:10:00'),
                null,

                '2025-04-05',
                5,
                true,
            ],
            [
                2,
                14,
                new \DateTimeImmutable('2025-03-31 12:10:00'),
                null,

                '2025-04-05',
                5,
                false,
            ],
            [
                2,
                14,
                new \DateTimeImmutable('2025-03-31 15:10:00'),
                null,

                '2025-04-05',
                5,
                true,
            ],
            [
                3,
                14,
                new \DateTimeImmutable('2025-03-31 12:10:00'),
                null,

                '2025-04-05',
                5,
                false,
            ],
            [
                3,
                14,
                new \DateTimeImmutable('2025-03-31 15:10:00'),
                null,

                '2025-04-05',
                5,
                true,
            ],
            [
                4,
                14,
                new \DateTimeImmutable('2025-03-31 15:10:00'),
                null,

                '2025-04-05',
                5,
                true,
            ],
            [
                5,
                14,
                new \DateTimeImmutable('2025-03-31 15:10:00'),
                null,

                '2025-04-05',
                5,
                true,
            ],
            [
                8,
                14,
                new \DateTimeImmutable('2025-03-31 15:10:00'),
                null,

                '2025-04-08',
                8,
                true,
            ],
            [
                20,
                14,
                new \DateTimeImmutable('2025-03-31 15:10:00'),
                null,

                '2025-04-21',
                21,
                true,
            ],
        ];
    }

    /**
     * @return array<int, array{
     *     0: int,
     *     1: int,
     *     2: \DateTimeImmutable,
     *     3: string,
     *     4: string,
     *     5: int,
     *     6: bool,
     *     7: array<int, string>
     * }>
     */
    public static function sampleDataCalcAvaibleFromDateWithDaysOff(): array
    {
        return [
            [
                0,
                14,
                new \DateTimeImmutable('2025-03-31 12:10:00'),
                '2025-04-07',

                '2025-04-07',
                7,
                false,
                [],
            ],
            [
                0,
                14,
                new \DateTimeImmutable('2025-03-31 15:10:00'),
                '2025-04-07',

                '2025-04-07',
                7,
                true,
                [],
            ],
            [
                1,
                14,
                new \DateTimeImmutable('2025-03-31 15:10:00'),
                '2025-04-07',

                '2025-04-08',
                8,
                true,
                [],
            ],
            [
                2,
                14,
                new \DateTimeImmutable('2025-03-31 15:10:00'),
                '2025-04-07',

                '2025-04-09',
                9,
                true,
                [],
            ],
            [
                5,
                14,
                new \DateTimeImmutable('2025-03-31 15:10:00'),
                '2025-04-07',

                '2025-04-14',
                14,
                true,
                [],
            ],
            [
                0,
                14,
                new \DateTimeImmutable('2025-03-31 15:10:00'),
                '2025-04-07',

                '2025-04-09',
                9,
                true,
                [
                    '2025-04-04',
                    '2025-04-07',
                    '2025-04-08',
                ],
            ],
            // wrong:
            [
                3,
                14,
                new \DateTimeImmutable('2025-03-31 15:10:00'),
                '2025-04-07',

                '2025-04-14',
                14,
                true,
                [
                    '2025-04-04',
                    '2025-04-05',
                    '2025-04-06',
                    '2025-04-07',
                    '2025-04-08',
                    '2025-04-09',
                    '2025-04-10',
                    '2025-04-11',
                ],
            ],
            [
                3,
                14,
                new \DateTimeImmutable('2025-03-31 15:10:00'),
                '2025-04-07',

                '2025-04-10',
                10,
                true,
                [
                    '2025-04-06',
                    '2025-04-07',
                    '2025-04-08',
                ],
            ],
            [
                3,
                0,
                new \DateTimeImmutable('2025-03-31 15:10:00'),
                '2025-04-07',

                '2025-04-10',
                10,
                true,
                [
                    '2025-04-06',
                    '2025-04-07',
                    '2025-04-08',
                ],
            ],
            [
                3,
                23,
                new \DateTimeImmutable('2025-03-31 15:10:00'),
                '2025-04-07',

                '2025-04-10',
                10,
                false,
                [
                    '2025-04-06',
                    '2025-04-07',
                    '2025-04-08',
                ],
            ],
        ];
    }

    #[DataProvider('sampleDataCalc')]
    public function testRealization(
        int $realizationDays,
        int $goldenHour,
        \DateTimeImmutable|string $nowDate,
        null|\DateTimeImmutable|string $avaibleFromDate,
        string $expectedDate,
        int $expectedDays,
        bool $afterGoldenHour,
    ): void {
        $realization = new RealizationDays($nowDate);
        $realization->setRealizationDays($realizationDays);
        $realization->setGoldenHour($goldenHour);
        $realization->setAvaibleFromDate($avaibleFromDate);
        $context = $realization->calc();

        static::assertSame($expectedDate, $context->date->format('Y-m-d'));
        static::assertSame($expectedDays, $context->realizationDays);
        static::assertSame($afterGoldenHour, $context->afterGoldenHour);
    }

    #[DataProvider('sampleDataCalcWithDaysOff')]
    public function testRealizationWithDaysOff(
        int $realizationDays,
        int $goldenHour,
        \DateTimeImmutable|string $nowDate,
        null|\DateTimeImmutable|string $avaibleFromDate,
        string $expectedDate,
        int $expectedDays,
        bool $passGoldenHour,
    ): void {
        $realization = new RealizationDays($nowDate);
        $realization->setRealizationDays($realizationDays);
        $realization->setGoldenHour($goldenHour);
        $realization->setAvaibleFromDate($avaibleFromDate);

        // only Sundays
        $realization->setDateWeekOff([7]);

        // wanna go to holiday? ;)
        $realization->setDateDaysOff([
            '2025-04-01',
            '2025-04-02',
            '2025-04-03',
            '2025-04-04',
            '2025-04-07',
            '2025-04-09',
            '2025-04-10',
        ]);

        $context = $realization->calc();

        static::assertSame($expectedDate, $context->date->format('Y-m-d'));
        static::assertSame($expectedDays, $context->realizationDays);
        static::assertSame($passGoldenHour, $context->afterGoldenHour);
    }

    /**
     * @param string[] $daysOff
     */
    #[DataProvider('sampleDataCalcAvaibleFromDateWithDaysOff')]
    public function testRealizationWithAvaibleFromDate(
        int $realizationDays,
        int $goldenHour,
        \DateTimeImmutable|string $nowDate,
        null|\DateTimeImmutable|string $avaibleFromDate,
        string $expectedDate,
        int $expectedDays,
        bool $passGoldenHour,
        array $daysOff,
    ): void {
        $realization = new RealizationDays($nowDate);
        $realization->setRealizationDays($realizationDays);
        $realization->setGoldenHour($goldenHour);
        $realization->setAvaibleFromDate($avaibleFromDate);

        // wanna go to holiday? ;)
        $realization->setDateDaysOff($daysOff);

        $context = $realization->calc();

        static::assertSame($expectedDate, $context->date->format('Y-m-d'));
        static::assertSame($expectedDays, $context->realizationDays);
        static::assertSame($passGoldenHour, $context->afterGoldenHour);
    }

    public function testSetAvaibleFromDateWithDateTime(): void
    {
        $config = new RealizationDays();
        $date = new \DateTimeImmutable('2025-04-01 14:33:00');

        $config->setAvaibleFromDate($date);

        $expected = $date->setTime(0, 0, 0);
        $this->assertEquals($expected, $this->getProperty($config, 'avaibleFromDate'));
    }

    public function testSetAvaibleFromDateWithString(): void
    {
        $config = new RealizationDays();
        $config->setAvaibleFromDate('2025-04-01');

        $expected = (new \DateTimeImmutable('2025-04-01'))->setTime(0, 0, 0);
        $this->assertEquals($expected, $this->getProperty($config, 'avaibleFromDate'));
    }

    public function testSetAvaibleFromDateWithNull(): void
    {
        $config = new RealizationDays();
        $config->setAvaibleFromDate(null);

        $this->assertNull($this->getProperty($config, 'avaibleFromDate'));
    }

    public function testSetDateWeekOffValid(): void
    {
        $config = new RealizationDays();
        $config->setDateWeekOff([1, 2, 3]);

        $this->assertSame([1, 2, 3], $this->getProperty($config, 'dateWeekOff'));

        $config = new RealizationDays();
        // @phpstan-ignore-next-line
        $config->setDateWeekOff(['1', '2', 3]);

        $this->assertSame([1, 2, 3], $this->getProperty($config, 'dateWeekOff'));
    }

    public function testSetDateWeekOffThrows(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Each week days-off must be an integer between 1 and 7');

        $config = new RealizationDays();
        $config->setDateWeekOff([1, 0, 8]);
    }

    public function testSetDateDaysOffValid(): void
    {
        $config = new RealizationDays();
        $config->setDateDaysOff(['2025-04-01', '2025-12-25']);

        $this->assertSame(['2025-04-01', '2025-12-25'], $this->getProperty($config, 'dateDaysOff'));
    }

    public function testSetDateDaysOffInvalidFormat(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid day-off dates date format: 01-04-2025');

        $config = new RealizationDays();
        $config->setDateDaysOff(['01-04-2025']);
    }

    public function testSetDateDaysOffInvalidFormatRegex(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid day-off dates date format: format-2025-01-01');

        $config = new RealizationDays();
        $config->setDateDaysOff(['format-2025-01-01']);
    }

    public function testSetRealizationDaysValid(): void
    {
        $config = new RealizationDays();
        $config->setRealizationDays(5);

        $this->assertSame(5, $this->getProperty($config, 'realizationDays'));
    }

    public function testSetRealizationDaysThrows(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('RealizationDays must be > 0');

        $config = new RealizationDays();
        $config->setRealizationDays(-1);
    }

    public function testSetGoldenHourValid(): void
    {
        $config = new RealizationDays();
        $config->setGoldenHour(10);

        $this->assertSame(10, $this->getProperty($config, 'goldenHour'));

        $config = new RealizationDays();
        $config->setGoldenHour(0);

        $this->assertSame(0, $this->getProperty($config, 'goldenHour'));

        $config = new RealizationDays();
        $config->setGoldenHour(23);

        $this->assertSame(23, $this->getProperty($config, 'goldenHour'));
    }

    public function testSetGoldenHourTooLow(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('GoldenHour must be between 0 and 23');

        $config = new RealizationDays();
        $config->setGoldenHour(-1);
    }

    public function testSetGoldenHourTooHigh(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('GoldenHour must be between 0 and 23');

        $config = new RealizationDays();
        $config->setGoldenHour(24);
    }

    private function getProperty(object $object, string $property): mixed
    {
        $ref = new \ReflectionProperty($object, $property);
        // @noinspection PhpExpressionResultUnusedInspection
        $ref->setAccessible(true);

        return $ref->getValue($object);
    }
}
