@extends('layouts.app')

@section('title', 'Tambah Produk Baru — gian games')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-gray-400 mb-6">
        <a href="{{ route('dashboard') }}" class="hover:text-[#0050ae] transition-colors">Home</a>
        <span>/</span>
        <a href="{{ route('products.index') }}" class="hover:text-[#0050ae] transition-colors">Produk Saya</a>
        <span>/</span>
        <span class="text-gray-700 font-semibold">Tambah Produk</span>
    </nav>

    <!-- Page Title -->
    <div class="mb-6 bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
        <h1 class="text-xl font-extrabold text-gray-900 tracking-tight flex items-center gap-2">
            <span>➕</span>
            Tambah Produk / Item Baru
        </h1>
        <p class="text-xs text-gray-500 mt-1">Daftarkan akun, top up, voucher, atau item game untuk mulai berjualan</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-xl bg-red-50 border border-red-200 px-4 py-3.5 text-red-700 text-xs space-y-1 shadow-sm">
            <p class="font-bold text-red-800 mb-1">Periksa kembali data Anda:</p>
            @foreach ($errors->all() as $error)
                <div class="flex items-start gap-1.5">
                    <span>•</span>
                    <span>{{ $error }}</span>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 sm:p-8 space-y-6">

        <form method="POST" action="{{ route('products.store') }}" class="space-y-5" enctype="multipart/form-data">
            @csrf

            <!-- Upload Gambar Produk -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">
                    Foto / Banner Produk
                    <span class="text-gray-400 font-normal lowercase">(opsional)</span>
                </label>

                <!-- Drop zone -->
                <div id="drop-zone"
                     class="relative border-2 border-dashed border-gray-300 rounded-xl p-5 text-center cursor-pointer hover:border-[#0050ae] hover:bg-blue-50/50 transition-all group"
                     onclick="document.getElementById('image').click()">

                    <!-- Preview (hidden by default) -->
                    <div id="image-preview-wrapper" class="hidden mb-3">
                        <img id="image-preview" src="#" alt="Preview"
                             class="mx-auto h-40 w-auto max-w-full object-cover rounded-lg shadow-sm">
                    </div>

                    <!-- Placeholder icon -->
                    <div id="upload-placeholder">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 group-hover:bg-blue-100 text-[#0050ae] flex items-center justify-center mx-auto mb-2 transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p class="text-xs font-bold text-gray-700 group-hover:text-[#0050ae] transition-colors">Klik untuk upload foto / thumbnail produk</p>
                        <p class="text-[11px] text-gray-400 mt-0.5">PNG, JPG, WEBP — maks. 2 MB</p>
                    </div>

                    <input type="file" id="image" name="image" accept="image/*" class="hidden"
                           onchange="previewImage(this)">
                </div>

                <!-- Tombol hapus preview -->
                <button type="button" id="remove-image-btn"
                        class="hidden mt-2 text-xs text-red-500 hover:text-red-700 font-semibold flex items-center gap-1"
                        onclick="removeImage()">
                    ✕ Hapus foto
                </button>

                @error('image')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama Produk -->
            <div>
                <label for="name" class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">
                    Nama Produk Game / Item
                    <span class="text-red-500 ml-0.5">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                       placeholder="Contoh: 140 Diamond Mobile Legends + Bonus"
                       class="w-full border @error('name') border-red-400 bg-red-50 @else border-gray-300 @enderror rounded-xl px-4 py-2.5 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#0050ae] focus:ring-2 focus:ring-[#0050ae]/15 transition-all">
            </div>

            <!-- Pilihan Jenis Pengiriman (2 Jenis Pesanan: Instan vs Biasa) -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">
                    Jenis Pengiriman Pesanan
                    <span class="text-red-500 ml-0.5">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- Option 1: Instan -->
                    <label class="relative flex items-start p-3.5 border-2 rounded-xl cursor-pointer transition-all border-amber-300 bg-amber-50/40 hover:border-amber-400">
                        <input type="radio" name="delivery_type" value="instant" {{ old('delivery_type', 'instant') === 'instant' ? 'checked' : '' }} class="mt-0.5 text-[#0050ae] focus:ring-[#0050ae]">
                        <div class="ml-2.5">
                            <div class="flex items-center gap-1.5">
                                <span class="bg-[#ffb800] text-gray-900 text-[10px] font-black px-1.5 py-0.5 rounded-sm">⚡ INSTAN</span>
                                <span class="text-xs font-bold text-gray-900">Pesanan Instan</span>
                            </div>
                            <p class="text-[11px] text-gray-500 mt-1 leading-snug">
                                Kode voucher/akun otomatis langsung terbit dan pesanan selesai seketika setelah pembayaran.
                            </p>
                        </div>
                    </label>

                    <!-- Option 2: Biasa / Manual -->
                    <label class="relative flex items-start p-3.5 border-2 rounded-xl cursor-pointer transition-all border-blue-200 bg-blue-50/30 hover:border-blue-300">
                        <input type="radio" name="delivery_type" value="regular" {{ old('delivery_type') === 'regular' ? 'checked' : '' }} class="mt-0.5 text-[#0050ae] focus:ring-[#0050ae]">
                        <div class="ml-2.5">
                            <div class="flex items-center gap-1.5">
                                <span class="bg-blue-100 text-[#0050ae] text-[10px] font-black px-1.5 py-0.5 rounded-sm">📦 BIASA</span>
                                <span class="text-xs font-bold text-gray-900">Pesanan Biasa</span>
                            </div>
                            <p class="text-[11px] text-gray-500 mt-1 leading-snug">
                                Penjual memproses pesanan secara manual (joki rank, gift skin, top up via User ID/Server).
                            </p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Harga & Stok side by side -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="price" class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">
                        Harga Jual (Rp)
                        <span class="text-red-500 ml-0.5">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-400 text-xs font-bold">Rp</span>
                        </div>
                        <input type="number" id="price" name="price" value="{{ old('price') }}" required step="100" min="0"
                               placeholder="0"
                               class="w-full border @error('price') border-red-400 bg-red-50 @else border-gray-300 @enderror rounded-xl pl-9 pr-3 py-2.5 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#0050ae] focus:ring-2 focus:ring-[#0050ae]/15 transition-all">
                    </div>
                </div>

                <div>
                    <label for="stock" class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">
                        Jumlah Stok (Unit)
                        <span class="text-red-500 ml-0.5">*</span>
                    </label>
                    <input type="number" id="stock" name="stock" value="{{ old('stock', 0) }}" required min="0"
                           placeholder="0"
                           class="w-full border @error('stock') border-red-400 bg-red-50 @else border-gray-300 @enderror rounded-xl px-3 py-2.5 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#0050ae] focus:ring-2 focus:ring-[#0050ae]/15 transition-all">
                </div>
            </div>

            <!-- Tips Banner -->
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-3.5 flex items-start gap-2.5 text-xs text-[#0050ae]">
                <span>💡</span>
                <div>
                    <span class="font-bold">Tips Seller:</span>
                    <span class="text-gray-600">Pastikan stok akurat agar pesanan pembeli dapat diproses dengan cepat (Instant Delivery).</span>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-3 pt-3 border-t border-gray-100">
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-[#0050ae] hover:bg-[#003d87] text-white font-bold px-6 py-2.5 rounded-xl shadow transition-all text-xs active:scale-[0.99]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Produk
                </button>
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center justify-center px-4 py-2.5 text-xs font-semibold text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-xl transition-all">
                    Batal
                </a>
            </div>
        </form>

    </div>

</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('image-preview');
            const previewWrapper = document.getElementById('image-preview-wrapper');
            const placeholder = document.getElementById('upload-placeholder');
            const removeBtn = document.getElementById('remove-image-btn');

            preview.src = e.target.result;
            previewWrapper.classList.remove('hidden');
            placeholder.classList.add('hidden');
            removeBtn.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removeImage() {
    const input = document.getElementById('image');
    const preview = document.getElementById('image-preview');
    const previewWrapper = document.getElementById('image-preview-wrapper');
    const placeholder = document.getElementById('upload-placeholder');
    const removeBtn = document.getElementById('remove-image-btn');

    input.value = '';
    preview.src = '#';
    previewWrapper.classList.add('hidden');
    placeholder.classList.remove('hidden');
    removeBtn.classList.add('hidden');
}

// Drag & drop
const dropZone = document.getElementById('drop-zone');
dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('border-[#0050ae]', 'bg-blue-50/50');
});
dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('border-[#0050ae]', 'bg-blue-50/50');
});
dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-[#0050ae]', 'bg-blue-50/50');
    const input = document.getElementById('image');
    input.files = e.dataTransfer.files;
    previewImage(input);
});
</script>
@endsection
