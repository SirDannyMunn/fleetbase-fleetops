<?php

namespace Fleetbase\FleetOps\Exports;

use Fleetbase\FleetOps\Models\ServiceArea;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ServiceAreaExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting
{
    protected array $selections = [];

    public function __construct(array $selections = [])
    {
        $this->selections = $selections;
    }

    public function map($service_area): array
    {
        return [
            $service_area->public_id,
            $service_area->internal_id,
            $service_area->name,
            $service_area->vendor_name,
            $service_area->vehicle_name,
            $service_area->phone,
            $service_area->drivers_license_number,
            $service_area->country,
            $service_area->created_at,
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Internal ID',
            'Service',
            'Service Area',
            'Zone',
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
            return ServiceArea::where('company_uuid', session('company'))
                ->whereIn('uuid', $this->selections)
                ->get();
        }

        return ServiceArea::where('company_uuid', session('company'))->get();
    }
}
