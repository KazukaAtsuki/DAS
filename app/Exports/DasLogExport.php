<?php

namespace App\Exports;

use App\Models\DasLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DasLogExport implements FromCollection, WithHeadings, WithMapping
{
    protected $stackId;

    public function __construct($stackId)
    {
        $this->stackId = $stackId;
    }

    public function collection()
    {
        // Ambil data log, urutkan dari yang terbaru
        $query = DasLog::with(['sensorConfig', 'stackConfig'])->orderBy('id', 'desc');

        // Jika ada filter stack, terapkan
        if ($this->stackId) {
            $query->where('stack_config_id', $this->stackId);
        }

        // Limit data agar tidak terlalu berat (opsional, misal ambil 5000 data terakhir)
        // $query->limit(5000);

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
            'Raw Value',
            'Status DIS',
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
            $log->raw_value,
            $log->status_sent_dis,
        ];
    }
}