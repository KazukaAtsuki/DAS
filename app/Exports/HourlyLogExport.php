<?php

namespace App\Exports;

use App\Models\HourlyLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class HourlyLogExport implements FromCollection, WithHeadings, WithMapping
{
    protected $stackId;
    protected $startDate;
    protected $endDate;

    public function __construct($request)
    {
        // Ambil dari request
        $this->stackId = $request->stack_id ?? null;
        $this->startDate = $request->start_date ?? null;
        $this->endDate = $request->end_date ?? null;
    }

    public function collection()
    {
        $query = HourlyLog::with(['sensorConfig', 'stackConfig'])->latest('timestamp');

        if ($this->stackId) {
            $query->where('stack_config_id', $this->stackId);
        }

        // Filter Tanggal di Excel
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('timestamp', [
                $this->startDate . ' 00:00:00',
                $this->endDate . ' 23:59:59'
            ]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [ 'ID', 'Timestamp', 'Stack Name', 'Sensor Parameter', 'Measured Value', 'Corrected Value' ];
    }

    public function map($log): array
    {
        return [
            $log->id,
            $log->timestamp,
            $log->stackConfig->stack_name ?? '-',
            $log->sensorConfig->parameter_name ?? '-',
            $log->measured_value,
            $log->corrected_value,
        ];
    }
}