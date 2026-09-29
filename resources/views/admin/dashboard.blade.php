@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-heading', 'Dashboard')

@section('content')
    <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
        <div>
            <p class="mb-1 text-secondary">Ringkasan operasional</p>
            <h1 class="h3 mb-0">Selamat datang, {{ auth()->user()->name }}</h1>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-primary">
            <i class="bi bi-box-seam me-2"></i>Kelola produk
        </a>
    </div>

    <div class="row g-3">
        <div class="col-12 col-md-6 col-xl-4">
            <a href="{{ route('products.index') }}" class="card h-100 border-0 shadow-sm text-decoration-none">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-secondary fw-medium">Data produk</span>
                        <span class="brand-mark"><i class="bi bi-box-seam"></i></span>
                    </div>
                    <h2 class="h5 text-dark mb-1">Kelola katalog</h2>
                    <p class="small text-secondary mb-0">Lihat, tambah, dan perbarui produk minimarket.</p>
                </div>
            </a>
        </div>
    </div>
@endsection
