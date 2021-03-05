@extends('admin.layouts.app')

@section('title', 'Produksi - Admin')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header d-flex justify-content-between mb-3 align-items-center">
                    <h4 class="panel-title">Daftar Produksi</h4>
                    <div class="panel-action">
                        <a href="{{ route('admin.produksi.tambah') }}" class="btn btn-primary">Tambah</a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="card">
                        <div class="card-body">
                            <h6>Filter Berdasarkan</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <form class="form-horizontal" action="{{ route('admin.produksi')}}" method="GET">
                                        <div class="form-group row">
                                            <label for="" class="control-label col-md-4">Tanggal</label>
                                            <div class="col-md-8">
                                                <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" placeholder="Tanggal" value="@if(isset($tanggal)){{date('Y-m-d', strtotime($tanggal))}}@else{{ date('Y-m-d')}}@endif" required>

                                                @error('tanggal')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="lokasi" class="control-label col-md-4">Lokasi</label>
                                            <div class="col-md-8">
                                                <select name="lokasi" id="lokasi" class="form-control @error('lokasi') is-invalid @enderror">
                                                    <option value="">-- pilih --</option>
                                                    @foreach ($list_lokasi as $key => $item)
                                                        @if (isset($lokasi) && $lokasi == $item->kode)
                                                            <option value="{{ $item->kode }}" selected>{{ $item->nama }}</option>
                                                        @else
                                                            <option value="{{ $item->kode }}">{{ $item->nama }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>

                                                @error('lokasi')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group text-right">
                                            <button class="btn btn-secondary" type="reset">Reset</button>
                                            <button class="btn btn-primary" type="submit">Filter</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <br>

                    <div class="table-responsive">
                        <table class="table stripe" id="table-series">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Kode Item</th>
                                    <th>Nama Item</th>
                                    <th>Kode Lokasi</th>
                                    <th>Nama Lokasi</th>
                                    <th>Qty Actual</th>
                                    <th>created By</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (! empty($produksi->items()))
                                    @php
                                        $nomer = ($produksi->currentPage() == 1) ? 1 : ($produksi->currentPage()  * $produksi->perPage()) - 1;
                                    @endphp
                                    @foreach ($produksi as $key => $item)
                                        <tr>
                                            <td>{{ $nomer++ }}</td>
                                            <td>{{ date('Y-M-d', strtotime($item->tanggal_transaksi)) }}</td>
                                            <td>{{ $item->item_kode }}</td>
                                            <td>{{ $item->item_nama }}</td>
                                            <td>{{ $item->lokasi_kode }}</td>
                                            <td>{{ $item->lokasi_nama }}</td>
                                            <td>{{ $item->qty_actual }}</td>
                                            <td>{{ $item->karyawan_nama }}</td>
                                            <td class="text-center flex flex-gap-4">
                                                <a href="{{ route('admin.produksi.edit', ['id' => $item->id])}}" class="btn btn-primary">
                                                    <i class="fa fa-pencil"></i>
                                                </a>

                                                <button class="btn btn-danger btn-hapus-produksi" type="button">
                                                    <i class="fa fa-minus"></i>
                                                </button>

                                                <form id="delete-produksi-{{$item->id}}" action="{{ route('admin.produksi.hapus', ['id' => $item->id]) }}" method="POST" style="display: none;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    @csrf
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center" colspan="8">Tidak ada data</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                        <div class="mt-3">
                            {{ $produksi->links('vendor.pagination.simple-bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('additional-js-end')
<script type="text/javascript">

$(function(){

    // render select 2
    customViewClient.renderSelect2(document.querySelectorAll('select.form-control'));

    $(document).on('click', '.btn-hapus-produksi', function(){
        var _ = $(this),
            row = _.closest('tr');

        Confirm.delete((resp) => {
            row.find('form').submit();
        });

    });
});

</script>
@endpush
