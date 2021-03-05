<!-- Menghubungkan dengan view template layouts/app -->
@extends('admin/layouts/app')

@section('title', 'Create Series - Admin')

<!-- isi bagian konten -->
<!-- cara penulisan isi section yang panjang -->
@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-home"></i>
        </span> Tambah Produksi
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.produksi') }}"><span>Produksi</span></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <span>Tambah</span>
            </li>
        </ul>
    </nav>
</div>
<div class="row">
    <div class="col-12 grid-margin">
        <div class="panel">
            <div class="panel-body">
                <form method="POST" action="{{ route('admin.produksi.tambah') }}" enctype="multipart/form-data">
                    @csrf
                    @include('admin.produksi._form')
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
