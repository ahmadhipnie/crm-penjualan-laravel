@extends('admin.layout.sidebar_admin')

@section('title', 'Emails')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Email Management</h6>
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="card-body px-4 pt-3 pb-2">
                    <ul class="nav nav-pills mb-3">
                        <li class="nav-item">
                            <a class="nav-link {{ $status == 'all' ? 'active' : '' }}"
                               href="{{ route('admin.emails.index', ['status' => 'all']) }}">
                                Semua <span class="badge bg-secondary ms-1">{{ $statusCounts['all'] }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $status == 'unread' ? 'active' : '' }}"
                               href="{{ route('admin.emails.index', ['status' => 'unread']) }}">
                                Belum Dibaca <span class="badge bg-warning ms-1">{{ $statusCounts['unread'] }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $status == 'read' ? 'active' : '' }}"
                               href="{{ route('admin.emails.index', ['status' => 'read']) }}">
                                Sudah Dibaca <span class="badge bg-info ms-1">{{ $statusCounts['read'] }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $status == 'replied' ? 'active' : '' }}"
                               href="{{ route('admin.emails.index', ['status' => 'replied']) }}">
                                Sudah Dibalas <span class="badge bg-success ms-1">{{ $statusCounts['replied'] }}</span>
                            </a>
                        </li>
                    </ul>

                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pengirim</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Subjek</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($emails as $email)
                                <tr class="{{ $email->status == 'unread' ? 'bg-light' : '' }}">
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <i class="fas fa-envelope {{ $email->status == 'unread' ? 'text-warning' : 'text-secondary' }} me-2"></i>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm {{ $email->status == 'unread' ? 'font-weight-bold' : '' }}">
                                                    {{ Str::limit($email->from, 40) }}
                                                </h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs mb-0 {{ $email->status == 'unread' ? 'font-weight-bold' : '' }}">
                                            {{ Str::limit($email->subject, 60) }}
                                        </p>
                                        @if($email->attachments && count($email->attachments) > 0)
                                            <i class="fas fa-paperclip text-sm text-secondary"></i>
                                        @endif
                                    </td>
                                    <td>
                                        @if($email->status == 'unread')
                                            <span class="badge badge-sm bg-gradient-warning">Belum Dibaca</span>
                                        @elseif($email->status == 'read')
                                            <span class="badge badge-sm bg-gradient-info">Sudah Dibaca</span>
                                        @else
                                            <span class="badge badge-sm bg-gradient-success">Sudah Dibalas</span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-secondary text-xs font-weight-normal">
                                            {{ $email->created_at->diffForHumans() }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('admin.emails.show', $email->id) }}"
                                           class="btn btn-sm btn-info mb-0" title="Lihat Detail">
                                            Detail
                                        </a>
                                        <form action="{{ route('admin.emails.destroy', $email->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Yakin ingin menghapus email ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger mb-0" title="Hapus">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="fas fa-inbox fa-3x text-secondary mb-3"></i>
                                        <p class="text-secondary mb-0">Tidak ada email</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $emails->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
