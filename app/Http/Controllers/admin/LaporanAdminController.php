<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanAdminController extends Controller
{
    /**
     * Display laporan penjualan
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $status = $request->get('status');
        $customer = $request->get('customer');

        // Query penjualan dengan relasi
        $query = Penjualan::with(['user', 'detailPenjualans.barang']);

        // Apply filters
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        if ($status && $status != 'all') {
            $query->where('status', $status);
        }

        if ($customer) {
            $query->where('id_user', $customer);
        }

        // Get data
        $penjualan = $query->orderBy('created_at', 'desc')->get();

        // Calculate summary statistics
        $totalPenjualan = $penjualan->count();
        $totalPendapatan = $penjualan->where('status', 'selesai')->sum('total_harga');
        $totalPending = $penjualan->whereIn('status', ['menunggu_pembayaran', 'sedang_diproses'])->count();
        $totalDibatalkan = $penjualan->where('status', 'dibatalkan')->count();

        // Get customers for filter dropdown
        $customers = User::where('role', 'customer')->orderBy('nama')->get();

        return view('admin.laporan.index', compact(
            'penjualan',
            'totalPenjualan',
            'totalPendapatan',
            'totalPending',
            'totalDibatalkan',
            'customers',
            'startDate',
            'endDate',
            'status',
            'customer'
        ));
    }

    /**
     * Export laporan ke Excel
     */
    public function exportExcel(Request $request)
    {
        return Excel::download(
            new \App\Exports\LaporanPenjualanExport($request->all()),
            'laporan-penjualan-' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Export laporan ke PDF
     */
    public function exportPdf(Request $request)
    {
        // Get filter parameters
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $status = $request->get('status');
        $customer = $request->get('customer');

        // Query penjualan
        $query = Penjualan::with(['user', 'detailPenjualans.barang']);

        // Apply filters
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        if ($status && $status != 'all') {
            $query->where('status', $status);
        }

        if ($customer) {
            $query->where('id_user', $customer);
        }

        $penjualan = $query->orderBy('created_at', 'desc')->get();

        // Calculate summary
        $totalPenjualan = $penjualan->count();
        $totalPendapatan = $penjualan->where('status', 'selesai')->sum('total_harga');

        $pdf = Pdf::loadView('admin.laporan.pdf', compact(
            'penjualan',
            'totalPenjualan',
            'totalPendapatan',
            'startDate',
            'endDate',
            'status'
        ));

        return $pdf->download('laporan-penjualan-' . date('Y-m-d') . '.pdf');
    }
}
