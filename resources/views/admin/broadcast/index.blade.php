@extends('admin.layout.sidebar_admin')

@section('title', 'Broadcast Email Promosi')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Broadcast Email Promosi</h6>
                    <p class="text-sm mb-0">Kirim email promosi produk furniture ke pelanggan</p>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.broadcast.send') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Subject -->
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subjek Email <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror"
                                   id="subject" name="subject" value="{{ old('subject') }}"
                                   placeholder="Contoh: Promo Spesial Furniture 50%!">
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Message -->
                        <div class="mb-3">
                            <label for="message" class="form-label">Pesan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('message') is-invalid @enderror"
                                      id="message" name="message" rows="6"
                                      placeholder="Tulis pesan promosi Anda...">{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Tips: Jelaskan promo atau penawaran spesial Anda dengan menarik</small>
                        </div>

                        <!-- Product Selection -->
                        <div class="mb-3">
                            <label class="form-label">Pilih Produk untuk Dipromosikan (Opsional)</label>
                            <div class="row">
                                @foreach($barangs as $barang)
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body p-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                       name="selected_products[]" value="{{ $barang->id }}"
                                                       id="product_{{ $barang->id }}"
                                                       {{ in_array($barang->id, old('selected_products', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="product_{{ $barang->id }}">
                                                    <div class="d-flex align-items-center">
                                                        @if($barang->gambarBarangs->isNotEmpty())
                                                            <img src="{{ asset($barang->gambarBarangs->first()->gambar_url) }}"
                                                                 alt="{{ $barang->nama_barang }}"
                                                                 class="me-2"
                                                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;"
                                                                 onerror="this.src='{{ asset('img/placeholder-furniture.png') }}'">
                                                        @else
                                                            <img src="{{ asset('img/placeholder-furniture.png') }}"
                                                                 alt="{{ $barang->nama_barang }}"
                                                                 class="me-2"
                                                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                                        @endif
                                                        <div>
                                                            <strong class="d-block">{{ $barang->nama_barang }}</strong>
                                                            <small class="text-success">Rp {{ number_format($barang->harga, 0, ',', '.') }}</small>
                                                            <small class="d-block text-muted">Stok: {{ $barang->stok }}</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Recipients -->
                        <div class="mb-3">
                            <label class="form-label">Penerima <span class="text-danger">*</span></label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="recipients"
                                       id="recipients_all" value="all"
                                       {{ old('recipients', 'all') == 'all' ? 'checked' : '' }}
                                       onchange="toggleCustomerSelection()">
                                <label class="form-check-label" for="recipients_all">
                                    Semua Pelanggan ({{ $customers->count() }} orang)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="recipients"
                                       id="recipients_selected" value="selected"
                                       {{ old('recipients') == 'selected' ? 'checked' : '' }}
                                       onchange="toggleCustomerSelection()">
                                <label class="form-check-label" for="recipients_selected">
                                    Pilih Pelanggan Tertentu
                                </label>
                            </div>
                            @error('recipients')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Customer Selection -->
                        <div id="customer_selection" class="mb-3" style="display: none;">
                            <label class="form-label">Pilih Pelanggan</label>
                            <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                @foreach($customers as $customer)
                                <div class="form-check">
                                    <input class="form-check-input customer-checkbox" type="checkbox"
                                           name="selected_customers[]" value="{{ $customer->id }}"
                                           id="customer_{{ $customer->id }}"
                                           {{ in_array($customer->id, old('selected_customers', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="customer_{{ $customer->id }}">
                                        {{ $customer->nama }} ({{ $customer->email }})
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            <small class="text-muted">
                                <span id="selected_count">0</span> pelanggan dipilih
                            </small>
                            @error('selected_customers')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Attachment -->
                        <div class="mb-3">
                            <label for="attachment" class="form-label">Lampiran (Opsional)</label>
                            <input type="file" class="form-control @error('attachment') is-invalid @enderror"
                                   id="attachment" name="attachment">
                            @error('attachment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Max: 10MB. Format: jpg, png, pdf, docx, dll</small>
                        </div>

                        <!-- Preview Section -->
                        <div class="mb-3">
                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="collapse" data-bs-target="#previewEmail">
                                <i class="fas fa-eye me-1"></i> Preview Email
                            </button>
                        </div>

                        <div class="collapse" id="previewEmail">
                            <div class="card mb-3">
                                <div class="card-body bg-light">
                                    <h6>Preview:</h6>
                                    <div class="border bg-white p-3 rounded">
                                        <p><strong>Kepada:</strong> <span id="preview_recipients">-</span></p>
                                        <p><strong>Subjek:</strong> <span id="preview_subject">-</span></p>
                                        <hr>
                                        <p id="preview_message">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i> Kirim Broadcast
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleCustomerSelection() {
    const selectedRadio = document.querySelector('input[name="recipients"]:checked').value;
    const customerSelection = document.getElementById('customer_selection');

    if (selectedRadio === 'selected') {
        customerSelection.style.display = 'block';
    } else {
        customerSelection.style.display = 'none';
    }

    updatePreview();
}

function updateSelectedCount() {
    const checkboxes = document.querySelectorAll('.customer-checkbox:checked');
    document.getElementById('selected_count').textContent = checkboxes.length;
}

function updatePreview() {
    const subject = document.getElementById('subject').value || '-';
    const message = document.getElementById('message').value || '-';
    const recipientsType = document.querySelector('input[name="recipients"]:checked').value;

    let recipientsText = '';
    if (recipientsType === 'all') {
        recipientsText = 'Semua Pelanggan ({{ $customers->count() }} orang)';
    } else {
        const selectedCount = document.querySelectorAll('.customer-checkbox:checked').length;
        recipientsText = selectedCount + ' pelanggan terpilih';
    }

    document.getElementById('preview_subject').textContent = subject;
    document.getElementById('preview_message').textContent = message;
    document.getElementById('preview_recipients').textContent = recipientsText;
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    toggleCustomerSelection();

    document.querySelectorAll('.customer-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    document.getElementById('subject').addEventListener('input', updatePreview);
    document.getElementById('message').addEventListener('input', updatePreview);
    document.querySelectorAll('input[name="recipients"]').forEach(radio => {
        radio.addEventListener('change', updatePreview);
    });
    document.querySelectorAll('.customer-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updatePreview);
    });

    updateSelectedCount();
    updatePreview();
});
</script>
@endsection
