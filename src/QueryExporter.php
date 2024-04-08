<?php

namespace Stianscholtz\QueryExporter;

use Exception;
use Illuminate\Contracts\Database\Query\Builder;
use League\Csv\ByteSequence;
use League\Csv\CannotInsertRecord;
use League\Csv\Writer;
use SplTempFileObject;

class QueryExporter
{
    protected string $fileName = 'export';
    protected ?array $headers = null;

    public function __construct(protected Builder $query)
    {
        //
    }

    /**
     * Create a new instance and set the query for the exported file.
     *
     * @param  Builder  $query
     * @return static
     */
    public static function forQuery(Builder $query): static
    {
        return new static($query);
    }

    /**
     * Set the filename for the exported file.
     *
     * @param  string  $filename
     * @return static
     */
    public function filename(string $filename): static
    {
        $this->fileName = $filename;
        return $this;
    }

    /**
     * Set the headers for the exported file.
     *
     * @param  array  $headers
     * @return static
     */
    public function headers(array $headers): static
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * Export query result to CSV.
     * @throws CannotInsertRecord
     * @throws Exception
     */
    public function export(): void
    {
        $data = $this->get();

        if (!$this->headers) {
            $this->headers = array_keys($data[0]);
        } elseif (count($this->headers) !== count($data[0])) {
            throw new Exception('Length of headers must be '.count($data[0]));
        }

        $csv = Writer::createFromFileObject(new SplTempFileObject())
            ->setOutputBOM(ByteSequence::BOM_UTF8);

        $csv->insertOne($this->headers);
        $csv->insertAll($data);
        $csv->output($this->fileName.'.csv');

        exit;
    }

    private function get(): array
    {
        $data = $this->query->get()->toArray();

        //Convert array of objects to array of arrays to support cases such as queries created using the DB facade.
        if (gettype($data[0]) === 'object') {
            $data = $this->convertObjectsToArrays($data);
        }

        return $data;
    }

    /**
     * @param  array  $data
     * @return array
     */
    private function convertObjectsToArrays(array $data): array
    {
        return array_map(function ($record) {
            return (array) $record;
        }, $data);
    }
}
