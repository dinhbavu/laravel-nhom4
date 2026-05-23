@extends('layouts.3d-app')

@section('title', $tour->ten_tour . ' - VietGo 3D')

@section('css')
<style>
/* ── Hero Banner ── */
.tour-detail-hero {
    position: relative; height: 50vh; min-height: 400px;
    display: flex; align-items: flex-end; padding-bottom: 3rem;
    overflow: hidden;
}
.tour-detail-hero img {
    position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover;
    z-index: 1; opacity: 0.6; mix-blend-mode: luminosity;
    -webkit-mask-image: linear-gradient(to bottom, rgba(0,0,0,1) 0%, rgba(0,0,0,0) 100%);
    mask-image: linear-gradient(to bottom, rgba(0,0,0,1) 0%, rgba(0,0,0,0) 100%);
}
.tour-detail-hero-overlay {
    position: absolute; inset: 0; z-index: 2;
    background: transparent;
}
.tour-detail-header {
    position: relative; z-index: 10; width: 100%; max-width: 1280px; margin: 0 auto; padding: 0 20px;
    transform: translateY(20px); opacity: 0; animation: fadeUp 1s forwards ease-out;
}
@keyframes fadeUp { to { transform: translateY(0); opacity: 1; } }

.tour-badges { display: flex; gap: .8rem; flex-wrap: wrap; margin-bottom: 1rem; }
.badge-3d {
    background: rgba(255,255,255,0.06); backdrop-filter: blur(5px); border: 1px solid rgba(255,255,255,0.1);
    padding: .4rem 1rem; border-radius: 99px; font-weight: 800; font-size: .8rem; color: rgba(255,255,255,0.8); text-transform: uppercase;
}
.badge-3d.highlight { background: rgba(74,222,128,0.15); color: #4ade80; border-color: rgba(74,222,128,0.3); box-shadow: 0 0 10px rgba(74,222,128,0.15); }

.tour-detail-title {
    font-family: 'Outfit', sans-serif; font-size: clamp(2rem, 4vw, 3.5rem); font-weight: 900;
    color: #fff; margin-bottom: 1rem; line-height: 1.1; text-shadow: 0 5px 20px rgba(0,0,0,0.8);
}
.tour-detail-meta { display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap; font-size: 1rem; color: rgba(255,255,255,0.8); font-weight:600; text-shadow: 0 2px 10px rgba(0,0,0,0.8); }
.tour-detail-meta i { color: #4ade80; }

/* ── Layout ── */
.detail-layout { display: grid; grid-template-columns: 1fr 380px; gap: 2.5rem; padding-top: 2rem; position: relative; z-index: 10; }
@media(max-width:1024px) { .detail-layout { grid-template-columns: 1fr; } }

/* ── Glassmorphism Cards ── */
.glass-info-card {
    background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(25px); -webkit-backdrop-filter: blur(25px);
    border-radius: 20px; border: 1px solid rgba(255,255,255,0.06);
    overflow: hidden; margin-bottom: 2rem; box-shadow: 0 15px 35px rgba(0,0,0,0.3);
}
.glass-info-head {
    padding: 1.5rem 2rem; border-bottom: 1px solid rgba(255,255,255,0.06); background: rgba(255,255,255,0.03);
    display: flex; align-items: center; gap: .8rem; font-weight: 800; font-size: 1.2rem; font-family: 'Outfit'; color: #4ade80;
}
.glass-info-body { padding: 2rem; color: rgba(255,255,255,0.7); line-height: 1.8; }

/* ── Itinerary ── */
.itinerary-day { display: flex; gap: 1.5rem; padding: 1.5rem 0; border-bottom: 1px solid rgba(255,255,255,0.06); }
.itinerary-day:last-child { border-bottom: none; }
.day-badge {
    flex-shrink: 0; width: 60px; height: 60px; border-radius: 15px; background: rgba(74,222,128,0.1);
    border: 1px solid rgba(74,222,128,0.25); color: #4ade80; display: flex; flex-direction: column; align-items: center; justify-content: center;
    font-weight: 800; font-size: 1.2rem; box-shadow: 0 0 15px rgba(74,222,128,0.08);
}
.day-badge span:first-child { font-size: .7rem; font-weight: 700; text-transform: uppercase; }
.day-content h3 { font-size: 1.1rem; font-weight: 800; color: #fff; margin-bottom: .5rem; font-family: 'Outfit'; }
.day-content p { color: rgba(255,255,255,0.55); }

/* ── Price Sidebar ── */
.glass-sidebar { position: sticky; top: 100px; }
.sidebar-amount { padding: 2rem; text-align: center; background: rgba(255,255,255,0.03); border-bottom: 1px solid rgba(255,255,255,0.06); }
.price-big { font-family: 'Outfit'; font-size: 2.5rem; font-weight: 900; color: #4ade80; text-shadow: 0 0 20px rgba(74,222,128,0.2); line-height: 1; margin: .5rem 0; }

.schedule-item { border: 1px solid rgba(255,255,255,0.08); border-radius: 15px; padding: 1.2rem; margin-bottom: 1rem; transition: all .3s; background: rgba(255,255,255,0.03); }
.schedule-item:hover { border-color: rgba(74,222,128,0.3); background: rgba(74,222,128,0.05); transform: translateY(-3px); }
.btn-book {
    display: flex; align-items: center; justify-content: center; gap: .5rem; width: 100%; padding: .8rem;
    border-radius: 12px; background: linear-gradient(135deg, #4ade80, #10b981); color: #0f172a; font-weight: 800; font-size: 1rem;
    text-transform: uppercase; transition: all .3s ease; box-shadow: 0 5px 15px rgba(74,222,128,0.3); text-decoration: none; border: none; cursor: pointer;
}
.btn-book:hover { transform: scale(1.02); box-shadow: 0 10px 25px rgba(74,222,128,0.5); }
.btn-wish { background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.8); border: 1px solid rgba(255,255,255,0.1); }
.btn-wish:hover, .btn-wish.active { background: rgba(239,68,68,0.15); color: #f87171; border-color: rgba(239,68,68,0.3); }

.whitespace-pre-line { white-space: pre-line; }

</style>
@endsection

@section('content')
<!-- Hero Banner -->
<div class="tour-detail-hero">
    <img src="{{ $tour->hinh_bia_url }}" alt="{{ $tour->ten_tour }}" loading="eager">
    <div class="tour-detail-hero-overlay"></div>
    <div class="tour-detail-header">
        <div class="tour-badges">
            <span class="badge-3d">{{ $tour->ma_tour }}</span>
            <span class="badge-3d highlight">{{ $tour->loai_tour_ten }}</span>
            @if($tour->noi_bat)<span class="badge-3d" style="color:#f87171;border-color:rgba(239,68,68,0.3);background:rgba(239,68,68,0.1);">🔥 NỔI BẬT</span>@endif
        </div>
        <h1 class="tour-detail-title">{{ $tour->ten_tour }}</h1>
        <div class="tour-detail-meta">
            <span><i class="fa-solid fa-location-dot"></i> {{ $tour->diemDen->ten_diem_den }}</span>
            <span><i class="fa-regular fa-clock"></i> {{ $tour->so_ngay }} Ngày {{ $tour->so_dem }} Đêm</span>
            <span><i class="fa-solid fa-star"></i> {{ $tour->danh_gia_trung_binh }} / 5</span>
        </div>
    </div>
</div>

<div class="container pb-20" style="max-width: 1280px; margin: 0 auto; padding: 0 20px;">
    <div class="detail-layout">

        <!-- ── Left: Info ── -->
        <div>
            <!-- Mô tả -->
            <div class="glass-info-card">
                <div class="glass-info-head">
                    <i class="fa-solid fa-book-open-reader"></i> Giới Thiệu Hành Trình
                </div>
                <div class="glass-info-body">
                    <p class="whitespace-pre-line" style="font-size:1.1rem;">{{ $tour->mo_ta_ngan }}</p>
                    @if($tour->mo_ta_day_du)
                        <div class="mt-6 pt-6 whitespace-pre-line" style="border-top:1px solid rgba(255,255,255,0.06);">
                            {!! $tour->mo_ta_day_du !!}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Lịch trình -->
            @if(count($tour->lichTrinh) > 0)
            <div class="glass-info-card">
                <div class="glass-info-head">
                    <i class="fa-solid fa-route"></i> Nhật Ký Hành Trình
                </div>
                <div class="glass-info-body" style="padding:0 2rem;">
                    @foreach($tour->lichTrinh as $lt)
                    <div class="itinerary-day">
                        <div class="day-badge">
                            <span>Ngày</span>
                            <span>{{ $lt->ngay_thu }}</span>
                        </div>
                        <div class="day-content">
                            <h3>{{ $lt->tieu_de }}</h3>
                            <p class="whitespace-pre-line">{{ $lt->noi_dung }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            
            <!-- Đánh giá -->
            @if(count($tour->danhGia) > 0)
            <div class="glass-info-card">
                <div class="glass-info-head">
                    <i class="fa-solid fa-comment-dots"></i> Đánh Giá Từ Du Khách
                </div>
                <div class="glass-info-body">
                    @foreach($tour->danhGia->take(5) as $dg)
                    <div style="padding:1rem;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.06);border-radius:10px;margin-bottom:1rem;">
                        <div style="display:flex; justify-content:space-between; margin-bottom:.5rem;">
                            <b style="color:#fff">{{ $dg->khachHang->ho_ten ?? 'Ẩn danh' }}</b>
                            <span style="color:#fbbf24">@for($i=1;$i<=5;$i++)<i class="fa-{{ $i<=$dg->diem_so ? 'solid' : 'regular' }} fa-star"></i>@endfor</span>
                        </div>
                        @if($dg->noi_dung)<p class="whitespace-pre-line" style="margin:0;font-size:0.9rem;color:rgba(255,255,255,0.55);">{{ $dg->noi_dung }}</p>@endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- ── Right: Price sidebar ── -->
        <div>
            <div class="glass-info-card glass-sidebar">
                <div class="sidebar-amount">
                    <div style="color:rgba(255,255,255,0.45); font-size:0.9rem;">Giá người lớn từ</div>
                    <div class="price-big">{{ $tour->gia_khuyen_mai_nguoi_lon_dinh_dang ?? $tour->gia_nguoi_lon_dinh_dang }}</div>
                    @if($tour->phan_tram_giam_gia > 0)
                        <div style="font-size:0.9rem;text-decoration:line-through;color:#f87171;">{{ $tour->gia_nguoi_lon_dinh_dang }}</div>
                    @endif
                </div>

                <div class="glass-info-body">
                    <h3 style="font-family:'Outfit'; font-size:1.2rem; color:#fff; margin-bottom:1.5rem;"><i class="fa-solid fa-calendar-check" style="color:#4ade80;"></i> Lịch Khởi Hành</h3>
                    
                    @if(count($lich_khoi_hanh) > 0)
                        @foreach($lich_khoi_hanh->take(4) as $lich)
                        <div class="schedule-item">
                            <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem; color:#fff; font-family:'Outfit'; font-size:1.1rem;">
                                <span><i class="fa-regular fa-clock" style="color:#4ade80;"></i> {{ $lich->ngay_di->format('d/m/Y') }}</span>
                                <span style="font-size:0.8rem; background:rgba(74,222,128,0.12); color:#4ade80; padding:2px 10px; border-radius:20px;">Còn {{ $lich->so_cho_con }} chỗ</span>
                            </div>
                            
                            @auth
                                @if(auth()->user()->laKhachHang())
                                <a href="{{ route('khach-hang.dat-tour.form', $lich) }}" class="btn-book" style="margin-top:1rem;">
                                    <i class="fa-solid fa-bolt"></i> Đặt tour ngay
                                </a>
                                @endif
                            @else
                                <a href="{{ route('dang-nhap') }}" class="btn-book" style="margin-top:1rem; background:rgba(255,255,255,0.08); color:#fff;">
                                    Đăng nhập để đặt
                                </a>
                            @endauth
                        </div>
                        @endforeach
                    @else
                        <div style="text-align:center; padding: 2rem 0; color:rgba(255,255,255,0.35);">
                            Chưa có lịch khởi hành.
                        </div>
                    @endif
                    
                    @auth
                        @if(auth()->user()->laKhachHang())
                        <form action="{{ $da_yeu_thich ? route('khach-hang.yeu-thich.xoa', $tour) : route('khach-hang.yeu-thich.them', $tour) }}" method="POST">
                            @csrf
                            @if($da_yeu_thich) @method('DELETE') @endif
                            <button type="submit" class="btn-book btn-wish {{ $da_yeu_thich ? 'active' : '' }}" style="margin-top:1rem;">
                                <i class="fa-{{ $da_yeu_thich ? 'solid' : 'regular' }} fa-heart"></i>
                                {{ $da_yeu_thich ? 'Đã yêu thích' : 'Thêm vào yêu thích' }}
                            </button>
                        </form>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<!-- Inlined 3D Logic via CDN -->
<script type="module">
import * as THREE from 'https://esm.sh/three@0.160.0';
import { gsap } from 'https://esm.sh/gsap@3.12.4';
import { ScrollTrigger } from 'https://esm.sh/gsap@3.12.4/ScrollTrigger';
gsap.registerPlugin(ScrollTrigger);

class Home3D {
    constructor() {
        this.container = document.querySelector('#webgl-container');
        if (!this.container) return;
        this.width = window.innerWidth;
        this.height = window.innerHeight;
        this.scene = new THREE.Scene();
        this.scene.fog = new THREE.FogExp2(0x0f172a, 0.002);
        this.camera = new THREE.PerspectiveCamera(70, this.width / this.height, 0.1, 1000);
        this.camera.position.z = 100;
        this.renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
        this.renderer.setSize(this.width, this.height);
        this.renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        this.container.appendChild(this.renderer.domElement);
        const baseUrl = window.APP_ASSET_URL || '/';
        this.images = [ baseUrl + 'images/sapa_3d.jpg', baseUrl + 'images/halong_3d.jpg', baseUrl + 'images/hoian_3d.jpg' ];
        this.currentIndex = 0;
        this.textures = [];
        this.meshes = [];
        this.time = 0;
        this.mouse = new THREE.Vector2(0, 0);
        this.targetMouse = new THREE.Vector2(0, 0);
        this.init();
    }
    async init() {
        try {
            const textureLoader = new THREE.TextureLoader();
            for (let img of this.images) {
                try {
                    this.textures.push(await textureLoader.loadAsync(img));
                } catch (e) {
                    console.error('Failed to load texture:', img, e);
                }
            }
            this.addObjects();
            this.addParticles();
            this.addLights();
            this.addEvents();
            this.render();
        } catch (error) {
            console.error('Critical error in 3D init:', error);
        } finally {
            const preloader = document.getElementById('preloader-3d');
            if (preloader) {
                gsap.to(preloader, { opacity: 0, duration: 1, onComplete: () => preloader.remove() });
            }
        }
    }
    addObjects() {
        const geometry = new THREE.PlaneGeometry(350, 200, 64, 64);
        this.textures.forEach((texture, index) => {
            const material = new THREE.MeshStandardMaterial({
                map: texture, transparent: true, opacity: index === 0 ? 1 : 0, roughness: 0.8, metalness: 0.2
            });
            const mesh = new THREE.Mesh(geometry, material);
            mesh.position.z = index === 0 ? 0 : -50;
            this.scene.add(mesh);
            this.meshes.push(mesh);
        });
    }
    addParticles() {
        const geometry = new THREE.BufferGeometry();
        const count = 2000;
        const positions = new Float32Array(count * 3);
        for(let i=0; i<count; i++) {
            positions[i*3] = (Math.random() - 0.5)*400; 
            positions[i*3+1] = (Math.random() - 0.5)*400;
            positions[i*3+2] = (Math.random() - 0.5)*400 - 50;
        }
        geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        const material = new THREE.PointsMaterial({ size: 0.8, color: 0x10b981, transparent: true, opacity: 0.6, depthWrite: false });
        this.particles = new THREE.Points(geometry, material);
        this.scene.add(this.particles);
    }
    addLights() {
        this.scene.add(new THREE.AmbientLight(0xffffff, 0.4));
        this.dirLight = new THREE.DirectionalLight(0xffffff, 1.5);
        this.dirLight.position.set(0, 50, 100);
        this.scene.add(this.dirLight);
    }
    addEvents() {
        window.addEventListener('resize', this.resize.bind(this));
        window.addEventListener('mousemove', (e) => {
            this.targetMouse.x = (e.clientX / window.innerWidth) * 2 - 1;
            this.targetMouse.y = -(e.clientY / window.innerHeight) * 2 + 1;
        });
    }
    resize() {
        this.width = window.innerWidth;
        this.height = window.innerHeight;
        this.renderer.setSize(this.width, this.height);
        this.camera.aspect = this.width / this.height;
        this.camera.updateProjectionMatrix();
    }
    render() {
        this.time += 0.05;
        this.mouse.lerp(this.targetMouse, 0.05);
        this.meshes.forEach((mesh, index) => {
            const positionAttribute = mesh.geometry.attributes.position;
            const vertex = new THREE.Vector3();
            for (let i=0; i<positionAttribute.count; i++) {
                vertex.fromBufferAttribute(positionAttribute, i);
                vertex.z = Math.sin(vertex.x * 0.05 + this.time + index)*3 + Math.cos(vertex.y * 0.05 + this.time)*3;
                positionAttribute.setXYZ(i, vertex.x, vertex.y, vertex.z);
            }
            positionAttribute.needsUpdate = true;
            if(index === this.currentIndex) {
                mesh.rotation.y = this.mouse.x * 0.1;
                mesh.rotation.x = -this.mouse.y * 0.1;
            }
        });
        if(this.particles) {
            this.particles.rotation.y = this.time * 0.05;
            this.particles.rotation.x = this.time * 0.02;
            this.particles.position.x = -this.mouse.x * 20;
            this.particles.position.y = -this.mouse.y * 20;
        }
        if(this.dirLight) {
            this.dirLight.position.x = this.mouse.x * 100;
            this.dirLight.position.y = this.mouse.y * 100 + 50;
        }
        this.renderer.render(this.scene, this.camera);
        requestAnimationFrame(this.render.bind(this));
    }
}
document.addEventListener("DOMContentLoaded", () => { new Home3D(); });
</script>
@endsection
