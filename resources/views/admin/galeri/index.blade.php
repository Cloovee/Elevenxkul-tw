@extends('layouts.admin')

@section('title', 'Kelola Galeri')
@section('page-title', 'Kelola Galeri Ekstrakurikuler')

@section('content')
@include('admin.galeri._style')

<div class="gl-panel">
    <div class="gl-head">
        <div>
            <h3 class="gl-h">Galeri per Ekskul</h3>
            <p class="gl-sub">Foto dikelompokkan per ekstrakurikuler dan tampil dengan susunan yang sama di bagian Galeri landing page. Total {{ $totalFoto }} foto.</p>
        </div>
        <form method="GET" class="gl-tools">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama ekskul..." class="gl-search">
            <button type="submit" class="gl-btn gl-btn-soft"><i class="fas fa-search"></i> Cari</button>
            @if(request('search'))
                <a href="{{ route('admin.galeri.index') }}" class="gl-btn gl-btn-soft">Reset</a>
            @endif
        </form>
    </div>

    <div class="gl-tools">
        <a href="{{ route('admin.galeri.create') }}" class="gl-btn gl-btn-primary"><i class="fas fa-plus"></i> Tambah Foto</a>
        <a href="{{ route('landing') }}#galeri" target="_blank" rel="noopener" class="gl-btn gl-btn-soft"><i class="fas fa-up-right-from-square"></i> Lihat di landing page</a>
    </div>

    @forelse($ekskuls as $ekskul)
        <section class="gl-group" id="ekskul-{{ $ekskul->id_ekskul }}">
            <div class="gl-ghead">
                <div class="gl-gname">
                    <span class="gl-gico"><i class="fas fa-layer-group"></i></span>
                    <div>
                        <h4 class="gl-gtitle">{{ $ekskul->nama_ekskul }}</h4>
                        <p class="gl-gcount">{{ $ekskul->galeri->count() }} foto kegiatan</p>
                    </div>
                </div>
                <a href="{{ route('admin.galeri.create', ['ekskul' => $ekskul->id_ekskul]) }}" class="gl-btn gl-btn-soft gl-btn-sm">
                    <i class="fas fa-plus"></i> Tambah foto
                </a>
            </div>

            @if($ekskul->galeri->isEmpty())
                <div class="gl-empty">
                    <i class="fas fa-images"></i>
                    <b>Belum ada foto kegiatan {{ $ekskul->nama_ekskul }}</b>
                    <span>Ekskul ini belum tampil di galeri landing page.</span>
                </div>
            @else
                <div class="gl-grid">
                    @foreach($ekskul->galeri as $foto)
                        @include('admin.galeri._item', ['foto' => $foto])
                    @endforeach
                </div>
            @endif
        </section>
    @empty
        <div class="gl-empty" style="margin-top:1.1rem">
            <i class="fas fa-magnifying-glass"></i>
            <b>Ekskul tidak ditemukan</b>
            <span>Coba kata kunci lain, atau tambahkan ekskul lewat menu Kelola Ekskul.</span>
        </div>
    @endforelse

    @if($tanpaEkskul->isNotEmpty() && ! request('search'))
        <section class="gl-group">
            <div class="gl-ghead">
                <div class="gl-gname">
                    <span class="gl-gico" style="background:#B7791F"><i class="fas fa-circle-question"></i></span>
                    <div>
                        <h4 class="gl-gtitle">Belum punya ekskul</h4>
                        <p class="gl-gcount">{{ $tanpaEkskul->count() }} foto lama</p>
                    </div>
                </div>
            </div>
            <div class="gl-grid">
                @foreach($tanpaEkskul as $foto)
                    @include('admin.galeri._item', ['foto' => $foto])
                @endforeach
            </div>
            <div class="gl-note"><i class="fas fa-circle-info"></i> Klik Edit lalu pilih ekskulnya supaya foto ini masuk ke galeri ekskul yang tepat.</div>
        </section>
    @endif
</div>
@endsection
