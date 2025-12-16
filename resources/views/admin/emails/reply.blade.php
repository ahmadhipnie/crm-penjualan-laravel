@extends('admin.layout.sidebar_admin')

@section('title', 'Balas Email')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Balas Email</h6>
                        <a href="{{ route('admin.emails.show', $email->id) }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Original Email Preview -->
                    <div class="alert alert-secondary">
                        <strong>Email Asli:</strong>
                        <p class="mb-1"><strong>Dari:</strong> {{ $email->from }}</p>
                        <p class="mb-1"><strong>Subjek:</strong> {{ $email->subject }}</p>
                        <p class="mb-0"><strong>Tanggal:</strong> {{ $email->created_at->format('d F Y H:i') }}</p>
                    </div>

                    <!-- Reply Form -->
                    <form action="{{ route('admin.emails.send-reply', $email->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Subject -->
                        <div class="mb-3">
                            <label class="form-label">Subjek <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror"
                                   value="{{ old('subject', 'Re: ' . $email->subject) }}" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Body -->
                        <div class="mb-3">
                            <label class="form-label">Pesan <span class="text-danger">*</span></label>
                            <textarea name="body" rows="10" class="form-control @error('body') is-invalid @enderror"
                                      required>{{ old('body') }}</textarea>
                            @error('body')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Tulis balasan Anda di sini</small>
                        </div>

                        <!-- Attachments -->
                        <div class="mb-3">
                            <label class="form-label">Lampiran (Opsional)</label>
                            <input type="file" name="attachments[]" class="form-control @error('attachments.*') is-invalid @enderror"
                                   multiple accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.txt">
                            @error('attachments.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maksimal 10MB per file. Anda bisa upload beberapa file sekaligus.</small>
                        </div>

                        <!-- Original Email Body -->
                        <div class="mb-3">
                            <label class="form-label">Email Asli:</label>
                            <div class="p-3 border rounded bg-light" style="max-height: 300px; overflow-y: auto;">
                                {!! nl2br(e($email->body)) !!}
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.emails.show', $email->id) }}" class="btn btn-secondary me-2">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i> Kirim Balasan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
