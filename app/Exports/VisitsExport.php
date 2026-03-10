<?php
namespace App\Exports;
use Illuminate\Support\Facades\DB;

use App\Models\Visit;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class VisitsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithColumnWidths
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
      return Visit::query()
          ->reportFilters($this->params);
    }
    public function headings(): array
    {
        return [
            'Usuario',
            'Cliente',
            'Provincia',
            'Ciudad',
            'Fecha de visita',
            'CheckIn',
            'CheckOut',
            'Duración',
            'Segundos'
        ];
    }
    public function map($data): array
    {
      $fullName = $data->assignedTo->first_name ?? ''.' '.$data->assignedTo->last_name;
        return [
            $fullName,
            $data->location->name,
            $data->location->province->name,
            $data->location->city->name,
            $data->scheduled_date,
            $data->check_in,
            $data->check_out,
            $data->duration,
            $data->duration_seconds,
        ];
    }
}
