<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DamageLogsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /** @var \Illuminate\Support\Collection<int, \App\Models\LoanItem> */
    private Collection $logs;

    public function __construct(Collection $logs)
    {
        $this->logs = $logs;
    }

    public function collection(): Collection
    {
        return $this->logs;
    }

    public function headings(): array
    {
        return [
            'Tanggal Pengembalian',
            'Nama Barang',
            'Kode Barang',
            'Kondisi Pengembalian',
            'Catatan',
            'Kode Peminjaman',
            'Nama Partner',
        ];
    }

    /**
     * @param  \App\Models\LoanItem  $log
     */
    public function map($log): array
    {
        $condition = str_replace('_', ' ', (string) $log->return_condition);
        $item = $log->item;
        $loan = $log->loan;

        return [
            optional($log->updated_at)?->format('Y-m-d H:i'),
            $item?->name,
            $item?->code,
            ucfirst($condition),
            $log->return_notes ?: '-',
            $loan?->code,
            $loan?->partner?->name,
        ];
    }
}

