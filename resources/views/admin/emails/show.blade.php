@extends('admin.layout.sidebar_admin')

@section('title', 'Detail Email')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Detail Email</h6>
                        <div>
                            <a href="{{ route('admin.emails.index') }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                            <a href="{{ route('admin.emails.reply', $email->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-reply me-1"></i> Balas
                            </a>
                            <form action="{{ route('admin.emails.destroy', $email->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus email ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Email Header -->
                    <div class="border-bottom pb-3 mb-3">
                        <h5 class="mb-3">{{ $email->subject }}</h5>
                        <div class="row">
                            <div class="col-md-8">
                                <p class="mb-1">
                                    <strong>Dari:</strong> {{ $email->from }}
                                </p>
                                <p class="mb-1">
                                    <strong>Tanggal:</strong> {{ $email->created_at->format('d F Y H:i') }}
                                </p>
                                <p class="mb-0">
                                    <strong>Status:</strong>
                                    @if($email->status == 'unread')
                                        <span class="badge bg-gradient-warning">Belum Dibaca</span>
                                    @elseif($email->status == 'read')
                                        <span class="badge bg-gradient-info">Sudah Dibaca</span>
                                    @else
                                        <span class="badge bg-gradient-success">Sudah Dibalas</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Email Body -->
                    <div class="mb-4">
                        <h6 class="mb-2">Isi Email:</h6>
                        <div class="p-3 border rounded bg-light">
                            @if(strip_tags($email->body) != $email->body)
                                {{-- HTML content detected --}}
                                <div style="overflow-x: auto;">
                                    {!! preg_replace('/<img([^>]+)src=["\']([^"\']+)["\']([^>]*)>/i', '<img$1src="$2"$3 style="max-width: 100%; height: auto;">', $email->body) !!}
                                </div>
                            @else
                                {{-- Plain text content - auto convert URLs to clickable links and images --}}
                                @php
                                    $text = e($email->body);
                                    // Convert image URLs to actual images
                                    $text = preg_replace('/(?:^|\s)(https?:\/\/[^\s]+\.(?:jpg|jpeg|png|gif|webp|svg))(?:\s|$)/i', ' <img src="$1" alt="Image" style="max-width: 100%; height: auto; display: block; margin: 10px 0;" /> ', $text);
                                    // Convert remaining URLs to links
                                    $text = preg_replace('/(?:^|\s)(https?:\/\/[^\s]+)(?:\s|$)/i', ' <a href="$1" target="_blank">$1</a> ', $text);
                                    // Apply line breaks
                                    $text = nl2br($text);
                                @endphp
                                {!! $text !!}
                            @endif
                        </div>
                    </div>

                    <!-- Attachments -->
                    @if($email->attachments && count($email->attachments) > 0)
                    <div class="mb-4">
                        <h6 class="mb-2">Lampiran:</h6>
                        <div class="list-group">
                            @foreach($email->attachments as $attachment)
                            <a href="{{ asset($attachment['path']) }}" target="_blank"
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-file me-2"></i>
                                    {{ $attachment['filename'] }}
                                </div>
                                <i class="fas fa-download"></i>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Replies -->
                    @if($email->replies->count() > 0)
                    <div class="mt-4">
                        <h6 class="mb-3">Balasan ({{ $email->replies->count() }})</h6>
                        @foreach($email->replies as $reply)
                        <div class="card mb-3 bg-light">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <strong>Kepada: {{ $reply->to }}</strong>
                                    <small class="text-muted">{{ $reply->created_at->format('d F Y H:i') }}</small>
                                </div>
                                <p class="mb-2"><strong>Subjek:</strong> {{ $reply->subject }}</p>
                                <div class="p-2 border rounded bg-white mb-2">
                                    {!! nl2br(e($reply->body)) !!}
                                </div>
                                @if($reply->attachments && count($reply->attachments) > 0)
                                <div class="mt-2">
                                    <small><strong>Lampiran:</strong></small>
                                    @foreach($reply->attachments as $attachment)
                                        <a href="{{ asset($attachment['path']) }}" target="_blank" class="badge bg-secondary me-1">
                                            <i class="fas fa-paperclip"></i> {{ $attachment['filename'] }}
                                        </a>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
