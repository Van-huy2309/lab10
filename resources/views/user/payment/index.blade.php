@extends('layouts.shop')

@section('title', 'Thanh toán')

@section('content')
<h1 style="font-weight:800;margin-bottom:24px;">Thanh toán đơn hàng</h1>

<div class="checkout-grid">
    <form action="{{ route('user.payment.process') }}" method="POST" class="checkout-card">
        @csrf
        <h3>Thông tin giao hàng</h3>

        <div class="field-group">
            <label class="field-label">Họ tên người nhận</label>
            <input type="text" name="name" class="field-input" value="{{ old('name', auth()->user()->name) }}" required>
        </div>
        <div class="field-group">
            <label class="field-label">Số điện thoại</label>
            <input type="text" name="phone" class="field-input" value="{{ old('phone') }}" placeholder="09xxxxxxxx" pattern="0(3|5|7|8|9)[0-9]{8}" maxlength="10" required>
            <small class="text-muted">10 số, đầu 03/05/07/08/09 (VD: 0987654321). GHN từ chối số giả như 0123456789.</small>
            @error('phone')<div class="field-error">{{ $message }}</div>@enderror
        </div>
        <div class="field-group">
            <label class="field-label">Địa chỉ chi tiết</label>
            <input type="text" name="address" class="field-input" value="{{ old('address') }}" placeholder="Số nhà, tên đường..." required>
        </div>
        <div class="field-group">
            <label class="field-label">Tỉnh / Thành</label>
            <select id="province_select" class="field-input">
                <option value="">-- Đang tải tỉnh/thành... --</option>
            </select>
        </div>
        <div class="field-group">
            <label class="field-label">Quận / Huyện</label>
            <select id="district_select" name="to_district_id" class="field-input" disabled required>
                <option value="">-- Chọn Quận/Huyện --</option>
            </select>
        </div>
        <div class="field-group">
            <label class="field-label">Phường / Xã</label>
            <select id="ward_select" name="to_ward_code" class="field-input" disabled required>
                <option value="">-- Chọn Phường/Xã --</option>
            </select>
        </div>

        <h3 style="margin-top:28px;">Phương thức thanh toán</h3>
        <label class="pay-option"><input type="radio" name="payment_method" value="cod" checked> COD - Thanh toán khi nhận hàng</label>
        <label class="pay-option"><input type="radio" name="payment_method" value="momo"> MoMo (thẻ ATM test)</label>

        <input type="hidden" id="total_price_input" value="{{ (int) $totalPrice }}">
        <button type="submit" class="btn-auth" style="margin-top:20px;">Đặt hàng</button>
    </form>

    <div class="checkout-card">
        <h3>Đơn hàng</h3>
        @foreach ($cart as $item)
            <div class="order-line">
                <div>
                    {{ $item['name'] }} x{{ $item['quantity'] }}
                    @if (!empty($item['color']))<div class="small text-muted">{{ $item['color'] }}</div>@endif
                </div>
                <strong>{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }} đ</strong>
            </div>
        @endforeach
        <hr class="detail-divider">
        <div class="order-line"><span>Tiền hàng</span><strong>{{ number_format($totalPrice, 0, ',', '.') }} đ</strong></div>
        <div class="order-line"><span>Phí vận chuyển</span><strong id="shipping_fee_text">0 VNĐ</strong></div>
        <div class="order-line total-line"><span>Tổng thanh toán</span><strong id="final_total_text">{{ number_format($totalPrice, 0, ',', '.') }} VNĐ</strong></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const provinceSelect = document.getElementById('province_select');
    const districtSelect = document.getElementById('district_select');
    const wardSelect = document.getElementById('ward_select');
    const shippingFeeText = document.getElementById('shipping_fee_text');
    const finalTotalText = document.getElementById('final_total_text');
    const totalPriceInput = document.getElementById('total_price_input');
    const districtsUrl = "{{ route('locations.districts', ['provinceId' => '__PROVINCE__']) }}";
    const wardsUrl = "{{ route('locations.wards', ['districtId' => '__DISTRICT__']) }}";
    const subtotal = parseInt(totalPriceInput ? totalPriceInput.value : 0) || 0;

    fetch("{{ route('locations.provinces') }}", { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
        .then(async res => {
            const type = res.headers.get('content-type') || '';
            if (!type.includes('application/json')) {
                throw new Error('session');
            }
            return res.json();
        })
        .then(res => {
            const list = Array.isArray(res.data) ? res.data : [];
            if (list.length) {
                let options = '<option value="">-- Chọn Tỉnh/Thành --</option>';
                list.forEach(p => {
                    const id = p.ProvinceID ?? p.province_id;
                    const name = p.ProvinceName ?? p.province_name;
                    options += `<option value="${id}">${name}</option>`;
                });
                provinceSelect.innerHTML = options;
                provinceSelect.disabled = false;
            } else {
                console.error('GHN provinces', res);
                provinceSelect.innerHTML = '<option value="">-- Không tải được tỉnh/thành --</option>';
            }
        })
        .catch(() => {
            provinceSelect.innerHTML = '<option value="">-- Lỗi kết nối / phiên hết hạn, tải lại trang --</option>';
        });

    provinceSelect.addEventListener('change', function () {
        districtSelect.innerHTML = '<option value="">-- Đang tải... --</option>';
        districtSelect.disabled = true;
        wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
        wardSelect.disabled = true;
        updateTotals(0);
        if (!this.value) return;
        fetch(districtsUrl.replace('__PROVINCE__', this.value), { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
            .then(res => res.json())
            .then(res => {
                const list = Array.isArray(res.data) ? res.data : [];
                if (list.length) {
                    let options = '<option value="">-- Chọn Quận/Huyện --</option>';
                    list.forEach(d => {
                        const id = d.DistrictID ?? d.district_id;
                        const name = d.DistrictName ?? d.district_name;
                        options += `<option value="${id}">${name}</option>`;
                    });
                    districtSelect.innerHTML = options;
                    districtSelect.disabled = false;
                } else {
                    districtSelect.innerHTML = '<option value="">-- Không tải được quận/huyện --</option>';
                }
            })
            .catch(() => {
                districtSelect.innerHTML = '<option value="">-- Lỗi kết nối GHN --</option>';
            });
    });

    districtSelect.addEventListener('change', function () {
        wardSelect.innerHTML = '<option value="">-- Đang tải... --</option>';
        wardSelect.disabled = true;
        updateTotals(0);
        if (!this.value) return;
        fetch(wardsUrl.replace('__DISTRICT__', this.value), { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
            .then(res => res.json())
            .then(res => {
                const list = Array.isArray(res.data) ? res.data : [];
                if (list.length) {
                    let options = '<option value="">-- Chọn Phường/Xã --</option>';
                    list.forEach(w => {
                        const code = w.WardCode ?? w.ward_code;
                        const name = w.WardName ?? w.ward_name;
                        options += `<option value="${code}">${name}</option>`;
                    });
                    wardSelect.innerHTML = options;
                    wardSelect.disabled = false;
                } else {
                    wardSelect.innerHTML = '<option value="">-- Không tải được phường/xã --</option>';
                }
            })
            .catch(() => {
                wardSelect.innerHTML = '<option value="">-- Lỗi kết nối GHN --</option>';
            });
    });

    wardSelect.addEventListener('change', function () {
        if (!this.value || !districtSelect.value) return;
        shippingFeeText.innerText = 'Đang tính cước...';
        fetch("{{ route('locations.fee') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                to_district_id: districtSelect.value,
                to_ward_code: this.value
            })
        })
        .then(res => res.json())
        .then(res => {
            if (res.code === 200 && res.data) {
                const fee = parseInt(res.data.total) || 0;
                updateTotals(fee);
            } else {
                shippingFeeText.innerText = 'Chưa hỗ trợ';
                updateTotals(0);
            }
        })
        .catch(() => {
            shippingFeeText.innerText = 'Lỗi tính phí';
            updateTotals(0);
        });
    });

    function updateTotals(fee) {
        shippingFeeText.innerText = new Intl.NumberFormat('vi-VN').format(fee) + ' VNĐ';
        const finalAmount = subtotal + fee;
        finalTotalText.innerText = new Intl.NumberFormat('vi-VN').format(finalAmount) + ' VNĐ';
    }
});
</script>
@endpush
