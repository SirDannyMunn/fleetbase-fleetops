<?php

namespace Fleetbase\FleetOps\Exports;

use Fleetbase\FleetOps\Models\Vehicle;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class VehicleExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting
{
    protected array $selections = [];

    public function __construct(array $selections = [])
    {
        $this->selections = $selections;
    }

    public function map($vehicle): array
    {
        return [
            $vehicle->public_id,
            $vehicle->internal_id,
            $vehicle->display_name,
            $vehicle->driver_name,
            $vehicle->model_data,
            $vehicle->created_at,
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Internal ID',
            'Name',
            'Driver Assigned',
            'Make',
            'Model',
            'Year',
            'Created',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'F' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'G' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        if ($this->selections) {
            return Vehicle::where('company_uuid', session('company'))
                ->whereIn('uuid', $this->selections)
                ->get();
        }

        return Vehicle::where('company_uuid', session('company'))->get();
    }
}
