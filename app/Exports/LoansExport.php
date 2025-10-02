<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LoansExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /** @var \Illuminate\Support\Collection<int, \App\Models\Loan> */
    private Collection $loans;

    public function __construct(Collection $loans)
    {
        $this->loans = $loans;
    }

    public function collection(): Collection
    {
        return $this->loans;
    }

    public function headings(): array
    {
        return [
            'Kode Peminjaman',
            'Nama Peminjam',
            'Keperluan',
            'Tanggal Pinjam',
            'Petugas',
            'Status',
            'Jumlah Item',
            'Jumlah Item Kembali',
        ];
    }

    /**
     * @param  \App\Models\Loan  $loan
     */
    public function map($loan): array
    {
        $totalItems = $loan->relationLoaded('items') ? $loan->items->count() : $loan->items()->count();
        $returned = $loan->relationLoaded('items')
            ? $loan->items->whereNotNull('return_condition')->count()
            : $loan->items()->whereNotNull('return_condition')->count();

        return [
            $loan->code,
            optional($loan->partner)->name,
            $loan->purpose,
            optional($loan->loan_date) instanceof \Carbon\Carbon ? $loan->loan_date->format('Y-m-d') : (string) $loan->loan_date,
            optional($loan->user)->name,
            $loan->status,
            $totalItems,
            $returned,
        ];
    }
}

