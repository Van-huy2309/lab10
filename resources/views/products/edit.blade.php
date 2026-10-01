@extends('layouts.app')

@section('title', 'Sửa sản phẩm')
@section('topbar-title', 'Bảng Quản Trị')

@section('content')
<div class="product-form-page">
    <div class="product-form-header">
        <h1>Sửa Sản Phẩm</h1>
        <a href="{{ route('products.index') }}" class="back-link">← Quay lại</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('products._form', ['product' => $product])
    </form>
</div>
@endsection

@push('styles')
<style>
    .product-form-page { max-width: 900px; margin: 0 auto; }
    .product-form-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; }
    .product-form-header h1 { font-size: 1.75rem; font-weight: 700; color: #111827; margin: 0; }
    .back-link { color: #6b7280; text-decoration: none; font-size: 0.9rem; }
    .back-link:hover { color: #374151; text-decoration: none; }
    .product-card { background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); padding: 28px; margin-bottom: 20px; }
    .section-title { display: flex; align-items: center; gap: 12px; font-size: 1.05rem; font-weight: 700; color: #111827; margin-bottom: 24px; }
    .section-title-row { justify-content: space-between; }
    .step-badge { display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; color: #fff; font-size: 0.85rem; font-weight: 700; flex-shrink: 0; }
    .step-blue { background: #3b82f6; }
    .step-purple { background: #8b5cf6; }
    .field-group { margin-bottom: 18px; }
    .field-group label { display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 6px; }
    .field-input { width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.875rem; color: #111827; background: #fff; }
    .field-input:focus { outline: none; border-color: #8b5cf6; box-shadow: 0 0 0 3px rgba(139,92,246,0.15); }
    .field-textarea { resize: vertical; min-height: 100px; }
    .checkbox-label { display: flex; align-items: center; gap: 8px; font-weight: 500 !important; cursor: pointer; margin-bottom: 0 !important; }
    .checkbox-label input { width: 16px; height: 16px; }
    .preview-img { max-height: 120px; border-radius: 8px; border: 1px solid #e5e7eb; }
    .btn-add-color { background: #8b5cf6; color: #fff; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 600; cursor: pointer; }
    .color-row { border: 1px solid #e5e7eb; border-radius: 10px; padding: 20px; margin-bottom: 16px; background: #fafafa; }
    .color-row-grid { display: grid; grid-template-columns: 1fr 1fr 1fr 1fr 1.2fr auto; gap: 12px; align-items: end; }
    @media (max-width: 992px) { .color-row-grid { grid-template-columns: 1fr 1fr; } }
    .color-hex-group { display: flex; gap: 8px; align-items: center; }
    .color-picker { width: 42px; height: 42px; border: 1px solid #d1d5db; border-radius: 8px; padding: 2px; cursor: pointer; flex-shrink: 0; }
    .hex-input { flex: 1; }
    .color-remove-wrap { display: flex; align-items: flex-end; }
    .btn-remove-color { background: #fee2e2; color: #dc2626; border: none; border-radius: 8px; width: 38px; height: 38px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    .btn-save-product { width: 100%; background: #8b5cf6; color: #fff; border: none; border-radius: 10px; padding: 14px; font-size: 1rem; font-weight: 700; cursor: pointer; margin-top: 4px; }
    .form-footer { text-align: center; color: #9ca3af; font-size: 0.78rem; margin-top: 32px; padding-bottom: 16px; }
</style>
@endpush

@push('scripts')
<script>
let colorIndex = {{ max(count(old('variants', [])), $product->variants->count(), 1) }};

function addColorRow() {
    const container = document.getElementById('colors-container');
    const row = document.createElement('div');
    row.className = 'color-row';
    row.innerHTML = `
        <div class="color-row-grid">
            <div class="field-group"><label>Tên màu</label><input type="text" name="variants[${colorIndex}][color]" class="field-input" placeholder="VD: Vải Đỏ"></div>
            <div class="field-group"><label>Mã màu (Hex)</label><div class="color-hex-group"><input type="color" class="color-picker" value="#ef4444" onchange="syncHex(this)"><input type="text" name="variants[${colorIndex}][color_hex]" class="field-input hex-input" value="#ef4444" oninput="syncPicker(this)"></div></div>
            <div class="field-group"><label>Giá riêng (đ)</label><input type="number" name="variants[${colorIndex}][price]" class="field-input" min="0" placeholder="Giá riêng"></div>
            <div class="field-group"><label>Tồn kho</label><input type="number" name="variants[${colorIndex}][stock]" class="field-input" min="0" value="0"></div>
            <div class="field-group color-remove-wrap"><button type="button" class="btn-remove-color" onclick="removeColorRow(this)"><i class="fas fa-times"></i></button></div>
        </div>`;
    container.appendChild(row);
    colorIndex++;
    updateRemoveButtons();
}

function removeColorRow(btn) { btn.closest('.color-row').remove(); updateRemoveButtons(); }
function updateRemoveButtons() {
    const rows = document.querySelectorAll('.color-row');
    rows.forEach(row => { const btn = row.querySelector('.btn-remove-color'); if (btn) btn.style.display = rows.length <= 1 ? 'none' : ''; });
}
function syncHex(picker) { picker.nextElementSibling.value = picker.value; }
function syncPicker(input) { if (/^#[0-9A-Fa-f]{6}$/.test(input.value)) input.previousElementSibling.value = input.value; }
</script>
@endpush
