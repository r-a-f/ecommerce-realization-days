# Ecommerce Realization Days

[![Tests](https://img.shields.io/badge/tests-100%25-brightgreen)](#)
[![Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen)](#)
[![MSI](https://img.shields.io/badge/MSI-96%25-brightgreen)](#)
[![PHP](https://img.shields.io/badge/PHP-8.2%20%7C%208.3-blue)](#)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%2010-blue)](#)
[![License](https://img.shields.io/badge/license-Apache--2.0-blue.svg)](LICENSE)
---

## Description

**Ecommerce Realization Days** is a PHP library for calculating and formatting order fulfillment (e.g. shipping) dates in e-commerce systems.

It supports:
- golden hour logic (cutoff time for same-day shipping)
- fixed days off (e.g. holidays)
- weekly days off (e.g. weekends)
- templates with html tags allowed

---

## Installation

Install via Composer:

```
composer require r-a-f/ecommerce-realization-days
```

## ✅ Example (basic)

```php
use RealizationDays\RealizationDays;

$config = new RealizationDays();
$config->setGoldenHour(14);
$config->setRealizationDays(3);
$config->setDateDaysOff(['2025-05-01', '2025-05-03']);
$config->setDateWeekOff([6, 7]); // Saturday and Sunday

$context = $config->calc();

echo $context->date->format('Y-m-d');
````

### 🎯 Formatting and display

You can use `RealizationDaysFormatter` to generate customer-friendly messages like:

> "Order today – ships by Friday"  
> "Order now – ships until 14:00 today"

### 🧪 Example:

```php
use RealizationDays\RealizationDaysFormatter;

// Format output
$formatter = new RealizationDaysFormatter($context);

// Set weekdays and templates
$formatter->setTranslation(
    ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
    [
        'BEFORE_TODAY' => 'Order today – ships before %DATE_OR_WEEKDAY%',
        'BEFORE_TOMORROW' => 'Order today – ships tomorrow!',
        'BEFORE_NEXT_DAYS' => 'Order today – ships by %DATE_OR_WEEKDAY%',
        'AFTER_TODAY' => 'Order now – ships until %GOLDEN_HOUR% today',
        'AFTER_TOMORROW' => 'Order now – ships the day after tomorrow',
        'AFTER_NEXT_DAYS' => 'Order now – ships by %DATE_OR_WEEKDAY%',
    ]
);

echo $formatter->format('d-m-Y'); // e.g. "Order today – ships by 20-04-2025"
```

## 👤 Author
Rafał Pawlukiewicz 🌍 pawlukiewicz.com
