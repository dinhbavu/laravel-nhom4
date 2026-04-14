@extends('layouts.quan-tri')
@section('title', 'Quản Lý Người Dùng - VietGo Admin')
@section('page-title', 'Nguồn Nhân Lực & Người Dùng')

@section('css')
<style>
.user-mini-stats { display:grid; grid-template-columns: repeat(3,1fr); gap:14px; margin-bottom:22px; }
@media (max-width: 768px) {
    .user-mini-stats { grid-template-columns: repeat(3,1fr); gap:10px; }
    .user-mini-stats > div { padding: 12px 10px !important; }
    .user-mini-stats > div > div:first-child { width:34px !important; height:34px !important; font-size:15px !important; flex-shrink:0; }
    .user-mini-stats > div > div:last-child > div:last-child { font-size:18px !important; }
}
@media (max-width: 480px) {
    .user-mini-stats { grid-template-columns: 1fr; gap:8px; }
}
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Danh Sách Người Dùng</h2>
        <p>Quản lý tài khoản admin, nhân viên và khách hàng</p>
    </div>
    @if(auth()->user()->laAdmin())
    <a href="{{ route('quan-tri.nguoi-dung.tao-nhan-vien') }}" class="btn btn-primary">
        <i class="fa-solid fa-user-plus"></i> Thêm Nhân Viên
    </a>
    @endif
</div>

<!-- Stats Mini -->
<div class="user-mini-stats">
    <div style="background:white; border-radius:12px; padding:16px 18px; border:1px solid #e8edf5; display:flex; align-items:center; gap:12px;">
        <div style="width:40px; height:40px; border-radius:10px; background:#fee2e2; display:flex; align-items:center; justify-content:center; color:#dc2626; font-size:18px;"><i class="fa-solid fa-shield-halved"></i></div>
        <div><div style="font-size:11px; color:#64748b; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Quản Trị Viên</div>
        <div style="font-size:22px; font-weight:800; color:#dc2626;">{{ \App\Models\NguoiDung::where('vai_tro','admin')->count() }}</div></div>
    </div>
    <div style="background:white; border-radius:12px; padding:16px 18px; border:1px solid #e8edf5; display:flex; align-items:center; gap:12px;">
        <div style="width:40px; height:40px; border-radius:10px; background:#dbeafe; display:flex; align-items:center; justify-content:center; color:#2563eb; font-size:18px;"><i class="fa-solid fa-id-card"></i></div>
        <div><div style="font-size:11px; color:#64748b; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Nhân Viên</div>
        <div style="font-size:22px; font-weight:800; color:#2563eb;">{{ \App\Models\NguoiDung::where('vai_tro','nhan_vien')->count() }}</div></div>
    </div>
    <div style="background:white; border-radius:12px; padding:16px 18px; border:1px solid #e8edf5; display:flex; align-items:center; gap:12px;">
        <div style="width:40px; height:40px; border-radius:10px; background:#d1fae5; display:flex; align-items:center; justify-content:center; color:#059669; font-size:18px;"><i class="fa-solid fa-users"></i></div>
        <div><div style="font-size:11px; color:#64748b; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Khách Hàng</div>
        <div style="font-size:22px; font-weight:800; color:#059669;">{{ \App\Models\NguoiDung::where('vai_tro','khach_hang')->count() }}</div></div>
    </div>
</div>

