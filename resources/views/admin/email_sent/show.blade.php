@extends('admin.layout.sidebar_admin')

@section('title', 'Detail Email Terkirim')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Back Button -->
            <div class="mb-3">
                <a href="{{ route('admin.email-sent.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Email
                </a>
            </div>

            <!-- Email Detail Card -->
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Detail Email Terkirim</h6>
                        <form action="{{ route('admin.email-sent.destroy', $emailSent->id) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus email ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Hapus Email
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Email Info -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="border rounded p-3 bg-light">
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <strong>Penerima:</strong>
                                    </div>
                                    <div class="col-md-10">
                                        <span class="badge bg-gradient-primary">{{ $emailSent->to }}</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <strong>Subjek:</strong>
                                    </div>
                                    <div class="col-md-10">
                                        {{ $emailSent->subject }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <strong>Tanggal Kirim:</strong>
                                    </div>
                                    <div class="col-md-10">
                                        {{ $emailSent->created_at->format('d F Y H:i:s') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Email Body -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h6 class="mb-2">Isi Email:</h6>
                            <div class="border rounded p-3" style="background-color: #f8f9fa; min-height: 200px;">
                                {!! nl2br($emailSent->body) !!}
                            </div>
                        </div>
                    </div>

                    <!-- Attachments -->
                    @php
                        $attachments = $emailSent->getAttachmentDetails();
                    @endphp
                    @if(count($attachments) > 0)
                        <div class="row">
                            <div class="col-md-12">
                                <h6 class="mb-2">
                                    <i class="fas fa-paperclip"></i> Lampiran ({{ count($attachments) }}):
                                </h6>
                                <div class="list-group">
                                    @foreach($attachments as $attachment)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-file"></i>
                                                <span class="ms-2">{{ $attachment['filename'] }}</span>
                                            </div>
                                            <div>
                                                <a href="{{ $attachment['url'] }}"
                                                   target="_blank"
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-download"></i> Download
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle"></i> Email ini tidak memiliki lampiran
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
