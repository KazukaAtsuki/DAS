<?php

namespace App\Exports;

use App\Models\RcaLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RcaLogExport implements FromCollection, WithHeadings, WithMapping
{
    // --- PERBAIKAN DI SINI: Deklarasikan variabel dulu ---
    protected $stackId;
    protected $startDate;
    protected $endDate;

    // Constructor menerima request
    public function __construct($request)
    {
        // Ambil data dari object request, pakai null coalescing operator (?? null) biar aman
        $this->stackId = $request->stack_id ?? null;
        $this->startDate = $request->start_date ?? null;
        $this->endDate = $request->end_date ?? null;
    }

    public function collection()
    {
        $query = RcaLog::with(['sensorConfig', 'stackConfig'])->orderBy('id', 'desc');

        if ($this->stackId) {
            $query->where('stack_config_id', $this->stackId);
        }

        // Logika Filter Tanggal
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