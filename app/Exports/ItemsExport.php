<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ItemsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /** @var \Illuminate\Support\Collection<int, \App\Models\Item> */
    private Collection $items;

    public function __construct(Collection $items)
    {
        $this->items = $items;
    }

    public function collection(): Collection
    {
        return $this->items;
    }

    public function headings(): array
    {
        return [
            'Kode Barang',
            'Nama Barang',
            'Kategori',
            'Serial Number',
            'Tahun Pengadaan',
            'Kondisi',
            'Status Hilang',
            'Catatan Pengembalian Terakhir',
        ];
    }

    /**
     * @param  \App\Models\Item  $item
     */
    public function map($item): array
    {
        $condition = str_replace('_', ' ', (string) $item->condition);

        return [
            $item->code,
            $item->name,
            optional($item->category)->name,
            $item->serial_number,
            $item->procurement_year,
            ucfirst($condition),
            $item->is_missing ? 'Ya' : 'Tidak',
            ($item->lastReturn?->return_notes) ?: '-',
        ];
    }
}