<div class="table-card">
    <!-- Filter -->
    <form action="{{ route('quan-tri.nguoi-dung.danh-sach') }}" method="GET">
        <div class="filter-bar">
            <input type="text" name="tu_khoa" value="{{ request('tu_khoa') }}" placeholder="🔍  Tên, email..." class="form-control" style="min-width:200px;">
            <select name="vai_tro" class="form-control">
                <option value="">Tất cả vai trò</option>
                <option value="admin" {{ request('vai_tro') == 'admin' ? 'selected' : '' }}>Quản Trị Viên</option>
                <option value="nhan_vien" {{ request('vai_tro') == 'nhan_vien' ? 'selected' : '' }}>Nhân Viên</option>
                <option value="khach_hang" {{ request('vai_tro') == 'khach_hang' ? 'selected' : '' }}>Khách Hàng</option>
            </select>
            <select name="trang_thai" class="form-control">
                <option value="">Tất cả trạng thái</option>
                <option value="1" {{ request('trang_thai') == '1' ? 'selected' : '' }}>Đang hoạt động</option>
                <option value="0" {{ request('trang_thai') == '0' ? 'selected' : '' }}>Đã khóa</option>
            </select>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-search"></i> Lọc</button>
            @if(request()->hasAny(['tu_khoa','vai_tro','trang_thai']))
            <a href="{{ route('quan-tri.nguoi-dung.danh-sach') }}" class="btn btn-secondary">Xóa lọc</a>
            @endif
        </div>
    </form>

    <div class="overflow-x">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Người Dùng</th>
                    <th>Liên Hệ</th>
                    <th>Vai Trò</th>
                    <th>Ngày Đăng Ký</th>
                    <th>Trạng Thái</th>
                    @if(auth()->user()->laAdmin()) <th style="width:100px;">Thao Tác</th> @endif
                </tr>
            </thead>
            <tbody>
                @forelse($danh_sach_nguoi_dung as $nd)
                <tr>
                    <td>
                        <div style="display:flex; align-items:center; gap:12px;">
                            <i class="fa-solid fa-circle-user" style="font-size:36px; color:#cbd5e1;"></i>
                            <div>
                                <div style="font-weight:700; font-size:14px; color:#1e293b;">
                                    {{ $nd->ho_ten }}
                                    @if($nd->id === auth()->id())
                                    <span style="font-size:10px; background:#dbeafe; color:#1d4ed8; border-radius:4px; padding:1px 6px; font-weight:600; margin-left:4px;">Bạn</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-size:13.5px;">{{ $nd->email }}</div>
                        <div style="font-size:12px; color:#94a3b8;"><i class="fa-solid fa-phone" style="color:#10b981;"></i> {{ $nd->so_dien_thoai ?? 'Chưa có' }}</div>
                    </td>
                    <td>
                        @php $role_colors = ['admin'=>'danger','nhan_vien'=>'info','khach_hang'=>'success']; @endphp
                        <span class="badge badge-{{ $role_colors[$nd->vai_tro] ?? 'secondary' }}">{{ $nd->ten_vai_tro }}</span>
                    </td>
                    <td style="font-size:13px; color:#64748b;">{{ $nd->created_at->format('d/m/Y') }}</td>
                    <td>
                        @if($nd->trang_thai)
                            <span class="status-dot status-active">Hoạt động</span>
                        @else
                            <span class="status-dot status-inactive">Đã khóa</span>
                        @endif
                    </td>
                    <td>
                        @if(auth()->user()->laAdmin() || (auth()->user()->laNhanVien() && $nd->vai_tro === 'khach_hang'))
                            @if($nd->id !== auth()->id() && $nd->vai_tro !== 'admin')
                            <div style="display:flex; gap:6px;">
                                <form action="{{ route('quan-tri.nguoi-dung.khoa', $nd) }}" method="POST" style="display:inline;" onsubmit="return confirm('{{ $nd->trang_thai ? 'Khóa' : 'Mở khóa' }} tài khoản này?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-icon {{ $nd->trang_thai ? 'btn-warning' : 'btn-info' }}" title="{{ $nd->trang_thai ? 'Khóa tài khoản' : 'Mở khóa' }}">
                                        <i class="fa-solid fa-{{ $nd->trang_thai ? 'lock' : 'lock-open' }}"></i>
                                    </button>
                                </form>
                                <a href="{{ route('quan-tri.nguoi-dung.chinh-sua', $nd) }}" class="btn btn-primary btn-sm btn-icon" title="Sửa & Phân quyền">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('quan-tri.nguoi-dung.xoa', $nd) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản này vĩnh viễn?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Xóa tài khoản">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                            @else
                            <span style="color:#d1d5db; padding: 7px; display:inline-block; font-size:12px;"><i class="fa-solid fa-ban"></i></span>
                            @endif
                        @else
                            <span style="color:#94a3b8; font-size:12px; font-style:italic;">Không có quyền</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="empty-row">
                    <i class="fa-solid fa-users" style="font-size:32px; display:block; margin-bottom:8px; opacity:0.3;"></i>
                    Không tìm thấy người dùng
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $danh_sach_nguoi_dung->links() }}</div>
</div>
@endsection
