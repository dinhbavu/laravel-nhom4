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
}
.filter-input:focus { background: rgba(255,255,255,0.08); border-color: #4ade80; box-shadow: 0 0 15px rgba(74,222,128,0.15); }
.filter-input option { background: #1e293b; color: #e2e8f0; }

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

/* ── 3D Floating Tour Cards ── */
.tour-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; position: relative; z-index: 10; }
@media(max-width:1000px) { .tour-grid { grid-template-columns: repeat(2, 1fr); } }
@media(max-width:580px) { .tour-grid { grid-template-columns: 1fr; } }

.tour-card-3d {
    background: rgba(255, 255, 255, 0.04);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
    border: 1px solid rgba(255, 255, 255, 0.06);
    display: flex;
    flex-direction: column;
    height: 100%;
    transform-style: preserve-3d;
    perspective: 1000px;
}

.tour-card-3d:hover {
    transform: translateY(-10px) rotateX(5deg) rotateY(-2deg);
    box-shadow: 0 20px 40px rgba(0,0,0,0.3), 0 0 20px rgba(74,222,128,0.08);
    border-color: rgba(74,222,128,0.25);
    background: rgba(255, 255, 255, 0.07);
}

.tour-thumb { position: relative; overflow: hidden; aspect-ratio: 16/11; border-radius: 20px 20px 0 0; }
.tour-thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.8s ease; }
.tour-card-3d:hover .tour-thumb img { transform: scale(1.15); }
.tour-thumb-overlay { position: absolute; inset: 0; background: linear-gradient(to top, rgba(15,23,42,1) 0%, transparent 80%); }

.thumb-badge {
    position: absolute; top: 1rem; left: 1rem;
    backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,0.15); color: white;
    font-size: .7rem; font-weight: 800; padding: .3rem .75rem; border-radius: 20px;
    letter-spacing: .05em; text-transform: uppercase;
}
.thumb-badge.popular { background: rgba(239,68,68,0.8); box-shadow: 0 0 10px rgba(239,68,68,0.5); }
.thumb-badge.new { background: rgba(56,189,248,0.5); box-shadow: 0 0 10px rgba(56,189,248,0.3); }

.tour-content-3d { padding: 1.5rem; flex: 1; display: flex; flex-direction: column; }
.tour-meta-3d { display: flex; align-items: center; gap: 1rem; margin-bottom: .8rem; flex-wrap: wrap; }
.meta-chip { display: flex; align-items: center; gap: .4rem; font-size: .8rem; color: rgba(255,255,255,0.45); font-weight: 600; }
.meta-chip i { color: #4ade80; font-size: .85rem; }

.tour-title-3d {
    font-family: 'Outfit', sans-serif; font-size: 1.2rem; font-weight: 800;
    color: #fff; line-height: 1.4; margin-bottom: .8rem;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    transition: color .3s;
}
.tour-card-3d:hover .tour-title-3d { color: #4ade80; }

.tour-footer-3d {
    margin-top: auto; display: flex; align-items: center; justify-content: space-between;
    padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.06);
}

.price-val-3d { font-family: 'Outfit', sans-serif; font-size: 1.25rem; font-weight: 900; color: #4ade80; }

.arrow-btn {
    width: 2.5rem; height: 2.5rem; border-radius: 50%;
    background: rgba(74,222,128,0.1); border: 1px solid rgba(74,222,128,0.25);
    display: flex; align-items: center; justify-content: center;
    color: #4ade80; transition: all .4s cubic-bezier(0.175,0.885,0.32,1.275);
}
.tour-card-3d:hover .arrow-btn { background: #4ade80; border-color: #4ade80; color: #0f172a; transform: scale(1.1) rotate(45deg); box-shadow: 0 0 15px rgba(74,222,128,0.5); }

</style>
@endsection

@section('content')

<div class="container pb-20" style="position:relative; z-index:10; max-width: 1280px; margin: 0 auto; padding: 0 20px;">

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
                    <select name="diem_den" class="filter-input">
                        <option value="">Tất cả điểm đến</option>
                        @foreach($danh_sach_diem_den->groupBy('vung_mien_ten') as $mien => $diem_dens)
                        <optgroup label="{{ $mien ?: 'Khác' }}">
                            @foreach($diem_dens as $dd)
                            <option value="{{ $dd->id }}" {{ request('diem_den') == $dd->id ? 'selected' : '' }}>{{ $dd->ten_diem_den }}</option>
                            @endforeach
                        </optgroup>
                        @endforeach
                    </select>
                </div>
                <div>
                    <div class="filter-label">Vùng miền</div>
                    <select name="vung_mien" class="filter-input">
                        <option value="">Toàn quốc</option>
                        <option value="mien_bac" {{ request('vung_mien') == 'mien_bac' ? 'selected' : '' }}>Miền Bắc</option>
                        <option value="mien_trung" {{ request('vung_mien') == 'mien_trung' ? 'selected' : '' }}>Miền Trung</option>
                        <option value="mien_nam" {{ request('vung_mien') == 'mien_nam' ? 'selected' : '' }}>Miền Nam</option>
                    </select>
                </div>
                <div>
                    <div class="filter-label">Sắp xếp</div>
                    <select name="sap_xep" class="filter-input">
                        <option value="moi_nhat" {{ request('sap_xep','moi_nhat') == 'moi_nhat' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="gia_tang" {{ request('sap_xep') == 'gia_tang' ? 'selected' : '' }}>Giá: Thấp → Cao</option>
                        <option value="gia_giam" {{ request('sap_xep') == 'gia_giam' ? 'selected' : '' }}>Giá: Cao → Thấp</option>
                    </select>
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
                                <div style="font-size:0.8rem; color:rgba(255,255,255,0.35); text-transform:uppercase; margin-bottom:3px;">Giá từ</div>
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
@if(file_exists(public_path('build/manifest.json')))
    @vite(['resources/js/three-home.js'])
@else
    <script src="{{ asset('build/assets/three-home-DF43yGkW.js') }}" defer></script>
@endif
@endsection
