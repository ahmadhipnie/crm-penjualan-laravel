@extends('admin.layout.sidebar_admin')

@section('title', 'Email Terkirim')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Alert Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Filter Card -->
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Filter Email Terkirim</h6>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.email-sent.index') }}" method="GET" id="filterForm">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="search">Pencarian</label>
                                    <input type="text" class="form-control" id="search" name="search"
                                           placeholder="Cari email, subjek, atau isi..."
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="start_date">Tanggal Mulai</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date"
                                           value="{{ request('start_date') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="end_date">Tanggal Akhir</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date"
                                           value="{{ request('end_date') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-primary btn-sm w-100 mb-2">
                                            <i class="fas fa-search"></i> Cari
                                        </button>
                                        <a href="{{ route('admin.email-sent.index') }}" class="btn btn-secondary btn-sm w-100">
                                            <i class="fas fa-redo"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Data Table Card -->
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Daftar Email Terkirim</h6>
                        <span class="badge badge-primary">Total: {{ $emailsSent->total() }} Email</span>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Penerima</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Subjek</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Isi Email</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Lampiran</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Kirim</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($emailsSent as $index => $email)
                                    <tr>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0 ms-3">{{ $emailsSent->firstItem() + $index }}</p>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $email->to }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ Str::limit($email->subject, 40) }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs text-secondary mb-0">{{ Str::limit(strip_tags($email->body), 60) }}</p>
                                        </td>
                                        <td class="align-middle text-center">
                                            @if($email->getAttachmentCount() > 0)
                                                <span class="badge badge-sm bg-gradient-info">
                                                    <i class="fas fa-paperclip"></i> {{ $email->getAttachmentCount() }}
                                                </span>
                                            @else
                                                <span class="text-secondary text-xs">-</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                {{ $email->created_at->format('d M Y H:i') }}
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <a href="{{ route('admin.email-sent.show', $email->id) }}"
                                               class="btn btn-link text-info text-gradient px-2 mb-0"
                                               data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Detail">
                                                Detail
                                            </a>
                                            <form action="{{ route('admin.email-sent.destroy', $email->id) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus email ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-link text-danger text-gradient px-2 mb-0"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="fas fa-inbox fa-3x text-secondary mb-3"></i>
                                                <p class="text-secondary mb-0">Tidak ada email terkirim</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($emailsSent->hasPages())
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-sm text-secondary mb-0">
                                    Menampilkan {{ $emailsSent->firstItem() }} sampai {{ $emailsSent->lastItem() }} dari {{ $emailsSent->total() }} email
                                </p>
                            </div>
                            <div>
                                {{ $emailsSent->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>
@endpush
@endsection
