@extends('backend.v_layouts.app')

@section('content')
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">{{ $subJudul }}</h5>
            <div class="table-responsive">
                <table id="zero_config" class="table table-striped table-bordered table-hover display" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No. Resi</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Pelanggan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($index as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row->noresi ?? $row->id }}</td>
                            <td>{{ $row->created_at->format('d M Y H:i') }}</td>
                            <td>Rp. {{ number_format($row->total_harga + $row->biaya_ongkir, 0, ',', '.') }}</td>
                            <td>
                                @if ($row->status == 'Paid')
                                    <span class="badge badge-primary" style="color: white; padding: 5px 10px;">Proses</span>
                                @elseif ($row->status == 'Kirim')
                                    <span class="badge badge-warning" style="color: white; padding: 5px 10px;">Kirim</span>
                                @else
                                    <span class="badge badge-secondary" style="color: white; padding: 5px 10px;">{{ $row->status }}</span>
                                @endif
                            </td>
                            <td>{{ $row->customer->user->email ?? '' }}</td>
                            <td>
                                <a href="{{ route('pesanan.detail', $row->id) }}" title="Detail Order">
                                    <button type="button" class="btn btn-primary btn-sm"><i class="far fa-eye"></i> Detail</button>
                                </a>
                                <a href="{{ route('pesanan.invoice', $row->id) }}" title="Cetak Invoice" target="_blank">
                                    <button type="button" class="btn btn-secondary btn-sm"><i class="fas fa-print"></i> Cetak</button>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
