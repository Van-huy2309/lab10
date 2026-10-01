@php
    $isEdit = isset($product) && $product !== null;
    $oldVariants = old('variants', ($isEdit && $product->variants->count())
        ? $product->variants->map(fn($v) => [
            'color' => $v->color,
            'color_hex' => $v->color_hex,
            'price' => $v->price,
            'stock' => $v->stock,
        ])->toArray()
        : []);
    if (empty($oldVariants)) {
        $oldVariants = [['color' => '', 'color_hex' => '#ef4444', 'price' => '', 'stock' => 0]];
    }
@endphp

{{-- Phần 1: Thông tin cơ bản --}}
<div class="product-card">
    <div class="section-title">
        <span class="step-badge step-blue">1</span>
        <span>Thông tin cơ bản</span>
    </div>

    <div class="field-group">
        <label>Tên sản phẩm</label>
        <input type="text" name="name" class="field-input"
               value="{{ old('name', $isEdit ? $product->name : '') }}"
               placeholder="Nhập tên sản phẩm" required>
    </div>

    <div class="field-group">
        <label>Danh mục</label>
        <select name="category_id" class="field-input" required>
            <option value="">-- Chọn danh mục --</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    {{ old('category_id', $isEdit ? $product->category_id : '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="field-group">
        <label>Giá bán thị gốc (đ)</label>
        <input type="number" name="original_price" class="field-input" min="0" step="1000"
               value="{{ old('original_price', $isEdit ? $product->original_price : 0) }}"
               placeholder="0" required>
    </div>

    <div class="field-group">
        <label>Ảnh đại diện chính</label>
        @if ($isEdit && $product->imageUrl())
            <div class="mb-2">
                <img src="{{ $product->imageUrl() }}" alt="" class="preview-img">
            </div>
        @endif
        <input type="file" name="image" class="field-input" accept="image/*">
    </div>

    <div class="field-group">
        <label>Mô tả sản phẩm</label>
        <textarea name="description" class="field-input field-textarea" rows="4"
                  placeholder="Nhập mô tả sản phẩm...">{{ old('description', $isEdit ? $product->description : '') }}</textarea>
    </div>

    <div class="field-group">
        <label class="checkbox-label">
            <input type="checkbox" name="is_active" value="1"
                {{ old('is_active', $isEdit ? $product->is_active : true) ? 'checked' : '' }}>
            Hiển thị bán ngay
        </label>
    </div>
</div>

{{-- Phần 2: Phân màu sản phẩm --}}
<div class="product-card">
    <div class="section-title section-title-row">
        <div class="d-flex align-items-center">
            <span class="step-badge step-purple">2</span>
            <span>Phân màu sản phẩm</span>
        </div>
        <button type="button" class="btn-add-color" onclick="addColorRow()">
            + Thêm màu mới
        </button>
    </div>

    <div id="colors-container">
        @foreach ($oldVariants as $index => $variant)
            <div class="color-row" data-index="{{ $index }}">
                <div class="color-row-grid">
                    <div class="field-group">
                        <label>Tên màu</label>
                        <input type="text" name="variants[{{ $index }}][color]" class="field-input"
                               value="{{ $variant['color'] ?? '' }}" placeholder="VD: Vải Đỏ">
                    </div>
                    <div class="field-group">
                        <label>Mã màu (Hex)</label>
                        <div class="color-hex-group">
                            <input type="color" class="color-picker"
                                   value="{{ $variant['color_hex'] ?? '#ef4444' }}"
                                   onchange="syncHex(this)">
                            <input type="text" name="variants[{{ $index }}][color_hex]" class="field-input hex-input"
                                   value="{{ $variant['color_hex'] ?? '#ef4444' }}"
                                   oninput="syncPicker(this)">
                        </div>
                    </div>
                    <div class="field-group">
                        <label>Giá riêng (đ)</label>
                        <input type="number" name="variants[{{ $index }}][price]" class="field-input" min="0"
                               value="{{ $variant['price'] ?? '' }}" placeholder="Giá riêng">
                    </div>
                    <div class="field-group">
                        <label>Tồn kho</label>
                        <input type="number" name="variants[{{ $index }}][stock]" class="field-input" min="0"
                               value="{{ $variant['stock'] ?? 0 }}">
                    </div>
                    <div class="field-group color-remove-wrap">
                        <button type="button" class="btn-remove-color" onclick="removeColorRow(this)"
                            {{ count($oldVariants) <= 1 ? 'style=display:none' : '' }}>
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<button type="submit" class="btn-save-product">Lưu Sản Phẩm</button>

<div class="form-footer">
    © 2024 E-Commerce Management System. Built with Laravel &amp; Tailwind CSS.
</div>
