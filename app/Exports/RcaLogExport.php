<?php

namespace App\Exports;

use App\Models\RcaLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RcaLogExport implements FromCollection, WithHeadings, WithMapping
{
    protected $stackId;

    public function __construct($stackId)
    {
        $this->stackId = $stackId;
    }

    public function collection()
    {
        // Ambil data RCA, urutkan dari yang terbaru
        $query = RcaLog::with(['sensorConfig', 'stackConfig'])->orderBy('id', 'desc');

        // Filter berdasarkan Stack jika ada
        if ($this->stackId) {
            $query->where('stack_config_id', $this->stackId);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Timestamp',
            'Stack Name',
            'Sensor Parameter',
            'Measured Value',
            'Corrected O2',
            'Raw Value',
        ];
    }

    public function map($log): array
    {
        return [
            $log->id,
            $log->timestamp,
            $log->stackConfig->stack_name ?? '-',
            $log->sensorConfig->parameter_name ?? '-',
            $log->measured_value,
            $log->corrected_o2,
            $log->raw_value,
        ];
    }
}