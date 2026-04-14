@extends('layouts.quan-tri')
@section('title', 'Lịch Khởi Hành: ' . $tour->ten_tour . ' - VietGo Admin')
@section('page-title', 'Lịch Khởi Hành')

@section('css')
<style>
.lkh-wrap { display: grid; grid-template-columns: 1fr 400px; gap: 2rem; align-items: start; }
@media(max-width:1100px){ .lkh-wrap { grid-template-columns: 1fr; } }
@media(max-width:768px){
    .lkh-wrap { gap: 1rem; }
    .add-form-card { position: static !important; }
    .f-grid { grid-template-columns: 1fr !important; }
}

/* Add form */
.add-form-card {
    background: white; border-radius: 20px; overflow: hidden;
    border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(15,23,42,.07);
    position: sticky; top: 1.5rem;
}
.add-form-head {
    padding: 1.25rem 1.5rem;
    background: linear-gradient(135deg, #0f172a, #065f46);
    color: white; display: flex; align-items: center; gap: .75rem;
}
.add-form-icon { font-size: 1.25rem; }
.add-form-body { padding: 1.5rem; display: flex; flex-direction: column; gap: 1rem; }

.f-group { display: flex; flex-direction: column; gap: .4rem; }
.f-label { font-size: .75rem; font-weight: 800; color: #374151; text-transform: uppercase; letter-spacing: .05em; }
.f-label span { color: #ef4444; }
.f-input {
    padding: .75rem 1rem; border: 2px solid #e2e8f0; border-radius: 10px;
    font-family: inherit; font-size: .875rem; outline: none; background: #f8fafc;
    transition: border-color .25s, box-shadow .25s; color: #0f172a; width: 100%;
}
.f-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(5,150,105,.1); background: white; }
.f-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; }

.btn-add-lkh {
    width: 100%; padding: .9rem; border: none; border-radius: 12px;
    background: linear-gradient(135deg, var(--primary), #059669);
    color: white; font-weight: 800; font-size: .9rem; cursor: pointer;
    font-family: inherit; transition: all .3s;
    display: flex; align-items: center; justify-content: center; gap: .6rem;
    box-shadow: 0 4px 14px rgba(5,150,105,.25);
}
.btn-add-lkh:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(5,150,105,.35); }

/* Table */
.lkh-table-card {
    background: white; border-radius: 20px; overflow: hidden;
    border: 1px solid #e2e8f0; box-shadow: 0 2px 12px rgba(15,23,42,.06);
}
.lkh-table-head {
    padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: .75rem;
}
.lkh-count { font-size: .8rem; color: #64748b; font-weight: 600; }

/* Status badges */
.lkh-badge { display: inline-flex; align-items: center; gap: .35rem; padding: .3rem .85rem; border-radius: 999px; font-size: .78rem; font-weight: 800; }
.lkh-con_cho { background: #dcfce7; color: #15803d; }
.lkh-het_cho { background: #fee2e2; color: #991b1b; }
.lkh-huy { background: #f1f5f9; color: #64748b; }

/* Inline edit row */
.edit-row { background: #f0fdf4; }
.edit-row td { padding: .75rem .65rem !important; }
</style>
@endsection

@section('content')
{{-- Alerts --}}
@if(session('thanh_cong'))
<div class="alert alert-success mb-4" style="background:#ecfdf5;border:1px solid #86efac;color:#065f46;padding:.9rem 1.25rem;border-radius:12px;display:flex;gap:.75rem;align-items:center;font-weight:700;">
    <i class="fa-solid fa-circle-check"></i> {{ session('thanh_cong') }}
</div>
@endif
@if(session('loi') || $errors->any())
<div class="alert mb-4" style="background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;padding:.9rem 1.25rem;border-radius:12px;font-weight:700;">
    <i class="fa-solid fa-circle-exclamation mr-2"></i>
    {{ session('loi') }}
    @foreach($errors->all() as $e) <div>{{ $e }}</div> @endforeach
</div>
@endif

{{-- Back + breadcrumb --}}
<div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1.5rem;">
    <a href="{{ route('quan-tri.tour.danh-sach') }}" style="width:36px;height:36px;border-radius:10px;background:white;border:1.5px solid #e2e8f0;display:flex;align-items:center;justify-content:center;color:#374151;text-decoration:none;">
        <i class="fa-solid fa-arrow-left" style="font-size:.85rem;"></i>
    </a>
    <div>
        <div style="font-size:.75rem;color:#64748b;font-weight:600;">Quản lý Tour</div>
        <h1 style="font-size:1.1rem;font-weight:900;color:#0f172a;margin:0;">{{ $tour->ten_tour }}</h1>
    </div>
    <div style="margin-left:auto;display:flex;gap:.5rem;">
        <a href="{{ route('quan-tri.tour.chinh-sua', $tour) }}" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-pen"></i> Sửa tour
        </a>
    </div>
</div>

{{-- Tour mini card --}}
<div style="display:flex;align-items:center;gap:1rem;padding:1rem 1.25rem;background:white;border-radius:16px;border:1px solid #e2e8f0;margin-bottom:1.75rem;box-shadow:0 2px 10px rgba(15,23,42,.05);">
    <img src="{{ $tour->hinh_bia_url }}" style="width:70px;height:55px;object-fit:cover;border-radius:10px;flex-shrink:0;">
    <div style="flex:1;">
        <div style="font-weight:700;color:#0f172a;font-size:.9rem;margin-bottom:.25rem;">{{ $tour->ten_tour }}</div>
        <div style="display:flex;gap:1rem;font-size:.78rem;color:#64748b;">
            <span><i class="fa-solid fa-location-dot mr-1" style="color:var(--primary);"></i>{{ $tour->diemDen->ten_diem_den }}</span>
            <span><i class="fa-regular fa-clock mr-1" style="color:var(--primary);"></i>{{ $tour->so_ngay }}N{{ $tour->so_dem }}Đ</span>
            <span><i class="fa-solid fa-tag mr-1" style="color:var(--primary);"></i>{{ $tour->gia_nguoi_lon_dinh_dang }}/NL</span>
        </div>
    </div>
    <span class="badge badge-{{ $tour->trang_thai === 'hoat_dong' ? 'success' : 'danger' }}">
        {{ $tour->trang_thai === 'hoat_dong' ? 'Hoạt động' : 'Ngừng' }}
    </span>
</div>

<div class="lkh-wrap">

    {{-- ═══ CỘT TRÁI: DANH SÁCH ═══ --}}
    <div>
        <div class="lkh-table-card">
            <div class="lkh-table-head">
                <div>
                    <h3 style="font-size:1rem;font-weight:900;color:#0f172a;margin:0 0 .15rem;">Danh Sách Lịch Khởi Hành</h3>
                    <span class="lkh-count">{{ $tour->lichKhoiHanh->count() }} lịch — {{ $tour->lichKhoiHanh->where('trang_thai','con_cho')->count() }} còn chỗ</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table" style="min-width:750px;">
                    <thead>
                        <tr>
                            <th>Ngày đi</th>
                            <th>Ngày về</th>
                            <th style="text-align:center;">Chỗ tối đa</th>
                            <th style="text-align:center;">Còn lại</th>
                            <th>Giá NL</th>
                            <th style="text-align:center;">Trạng thái</th>
                            <th style="text-align:center;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tour->lichKhoiHanh->sortBy('ngay_di') as $lich)
                        {{-- Normal row --}}
                        <tr id="row-{{ $lich->id }}">
                            <td style="font-weight:700;color:#0f172a;">{{ $lich->ngay_di->format('d/m/Y') }}</td>
                            <td style="font-weight:600;color:#374151;">{{ $lich->ngay_ve->format('d/m/Y') }}</td>
                            <td style="text-align:center;">{{ $lich->so_cho_toi_da }}</td>
                            <td style="text-align:center;">
                                <span style="font-weight:800;color:{{ $lich->so_cho_con > 0 ? '#059669' : '#ef4444' }};">
                                    {{ $lich->so_cho_con }}
                                </span>
                            </td>
                            <td style="font-weight:600;">{{ number_format($lich->gia_nguoi_lon_hien_tai, 0, ',', '.') }}đ</td>
                            <td style="text-align:center;">
                                <span class="lkh-badge lkh-{{ $lich->trang_thai }}">
                                    @if($lich->trang_thai === 'con_cho') ✅ Còn chỗ
                                    @elseif($lich->trang_thai === 'het_cho') ❌ Hết chỗ
                                    @else 🚫 Hủy @endif
                                </span>
                            </td>
                            <td style="text-align:center;">
                                <div style="display:flex;gap:.4rem;justify-content:center;">
                                    <button type="button" onclick="toggleEdit({{ $lich->id }})"
                                        class="btn btn-info btn-sm btn-icon" title="Sửa">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <form action="{{ route('quan-tri.lich-khoi-hanh.xoa', [$tour, $lich]) }}" method="POST"
                                        onsubmit="return confirm('Xóa lịch khởi hành này?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Xóa">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        {{-- Edit row (hidden) --}}
                        <tr id="edit-{{ $lich->id }}" class="edit-row" style="display:none;">
                            <td colspan="7">
                                <form action="{{ route('quan-tri.lich-khoi-hanh.cap-nhat', [$tour, $lich]) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(130px,1fr));gap:.65rem;align-items:end;">
                                        <div class="f-group">
                                            <label class="f-label">Ngày đi</label>
                                            <input type="date" name="ngay_di" class="f-input" value="{{ $lich->ngay_di->format('Y-m-d') }}" required>
                                        </div>
                                        <div class="f-group">
                                            <label class="f-label">Ngày về</label>
                                            <input type="date" name="ngay_ve" class="f-input" value="{{ $lich->ngay_ve->format('Y-m-d') }}" required>
                                        </div>
                                        <div class="f-group">
                                            <label class="f-label">Chỗ tối đa</label>
                                            <input type="number" name="so_cho_toi_da" class="f-input" value="{{ $lich->so_cho_toi_da }}" min="1" required>
                                        </div>
                                        <div class="f-group">
                                            <label class="f-label">Còn lại</label>
                                            <input type="number" name="so_cho_con" class="f-input" value="{{ $lich->so_cho_con }}" min="0">
                                        </div>
                                        <div class="f-group">
                                            <label class="f-label">Giá NL (đ)</label>
                                            <input type="number" name="gia_nguoi_lon" class="f-input" value="{{ $lich->gia_nguoi_lon }}" min="0">
                                        </div>
                                        <div class="f-group">
                                            <label class="f-label">Giá TE (đ)</label>
                                            <input type="number" name="gia_tre_em" class="f-input" value="{{ $lich->gia_tre_em }}" min="0">
                                        </div>
                                        <div class="f-group">
                                            <label class="f-label">Trạng thái</label>
                                            <select name="trang_thai" class="f-input">
                                                <option value="con_cho" {{ $lich->trang_thai === 'con_cho' ? 'selected' : '' }}>Còn chỗ</option>
                                                <option value="het_cho" {{ $lich->trang_thai === 'het_cho' ? 'selected' : '' }}>Hết chỗ</option>
                                                <option value="huy" {{ $lich->trang_thai === 'huy' ? 'selected' : '' }}>Hủy</option>
                                            </select>
                                        </div>
                                        <div style="display:flex;gap:.4rem;">
                                            <button type="submit" class="btn btn-primary btn-sm" style="flex:1;">
                                                <i class="fa-solid fa-save"></i> Lưu
                                            </button>
                                            <button type="button" onclick="toggleEdit({{ $lich->id }})" class="btn btn-secondary btn-sm">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align:center;padding:3rem;color:#94a3b8;">
                                <div style="font-size:2.5rem;margin-bottom:.75rem;">📅</div>
                                <div style="font-weight:700;color:#374151;margin-bottom:.35rem;">Chưa có lịch khởi hành nào</div>
                                <div style="font-size:.82rem;">Thêm lịch từ form bên phải để bắt đầu nhận đặt tour</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ═══ CỘT PHẢI: FORM THÊM ═══ --}}
    <div>
        <div class="add-form-card">
            <div class="add-form-head">
                <div class="add-form-icon">➕</div>
                <div>
                    <div style="font-weight:800;font-size:1rem;">Thêm Lịch Khởi Hành</div>
                    <div style="font-size:.78rem;color:rgba(255,255,255,.65);">Tạo lịch mới cho tour</div>
                </div>
            </div>
            <form action="{{ route('quan-tri.lich-khoi-hanh.luu', $tour) }}" method="POST" class="add-form-body">
                @csrf

                <div class="f-group">
                    <label class="f-label">Ngày Khởi Hành <span>*</span></label>
                    <input type="date" name="ngay_di" class="f-input" value="{{ old('ngay_di', date('Y-m-d', strtotime('+3 days'))) }}" required>
                </div>

                <div class="f-group">
                    <label class="f-label">Ngày Về <span>*</span></label>
                    <input type="date" name="ngay_ve" class="f-input" value="{{ old('ngay_ve', date('Y-m-d', strtotime('+' . ($tour->so_ngay + 3) . ' days'))) }}" required>
                </div>

                <div class="f-grid">
                    <div class="f-group">
                        <label class="f-label">Số Chỗ <span>*</span></label>
                        <input type="number" name="so_cho_toi_da" class="f-input" value="{{ old('so_cho_toi_da', 20) }}" min="1" required>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Trạng Thái</label>
                        <select name="trang_thai" class="f-input">
                            <option value="con_cho">✅ Còn chỗ</option>
                            <option value="het_cho">❌ Hết chỗ</option>
                            <option value="huy">🚫 Hủy</option>
                        </select>
                    </div>
                </div>

                <div style="padding:.8rem 1rem;background:#f0fdf4;border-radius:10px;font-size:.78rem;color:#065f46;font-weight:600;">
                    <div style="margin-bottom:.35rem;">💡 Giá mặc định từ tour:</div>
                    <div>NL: <strong>{{ $tour->gia_nguoi_lon_dinh_dang }}</strong> — TE: <strong>{{ $tour->gia_tre_em_dinh_dang ?? 'Không có' }}</strong></div>
                    <div style="color:#94a3b8;font-size:.72rem;margin-top:.2rem;">Để trống để dùng giá mặc định</div>
                </div>

                <div class="f-grid">
                    <div class="f-group">
                        <label class="f-label">Giá NL riêng (đ)</label>
                        <input type="number" name="gia_nguoi_lon" class="f-input" value="{{ old('gia_nguoi_lon') }}" min="0" placeholder="{{ $tour->gia_nguoi_lon }}">
                    </div>
                    <div class="f-group">
                        <label class="f-label">Giá TE riêng (đ)</label>
                        <input type="number" name="gia_tre_em" class="f-input" value="{{ old('gia_tre_em') }}" min="0" placeholder="{{ $tour->gia_tre_em ?? '0' }}">
                    </div>
                </div>

                <button type="submit" class="btn-add-lkh">
                    <i class="fa-solid fa-plus"></i> Thêm Lịch Khởi Hành
                </button>
            </form>
        </div>
    </div>

</div>
@endsection

@section('js')
<script>
function toggleEdit(id) {
    const row = document.getElementById('row-' + id);
    const editRow = document.getElementById('edit-' + id);
    const isOpen = editRow.style.display !== 'none';
    editRow.style.display = isOpen ? 'none' : 'table-row';
    row.style.opacity = isOpen ? '1' : '0.5';
}

// Auto calc ngay_ve based on so_ngay tour
const soNgay = {{ $tour->so_ngay }};
const ngayDiInput = document.querySelector('form[action*="luu"] input[name=ngay_di]');
if (ngayDiInput) {
    ngayDiInput.addEventListener('change', function() {
        const ngayVeInput = document.querySelector('form[action*="luu"] input[name=ngay_ve]');
        if (ngayVeInput && this.value) {
            const d = new Date(this.value);
            d.setDate(d.getDate() + soNgay - 1);
            ngayVeInput.value = d.toISOString().split('T')[0];
        }
    });
}
</script>
@endsection
