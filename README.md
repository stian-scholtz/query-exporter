# Laravel Query Exporter

Laravel Query Exporter is a handy utility package for Laravel applications that allows you to easily export query results to CSV files. It provides a simple and flexible interface to streamline the process of exporting database query results.

## Features

- Export query results to CSV files effortlessly.
- Customize file name and headers for exported CSV files.
- Seamless integration with Laravel's query builder via the DB Facade or Eloquent Models.

## Installation

You can install the Laravel Query Exporter via Composer. Run the following command in your terminal:

```
composer require yourvendor/query-exporter
```

## Usage

Using the Laravel Query Exporter is straightforward:

```php
use Stianscholtz\QueryExporter\QueryExporter;
use DB;

$query = DB::table('your_table')
            ->select('column1', 'column2')
            ->where('condition', 'value');

QueryExporter::forQuery($query)
    ->filename('my-file')
    ->headers(['Column 1', 'Column 2'])//Optional, selected columns in query will be used as default headers.
    ->export();
```

## Contributing
Contributions are welcome! Please feel free to submit a pull request.

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.