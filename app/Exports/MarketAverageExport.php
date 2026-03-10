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

class MarketAverageExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithColumnWidths
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
      return ProductsQuery::getMarkeAverage($this->params);
    }
    public function headings(): array
    {
        return [
            'Producto',
            'Cliente',
            'Provincia',
            'Ciudad',
            'Mínimo',
            'Máximo',
            'Promedio',
            'Última visita'
        ];
    }
    public function map($data): array
    {
        return [
            $data->product_name,
            $data->location_name,
            $data->province_name,
            $data->city_name,
            $data->min_price,
            $data->max_price,
            $data->avg_price,
            $data->last_observed_at
        ];
    }
}
