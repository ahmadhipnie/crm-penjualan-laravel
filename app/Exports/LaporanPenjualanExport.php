<?php

namespace App\Exports;

use App\Models\Penjualan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanPenjualanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Penjualan::with(['user', 'detailPenjualans.barang']);

        // Apply filters
        if (isset($this->filters['start_date']) && $this->filters['start_date']) {
            $query->whereDate('created_at', '>=', $this->filters['start_date']);
        }

        if (isset($this->filters['end_date']) && $this->filters['end_date']) {
            $query->whereDate('created_at', '<=', $this->filters['end_date']);
        }

        if (isset($this->filters['status']) && $this->filters['status'] && $this->filters['status'] != 'all') {
            $query->where('status', $this->filters['status']);
        }

        if (isset($this->filters['customer']) && $this->filters['customer']) {
            $query->where('id_user', $this->filters['customer']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Kode Transaksi',
            'Tanggal',
            'Customer',
            'Email',
            'Produk',
            'Jumlah Item',
            'Total Harga',
            'Status'
        ];
    }

    public function map($penjualan): array
    {
        // Get product names
        $products = $penjualan->detailPenjualans->pluck('barang.nama_barang')->join(', ');
        $totalItems = $penjualan->detailPenjualans->sum('qty');

        return [
            $penjualan->kode_transaksi,
            \Carbon\Carbon::parse($penjualan->created_at)->format('d/m/Y H:i'),
            $penjualan->user->nama ?? '-',
            $penjualan->user->email ?? '-',
            $products,
            $totalItems,
            'Rp ' . number_format($penjualan->total_harga, 0, ',', '.'),
            ucfirst(str_replace('_', ' ', $penjualan->status))
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 18,
            'C' => 20,
            'D' => 25,
            'E' => 35,
            'F' => 12,
            'G' => 18,
            'H' => 20,
        ];
    }
}
