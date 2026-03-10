<?php
namespace App\Exports;
use Illuminate\Support\Facades\DB;

use App\Queries\InventoryReportQuery;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InventoryExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithColumnWidths
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
      return InventoryReportQuery::build($this->params);
    }
    public function headings(): array
    {
        return [
            'Producto',
            'Cliente',
            'Provincia',
            'Ciudad',
            'Fecha de visita',
            'Stock',
            'Fecha de caducidad',
            'Vida útil'
        ];
    }
    public function map($data): array
    {
        return [
            $data->product->name,
            $data->location->name,
            $data->location->province->name,
            $data->location->city->name,
            $data->observed_at,
            $data->stock,
            $data->expiration_date,
            $data->days_to_expire
        ];
    }
}
