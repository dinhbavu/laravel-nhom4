@extends('layouts.3d-app')
@section('title', 'Đa Vũ Trụ Tour - VietGo 3D')

@section('css')
<style>
/* ── Glass Filter panel ── */
.filter-panel {
    background: rgba(255, 255, 255, 0.04);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border-radius: 20px;
    padding: 1.5rem;
    border: 1px solid rgba(255,255,255,0.06);
    margin-bottom: 2rem;
    margin-top: 100px;
}
.filter-title {
    display: flex;
    align-items: center;
    gap: .75rem;
    font-family: 'Outfit', sans-serif;
    font-size: 1.2rem;
    font-weight: 800;
    color: #4ade80;
    margin-bottom: 1.25rem;
}
.filter-title .filter-icon {
    width: 2.25rem;
    height: 2.25rem;
    border-radius: .625rem;
    background: rgba(74,222,128,0.1);
    color: #4ade80;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}
.filter-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem; }
@media(max-width:900px) { .filter-grid { grid-template-columns: 1fr 1fr; } }
@media(max-width:600px) { .filter-grid { grid-template-columns: 1fr; } }

.filter-label { font-size: .75rem; font-weight: 700; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: .04em; margin-bottom: .35rem; }
.filter-input {
    width: 100%;
    padding: .6rem 1rem;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 10px;
    color: #e2e8f0;
    font-size: .875rem;
    font-weight: 500;
    transition: all .25s;
    outline: none;
    font-family: inherit;
    backdrop-filter: blur(5px);
    appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%234ade80'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 1rem;
    padding-right: 2.5rem;
}
.filter-input:focus { background: rgba(255,255,255,0.08); border-color: #4ade80; box-shadow: 0 0 15px rgba(74,222,128,0.15); }
.filter-input option { background: #1e293b; color: #e2e8f0; padding: 10px; }
.filter-input optgroup { background: #1e293b; color: #4ade80; font-weight: 800; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px; }

.filter-bottom { display: flex; align-items: flex-end; justify-content: space-between; gap: 1.25rem; padding-top: 1.1rem; border-top: 1px solid rgba(255,255,255,0.06); }
@media(max-width:600px) { .filter-bottom { flex-direction: column; align-items: stretch; } }
.price-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; flex: 1; }

.btn-filter {
    display: inline-flex; align-items: center; justify-content: center; gap: .5rem;
    padding: .65rem 1.6rem;
    background: linear-gradient(135deg, #4ade80, #10b981);
    color: #0f172a;
    border: none;
    border-radius: 10px;
    font-weight: 700;
    font-size: .875rem;
    cursor: pointer;
    transition: all .25s;
    white-space: nowrap;
    text-transform: uppercase;
    box-shadow: 0 5px 15px rgba(74,222,128,0.3);
}
.btn-filter:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(74,222,128,0.5); }

/* ── 3D Floating Tour Cards - Modern V2 ── */
.tour-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2.5rem; position: relative; z-index: 10; }
@media(max-width:1000px) { .tour-grid { grid-template-columns: repeat(2, 1fr); gap: 2rem; } }
@media(max-width:650px) { .tour-grid { grid-template-columns: 1fr; } }

.tour-card-3d {
    background: rgba(15, 23, 42, 0.4);
    backdrop-filter: blur(25px);
    -webkit-backdrop-filter: blur(25px);
    border-radius: 24px;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border: 1px solid rgba(255, 255, 255, 0.08);
    display: flex;
    flex-direction: column;
    height: 100%;
    position: relative;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.tour-card-3d::before {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: 24px;
    padding: 2px;
    background: linear-gradient(135deg, rgba(74,222,128,0.5), rgba(74,222,128,0) 50%, rgba(255,255,255,0.05));
    -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor;
    mask-composite: exclude;
    opacity: 0;
    transition: opacity 0.4s ease;
    z-index: 5;
    pointer-events: none;
}

.tour-card-3d:hover {
    transform: translateY(-12px) scale(1.02);
    box-shadow: 0 25px 50px rgba(0,0,0,0.4), 0 0 40px rgba(74,222,128,0.15);
    background: rgba(15, 23, 42, 0.7);
    border-color: transparent;
}

.tour-card-3d:hover::before {
    opacity: 1;
}

.tour-thumb { 
    position: relative; 
    overflow: hidden; 
    aspect-ratio: 4/3; 
    border-radius: 20px; 
    margin: 8px 8px 0 8px; /* Inner spacing wrapper */
}
.tour-thumb img { 
    width: 100%; 
    height: 100%; 
    object-fit: cover; 
    transition: transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94); 
}
.tour-card-3d:hover .tour-thumb img { 
    transform: scale(1.1) rotate(1deg); 
}
.tour-thumb-overlay { 
    position: absolute; 
    inset: 0; 
    background: linear-gradient(to top, rgba(15,23,42,0.9) 0%, rgba(15,23,42,0) 50%); 
}

.thumb-badge {
    position: absolute; top: 0.75rem; left: 0.75rem;
    backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2); color: white;
    font-size: .65rem; font-weight: 800; padding: .4rem .8rem; border-radius: 99px;
    letter-spacing: .06em; text-transform: uppercase;
    z-index: 2;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
}
.thumb-badge.popular { background: linear-gradient(135deg, #ef4444, #f43f5e); box-shadow: 0 5px 15px rgba(239,68,68,0.4); }
.thumb-badge.new { background: linear-gradient(135deg, #3b82f6, #06b6d4); box-shadow: 0 5px 15px rgba(59,130,246,0.4); }

.tour-content-3d { 
    padding: 1.25rem 1.5rem; 
    flex: 1; 
    display: flex; 
    flex-direction: column; 
    position: relative;
    z-index: 2;
}
.tour-meta-3d { 
    display: flex; 
    align-items: center; 
    gap: 0.75rem; 
    margin-bottom: 1rem; 
    flex-wrap: wrap; 
}
.meta-chip { 
    display: flex; 
    align-items: center; 
    gap: .4rem; 
    font-size: .75rem; 
    color: #cbd5e1; 
    font-weight: 600; 
    background: rgba(255,255,255,0.05);
    padding: 6px 12px;
    border-radius: 8px;
    border: 1px solid rgba(255,255,255,0.05);
}
.meta-chip i { color: #4ade80; font-size: .85rem; }

.tour-title-3d {
    font-family: 'Outfit', sans-serif; font-size: 1.25rem; font-weight: 800;
    color: #f8fafc; line-height: 1.4; margin-bottom: 1rem;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    transition: color .3s;
}
.tour-card-3d:hover .tour-title-3d { color: #4ade80; }

.tour-footer-3d {
    margin-top: auto; 
    display: flex; 
    align-items: center; 
    justify-content: space-between;
    padding-top: 1.25rem; 
    border-top: 1px dashed rgba(255,255,255,0.1);
}

.price-label { font-size:0.75rem; color:#94a3b8; text-transform:uppercase; margin-bottom:4px; font-weight: 700; letter-spacing: 0.05em; }
.price-val-3d { font-family: 'Outfit', sans-serif; font-size: 1.35rem; font-weight: 900; color: #4ade80; text-shadow: 0 0 20px rgba(74,222,128,0.2); }

.arrow-btn {
    width: 2.75rem; height: 2.75rem; border-radius: 50%;
    background: rgba(74,222,128,0.15); border: 1px solid rgba(74,222,128,0.3);
    display: flex; align-items: center; justify-content: center;
    color: #4ade80; transition: all .4s cubic-bezier(0.175,0.885,0.32,1.275);
    font-size: 1.1rem;
}
.tour-card-3d:hover .arrow-btn { 
    background: linear-gradient(135deg, #4ade80, #10b981); 
    border-color: #4ade80; 
    color: #0f172a; 
    transform: scale(1.1) rotate(45deg); 
    box-shadow: 0 5px 20px rgba(74,222,128,0.5); 
}


/* ── Custom Glass Dropdown ── */
.custom-select-wrapper { position: relative; width: 100%; }
.select-trigger {
    width: 100%;
    padding: .6rem 1rem;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 10px;
    color: #e2e8f0;
    font-size: .875rem;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all .25s;
    backdrop-filter: blur(5px);
}
.select-trigger:hover, .select-trigger.active { background: rgba(255,255,255,0.08); border-color: #4ade80; }
.select-trigger::after {
    content: '';
    width: 0.8rem; height: 0.8rem;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%234ade80'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
    background-size: contain; background-repeat: no-repeat;
    transition: transform .3s;
}
.select-trigger.active::after { transform: rotate(180deg); }

.select-options {
    position: absolute;
    top: calc(100% + 8px);
    left: 0; right: 0;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 12px;
    padding: 8px;
    z-index: 1000;
    max-height: 300px;
    overflow-y: auto;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all .3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 15px 35px rgba(0,0,0,0.5);
}
.select-options.show { opacity: 1; visibility: visible; transform: translateY(0); }
.select-options::-webkit-scrollbar { width: 5px; }
.select-options::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }

.select-option {
    padding: 10px 14px;
    border-radius: 8px;
    color: #e2e8f0;
    font-size: .875rem;
    cursor: pointer;
    transition: all .2s;
}
.select-option:hover { background: rgba(74, 222, 128, 0.1); color: #4ade80; }
.select-option.selected { background: rgba(74, 222, 128, 0.2); color: #4ade80; font-weight: 700; }

.select-group-label {
    padding: 12px 14px 6px;
    font-size: .65rem;
    font-weight: 800;
    color: #4ade80;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    opacity: 0.8;
}

/* Hide original selects but keep them for form submission */
.hidden-select { display: none !important; }

</style>
@endsection

@section('content')

<div class="container pb-20" style="position:relative; z-index:10; max-width: 1280px; margin: 0 auto; padding: 0 20px 100px 20px;">

    <!-- Filter Panel -->
    <div class="filter-panel">
        <div class="filter-title">
            <span class="filter-icon"><i class="fa-solid fa-sliders"></i></span>
            Bộ Lọc Tìm Kiếm Tour
        </div>

        <form action="{{ route('tour.danh-sach') }}" method="GET">
            <div class="filter-grid">
                <div>
                    <div class="filter-label">Bạn muốn đi đâu?</div>
                    <input type="text" name="tu_khoa" value="{{ request('tu_khoa') }}" class="filter-input" placeholder="Tên tour, địa danh...">
                </div>
                <div>
                    <div class="filter-label">Điểm đến</div>
                    <div class="custom-select-wrapper">
                        <select name="diem_den" class="hidden-select" id="diem_den_select">
                            <option value="">Tất cả điểm đến</option>
                            @foreach($danh_sach_diem_den->groupBy('vung_mien_ten') as $mien => $diem_dens)
                            <optgroup label="{{ $mien ?: 'Khác' }}">
                                @foreach($diem_dens as $dd)
                                <option value="{{ $dd->id }}" {{ request('diem_den') == $dd->id ? 'selected' : '' }}>{{ $dd->ten_diem_den }}</option>
                                @endforeach
                            </optgroup>
                            @endforeach
                        </select>
                        <div class="select-trigger" id="diem_den_trigger"><span>Tất cả điểm đến</span></div>
                        <div class="select-options" id="diem_den_options">
                            <div class="select-option" data-value="">Tất cả điểm đến</div>
                            @foreach($danh_sach_diem_den->groupBy('vung_mien_ten') as $mien => $diem_dens)
                                <div class="select-group-label">{{ $mien ?: 'Khác' }}</div>
                                @foreach($diem_dens as $dd)
                                    <div class="select-option {{ request('diem_den') == $dd->id ? 'selected' : '' }}" data-value="{{ $dd->id }}">{{ $dd->ten_diem_den }}</div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
                <div>
                    <div class="filter-label">Vùng miền</div>
                    <div class="custom-select-wrapper">
                        <select name="vung_mien" class="hidden-select" id="vung_mien_select">
                            <option value="">Toàn quốc</option>
                            <option value="mien_bac" {{ request('vung_mien') == 'mien_bac' ? 'selected' : '' }}>Miền Bắc</option>
                            <option value="mien_trung" {{ request('vung_mien') == 'mien_trung' ? 'selected' : '' }}>Miền Trung</option>
                            <option value="mien_nam" {{ request('vung_mien') == 'mien_nam' ? 'selected' : '' }}>Miền Nam</option>
                        </select>
                        <div class="select-trigger" id="vung_mien_trigger"><span>Toàn quốc</span></div>
                        <div class="select-options" id="vung_mien_options">
                            <div class="select-option {{ request('vung_mien') == '' ? 'selected' : '' }}" data-value="">Toàn quốc</div>
                            <div class="select-option {{ request('vung_mien') == 'mien_bac' ? 'selected' : '' }}" data-value="mien_bac">Miền Bắc</div>
                            <div class="select-option {{ request('vung_mien') == 'mien_trung' ? 'selected' : '' }}" data-value="mien_trung">Miền Trung</div>
                            <div class="select-option {{ request('vung_mien') == 'mien_nam' ? 'selected' : '' }}" data-value="mien_nam">Miền Nam</div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="filter-label">Sắp xếp</div>
                    <div class="custom-select-wrapper">
                        <select name="sap_xep" class="hidden-select" id="sap_xep_select">
                            <option value="moi_nhat" {{ request('sap_xep','moi_nhat') == 'moi_nhat' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="gia_tang" {{ request('sap_xep') == 'gia_tang' ? 'selected' : '' }}>Giá: Thấp → Cao</option>
                            <option value="gia_giam" {{ request('sap_xep') == 'gia_giam' ? 'selected' : '' }}>Giá: Cao → Thấp</option>
                        </select>
                        <div class="select-trigger" id="sap_xep_trigger"><span>Mới nhất</span></div>
                        <div class="select-options" id="sap_xep_options">
                            <div class="select-option {{ request('sap_xep','moi_nhat') == 'moi_nhat' ? 'selected' : '' }}" data-value="moi_nhat">Mới nhất</div>
                            <div class="select-option {{ request('sap_xep') == 'gia_tang' ? 'selected' : '' }}" data-value="gia_tang">Giá: Thấp → Cao</div>
                            <div class="select-option {{ request('sap_xep') == 'gia_giam' ? 'selected' : '' }}" data-value="gia_giam">Giá: Cao → Thấp</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="filter-bottom">
                <div class="price-grid">
                    <div>
                        <div class="filter-label">Giá từ (VNĐ)</div>
                        <input type="number" name="gia_tu" value="{{ request('gia_tu') }}" class="filter-input" placeholder="0" min="0">
                    </div>
                    <div>
                        <div class="filter-label">Giá đến (VNĐ)</div>
                        <input type="number" name="gia_den" value="{{ request('gia_den') }}" class="filter-input" placeholder="Không giới hạn">
                    </div>
                </div>
                <button type="submit" class="btn-filter">
                    <i class="fa-solid fa-magnifying-glass"></i> Tìm Kiếm
                </button>
            </div>
        </form>
    </div>

    <!-- Results bar -->
    <div style="color:rgba(255,255,255,0.5); margin-bottom: 20px; font-family:'Outfit'; font-size:1.2rem;">
        Hệ thống tìm thấy <span style="color:#4ade80; font-weight:800; font-size:1.5rem;">{{ $danh_sach_tour->total() }}</span> tour phù hợp
    </div>

    <!-- Tour Grid Groups -->
    @php
        $tours_noi_bat = $danh_sach_tour->where('noi_bat', 1);
        $tours_thuong = $danh_sach_tour->where('noi_bat', 0);
        $groups = [];
        if($tours_noi_bat->isNotEmpty()) {
            $groups[] = ['title' => '🔥 Tour Nổi Bật', 'tours' => $tours_noi_bat, 'margin' => 'margin-bottom: 4rem;'];
        }
        if($tours_thuong->isNotEmpty()) {
            $groups[] = ['title' => count($groups) > 0 ? 'Danh Sách Tour' : null, 'tours' => $tours_thuong, 'margin' => ''];
        }
    @endphp

    @if($danh_sach_tour->isEmpty())
        <div style="text-align:center; padding: 100px; background: rgba(255,255,255,0.04); border-radius:30px; border:1px solid rgba(255,255,255,0.06); color:#fff; backdrop-filter:blur(10px);">
            <i class="fa-solid fa-map-location-dot fa-4x" style="color:rgba(255,255,255,0.15); margin-bottom:20px;"></i>
            <h2 style="font-family:'Outfit'; font-size:2rem; margin-bottom:10px;">Không tìm thấy Tour</h2>
            <p style="color:rgba(255,255,255,0.4);">Vui lòng thử thay đổi các tùy chọn lọc.</p>
        </div>
    @else
        @foreach($groups as $group)
            @if($group['title'])
            <div style="display:flex;align-items:center;margin-bottom:2rem;">
                <h2 style="font-family:'Outfit';font-size:2rem;font-weight:900;color:#fff;margin:0;">{{ $group['title'] }}</h2>
            </div>
            @endif
            <div class="tour-grid" style="{{ $group['margin'] }}">
                @foreach($group['tours'] as $tour)
                <article class="tour-card-3d">
                    <div class="tour-thumb">
                        @if($tour->noi_bat)
                            <span class="thumb-badge popular">HOT Nhất</span>
                        @else
                            <span class="thumb-badge new">Tour Mới</span>
                        @endif
                        <img src="{{ $tour->hinh_bia_url }}" alt="{{ $tour->ten_tour }}" loading="lazy">
                        <div class="tour-thumb-overlay"></div>
                    </div>

                    <div class="tour-content-3d">
                        <div class="tour-meta-3d">
                            <div class="meta-chip"><i class="fa-solid fa-location-dot"></i> {{ $tour->diemDen->ten_diem_den }}</div>
                            <div class="meta-chip"><i class="fa-regular fa-clock"></i> {{ $tour->so_ngay }}N{{ $tour->so_dem }}Đ</div>
                        </div>

                        <a href="{{ route('tour.chi-tiet', $tour) }}" style="text-decoration:none;">
                            <h2 class="tour-title-3d">{{ $tour->ten_tour }}</h2>
                        </a>

                        <div class="tour-footer-3d">
                            <div>
                                <div class="price-label">Giá từ</div>
                                <div class="price-val-3d">{{ $tour->gia_nguoi_lon_dinh_dang }}</div>
                            </div>
                            <a href="{{ route('tour.chi-tiet', $tour) }}" class="arrow-btn" title="Xem chi tiết">
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
        @endforeach
    @endif

    @if($danh_sach_tour->hasPages())
    <div style="margin-top:50px; display:flex; justify-content:center;">
        {{ $danh_sach_tour->links('pagination::bootstrap-4') }}
    </div>
    @endif

</div>
@endsection

@section('js')

<script>
document.addEventListener('DOMContentLoaded', function() {
    const wrappers = document.querySelectorAll('.custom-select-wrapper');
    
    wrappers.forEach(wrapper => {
        const trigger = wrapper.querySelector('.select-trigger');
        const optionsContainer = wrapper.querySelector('.select-options');
        const select = wrapper.querySelector('select');
        const options = wrapper.querySelectorAll('.select-option');
        
        // Set initial label
        const selectedOption = Array.from(options).find(opt => opt.classList.contains('selected'));
        if (selectedOption) {
            trigger.querySelector('span').textContent = selectedOption.textContent;
        }

        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            // Close other open dropdowns
            document.querySelectorAll('.select-options.show').forEach(el => {
                if (el !== optionsContainer) {
                    el.classList.remove('show');
                    el.previousElementSibling.classList.remove('active');
                }
            });
            optionsContainer.classList.toggle('show');
            trigger.classList.toggle('active');
        });

        options.forEach(option => {
            option.addEventListener('click', function() {
                const value = this.getAttribute('data-value');
                const text = this.textContent;
                
                select.value = value;
                trigger.querySelector('span').textContent = text;
                
                options.forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');
                
                optionsContainer.classList.remove('show');
                trigger.classList.remove('active');
            });
        });
    });

    // Close on click outside
    document.addEventListener('click', function() {
        document.querySelectorAll('.select-options.show').forEach(el => {
            el.classList.remove('show');
            el.previousElementSibling.classList.remove('active');
        });
    });
});
</script>
@if(file_exists(public_path('build/manifest.json')))
    @vite(['resources/js/three-home.js'])
@else
    <script src="{{ asset('build/assets/three-home-DF43yGkW.js') }}" defer></script>
@endif
@endsection
