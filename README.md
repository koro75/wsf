# Webshippy refactoring exercise

## Requirements
- PHP 8
- Composer

## Setup
```shell script
git clone https://github.com/koro75/wsf
composer install
```

## Usage
### `webshippy:orders`
Lists all orders from `orders.csv`.
```shell script
$ php bin/console webshippy:orders

+---------+----------+----------+---------------------+
| Product | Quantity | Priority | Created at          |
+---------+----------+----------+---------------------+
| 3       | 5        | high     | 2021-03-23 05:01:29 |
| 1       | 2        | high     | 2021-03-25 14:51:47 |
| 2       | 1        | medium   | 2021-03-21 14:00:26 |
| 1       | 8        | medium   | 2021-03-22 09:58:09 |
| 3       | 1        | medium   | 2021-03-22 12:31:54 |
| 1       | 6        | low      | 2021-03-21 06:17:20 |
| 2       | 4        | low      | 2021-03-22 17:41:32 |
| 2       | 2        | low      | 2021-03-24 11:02:06 |
| 3       | 2        | low      | 2021-03-24 12:39:58 |
| 1       | 1        | low      | 2021-03-25 19:08:22 |
+---------+----------+----------+---------------------+
```

### `webshippy:orders:fulfillable`
Lists stock-filtered orders from `orders.csv`.
```shell script
$ php bin/console webshippy:orders:fulfillable '{"1":2,"2":3,"3":1}'

+---------+----------+----------+---------------------+
| Product | Quantity | Priority | Created at          |
+---------+----------+----------+---------------------+
| 1       | 2        | high     | 2021-03-25 14:51:47 |
| 2       | 1        | medium   | 2021-03-21 14:00:26 |
| 3       | 1        | medium   | 2021-03-22 12:31:54 |
| 2       | 2        | low      | 2021-03-24 11:02:06 |
| 1       | 1        | low      | 2021-03-25 19:08:22 |
+---------+----------+----------+---------------------+
```

## Unit testing
```shell script
$ php ./vendor/bin/phpunit

PHPUnit 9.5.11 by Sebastian Bergmann and contributors.

Testing 
......                                                              6 / 6 (100%)

Time: 00:00.031, Memory: 10.00 MB

OK (6 tests, 205 assertions)
--------------------------------------
```