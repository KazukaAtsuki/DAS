<?php

namespace App\Exports;

use App\Models\DasLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DasLogExport implements FromCollection, WithHeadings, WithMapping
{
    protected $stackId;
    protected $startDate;
    protected $endDate;

    // Terima request object dari controller
    public function __construct($request)
    {
        $this->stackId = $request->stack_id;
        $this->startDate = $request->start_date;
        $this->endDate = $request->end_date;
    }

    public function collection()
    {
        $query = DasLog::with(['sensorConfig', 'stackConfig'])->orderBy('id', 'desc');

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

    // ... Headings & Map tetap sama ...
    public function headings(): array
    {
        return ['ID', 'Timestamp', 'Stack Name', 'Sensor Parameter', 'Measured Value', 'Raw Value', 'Status DIS'];
    }

    public function map($log): array
    {
        return [
            $log->id, $log->timestamp, $log->stackConfig->stack_name ?? '-', $log->sensorConfig->parameter_name ?? '-',
            $log->measured_value, $log->raw_value, $log->status_sent_dis
        ];
    }
}