@extends('backend.v_layouts.app')

@section('content')
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
    <div class="card mb-3">
        <div class="card-header">
            <h3>{{ $subJudul }}</h3>
        </div>
        <div class="card-body">
            <div class="invoice-title text-center mb-3">
                <h2>Detail Pesanan #{{ $order->id }}</h2>
                <strong>Tanggal:</strong> {{ $order->created_at->format('d M Y H:i') }}
            </div>
            
            <form action="{{ route('pesanan.update', $order->id) }}" method="post">
                @method('put')
                @csrf
                <hr>
                
                <div class="row mb-4">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <h5>Pelanggan</h5>
                        <address>
                            <strong>Nama:</strong> {{ $order->customer->user->nama ?? $order->customer->nama ?? '' }}<br>
                            <strong>Email:</strong> {{ $order->customer->user->email ?? $order->customer->email ?? '' }}<br>
                            <strong>Hp:</strong> {{ $order->customer->user->hp ?? $order->customer->hp ?? '' }}<br>
                        </address>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 text-right">
                        <h5>Ongkos Kirim</h5>
                        <address>
                            <strong>Kurir:</strong> {{ strtoupper($order->kurir) }}<br>
                            <strong>Layanan:</strong> {{ $order->layanan_ongkir }}<br>
                            <strong>Estimasi:</strong> {{ $order->estimasi_ongkir }} Hari<br>
                            <strong>Berat:</strong> {{ $order->total_berat }} Gram<br>
                        </address>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <h5>Produk</h5>
                        <table class="table table-bordered table-hover display">
                            <thead>
                                <tr>
                                    <th colspan="2">Produk</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalHarga = 0;
                                @endphp
                                @foreach($order->orderItems as $item)
                                    @php
                                        $totalHarga += $item->harga * $item->quantity;
                                    @endphp
                                    <tr>
                                        <td align="center" style="width: 80px;">
                                            @if($item->produk->foto)
                                                <img src="{{ asset('storage/img-produk/thumb_sm_' . $item->produk->foto) }}" alt="" width="60">
                                            @else
                                                <img src="{{ asset('storage/img-produk/img-default.jpg') }}" alt="" width="60">
                                            @endif
                                        </td>
                                        <td class="details">
                                            <a>{{ $item->produk->nama_produk }}</a>
                                            <ul>
                                                <li><span>Berat: {{ $item->produk->berat }} Gram</span></li>
                                                <li><span>Stok: {{ $item->produk->stok }} Pcs</span></li>
                                            </ul>
                                        </td>
                                        <td class="price text-center">Rp. {{ number_format($item->harga, 0, ',', '.') }}</td>
                                        <td class="qty text-center"><a>{{ $item->quantity }}</a></td>
                                        <td class="total text-center">Rp. {{ number_format($item->harga * $item->quantity, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="empty" colspan="3"></th>
                                    <td><strong>Subtotal</strong></td>
                                    <td>Rp. {{ number_format($totalHarga, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th class="empty" colspan="3"></th>
                                    <td><strong>Ongkos Kirim</strong></td>
                                    <td>Rp. {{ number_format($order->biaya_ongkir, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th class="empty" colspan="3"></th>
                                    <th><strong>TOTAL BAYAR</strong></th>
                                    <th><strong>Rp. {{ number_format($totalHarga + $order->biaya_ongkir, 0, ',', '.') }}</strong></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <label>No. Resi</label>
                            <input type="text" name="noresi" value="{{ old('noresi', $order->noresi) }}" 
                                   class="form-control @error('noresi') is-invalid @enderror" placeholder="Masukkan Nomor Resi">
                            @error('noresi')
                                <span class="invalid-feedback alert-danger" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control @error('status') is-invalid @enderror">
                                <option value="" {{ old('status', $order->status) == '' ? 'selected' : '' }}>- Pilih Status Pesanan -</option>
                                <option value="Paid" {{ old('status', $order->status) == 'Paid' ? 'selected' : '' }}>Proses</option>
                                <option value="Kirim" {{ old('status', $order->status) == 'Kirim' ? 'selected' : '' }}>Kirim</option>
                                <option value="Selesai" {{ old('status', $order->status) == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback alert-danger" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" id="ckeditor">{{ old('alamat', $order->alamat) }}</textarea>
                            @error('alamat')
                                <span class="invalid-feedback alert-danger" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label>Kode Pos</label>
                            <input type="text" name="pos" value="{{ old('pos', $order->pos) }}" 
                                   class="form-control @error('pos') is-invalid @enderror" placeholder="Masukkan Kode Pos">
                            @error('pos')
                                <span class="invalid-feedback alert-danger" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('pesanan.proses') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
