<div class="row form-horizontal">
    <div class="col-lg-8 col-xl-6">
        <div class="form-group row">
            <label for="" class="control-label col-md-4">Tanggal</label>
            <div class="col-md-8">
                <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" placeholder="Tanggal" value="@if(isset($produksi)){{date('Y-m-d', strtotime($produksi->tanggal_transaksi))}}@else{{ date('Y-m-d')}}@endif" required>

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
                <select name="lokasi" id="lokasi" class="form-control @error('lokasi') is-invalid @enderror" required>
                    <option value="" disabled>-- pilih --</option>
                    @foreach ($list_lokasi as $key => $item)
                        @if (isset($produksi) && $produksi->lokasi == $item->kode)
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

        <div class="form-group row">
            <label for="item" class="control-label col-md-4">Item</label>
            <div class="col-md-8">
                <select name="item" id="item" class="form-control @error('item') is-invalid @enderror" required>
                    <option value="" disabled>-- pilih --</option>
                    @foreach ($list_item as $key => $item)
                        @if ($produksi->kode == $item->kode)
                            <option value="{{ $item->kode }}" selected>{{ $item->nama }}</option>
                        @else
                            <option value="{{ $item->kode }}">{{ $item->nama }}</option>
                        @endif
                    @endforeach
                </select>

                @error('item')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="form-group row">
            <label for="qty" class="control-label col-md-4">Qty</label>
            <div class="col-md-8">
                <input type="number" name="qty" id="qty" min="0" class="form-control @error('qty') is-invalid @enderror" placeholder="Qty" @if (isset($produksi)) value="{{ $produksi->qty_actual }}" @endif required>

                @error('qty')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 text-right">
                <a href="{{ route('admin.produksi') }}" class="btn btn-secondary mt-2">Batalkan</a>
                <button type="submit" class="btn btn-primary mt-2">
                    @if(!isset($produksi))
                        {{ __('Tambah') }}
                    @else
                        <input type="hidden" name="id" value="{{ $produksi->id }}">
                        {{ __('Edit') }}
                    @endif
                </button>
            </div>
        </div>
    </div>
</div>

@push('additional-js-end')
<script type="text/javascript">
$(function(){
    customViewClient.renderSelect2(document.querySelectorAll('select.form-control'));
});
</script>
@endpush
