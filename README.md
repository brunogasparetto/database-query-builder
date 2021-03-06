

[![Build Status](https://travis-ci.org/brunogasparetto/database-query-builder.svg?branch=master)](https://travis-ci.org/brunogasparetto/database-query-builder)

# Database Query Builder

Simplistic Query Builder made a long time ago for fun.

## Installation

The package is available on [Packagist](https://packagist.org/packages/brunogasparetto/query-builder). You can install it using [Composer](http://getcomposer.org/)

```bash
$ composer require brunogasparetto/query-builder
```

## Example

```php
$database = new QueryBuilder\Database([
    'driver'   => 'mysql',
    'charset'  => 'utf8',
    'host'     => 'localhost',
    'dbname'   => 'databaseName',
    'user'     => 'user',
    'password' => 'password',
    'fetchMode' => PDO::FETCH_OBJ, // Default
]);

$query = $database
    ->select('column')
    ->from('table')
    ->whereOpen()
        ->where('column1', '=', 5)
        ->where('column2', '=', 'Name')
    ->whereClose()
    ->whereOrOpen()
        ->where('column1', '=', 10)
        ->where('column2', '=', 'Other Name')
    ->whereOrClose();

echo (string) $query;

$query
    ->select('coluna2', 'coluna3')
    ->join('tabela2')
        ->on('table.id', '=', 'tabela2.id')
        ->on('userid', '=', 5)
    ->where('tabela2.coluna2', '=', true);

echo (string) $query;
```
