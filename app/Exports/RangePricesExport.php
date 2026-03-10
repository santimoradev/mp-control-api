<?php
namespace App\Exports;
use Illuminate\Support\Facades\DB;

use App\Queries\ProductsQuery;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RangePricesExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithColumnWidths
{

    protected $params;

    public function __construct($params)
    {

        $this->params = $params;
    }
    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
    public function columnWidths(): array
    {
        return [
        ];
    }
    public function query()
    {
      $start = $this->params['start'];
      $end = $this->params['end'];
      return ProductsQuery::rangePrices($start, $end, $this->params);
    }
    public function headings(): array
    {
      $months = $this->params['months']->toArray();
      $payload=  array_merge(
            ['Producto'],
            $months,
            ['Promedio']
        );
      return $payload;
    }
    public function map($data): array
    {

        $row = [
            $data->name
        ];



        $values = [];
        foreach ($this->params['months'] as $month) :
            $values[] = $data->{$month} ?? null;
            $row[] = $data->{$month} ?? null;
        endforeach;

        $filtered = array_filter($values, fn($v) => $v !== null);
        $avg = count($filtered) ? round(array_sum($filtered) / count($filtered), 2) : null;
        $row[] = $avg;

        return $row;
    }
}
