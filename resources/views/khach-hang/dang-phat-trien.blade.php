@extends('layouts.khach-hang')
@section('title', 'Đang phát triển - VietGo')

@section('content')
<div class="container py-16 text-center" style="min-height:55vh;display:flex;flex-direction:column;align-items:center;justify-content:center;">
    <div style="width:6rem;height:6rem;border-radius:50%;background:var(--primary-xlight);display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;font-size:2.5rem;color:var(--primary);">
        <i class="fa-solid fa-person-digging"></i>
    </div>
    <h1 style="font-size:clamp(1.5rem,3vw,2rem);font-weight:800;margin-bottom:.75rem;">Tính năng đang xây dựng!</h1>
    <p class="text-secondary mb-8" style="max-width:420px;margin:0 auto 2rem;">Chúng tôi đang nỗ lực hoàn thiện. Vui lòng quay lại sau nhé!</p>
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('trang-chu') }}" class="btn btn-primary btn-lg">
        <i class="fa-solid fa-arrow-left mr-1"></i> Quay lại
    </a>
</div>
@endsection
