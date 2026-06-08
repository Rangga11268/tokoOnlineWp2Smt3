<style> 
    table { 
        border-collapse: collapse; 
        width: 100%; 
        border: 1px solid #ccc; 
    } 
 
    table tr td { 
        padding: 6px; 
        font-weight: normal; 
        border: 1px solid #ccc; 
    } 
 
    table th { 
        border: 1px solid #ccc; 
        padding: 6px;
        background-color: #f5f5f5;
    } 
</style> 
<table> 
    <tr> 
        <td align="left"> 
            <strong>Perihal :</strong> {{ $judul }} - {{ $subJudul }}<br> 
            <strong>Periode :</strong> {{ date('d-m-Y', strtotime($tanggalAwal)) }} s/d {{ date('d-m-Y', strtotime($tanggalAkhir)) }} 
        </td> 
    </tr> 
</table> 
<p></p> 
<table> 
    <thead> 
        <tr> 
            <th>No</th> 
            <th>No. Resi</th> 
            <th>Tanggal</th> 
            <th>Total Bayar</th> 
            <th>Status</th> 
            <th>Pelanggan</th> 
        </tr> 
    </thead> 
    <tbody> 
        @foreach ($cetak as $row) 
        <tr> 
            <td align="center"> {{ $loop->iteration }} </td> 
            <td> {{ $row->noresi ?? $row->id }} </td> 
            <td> {{ $row->created_at->format('d-m-Y H:i') }} </td> 
            <td> Rp. {{ number_format($row->total_harga + $row->biaya_ongkir, 0, ',', '.') }} </td> 
            <td> 
                @if ($row->status == 'Paid')
                    Proses
                @else
                    {{ $row->status }}
                @endif
            </td> 
            <td> {{ $row->customer->user->email ?? '' }} </td> 
        </tr> 
        @endforeach 
    </tbody> 
</table>

<script> 
    window.onload = function() { 
        printStruk(); 
    } 
 
    function printStruk() { 
        window.print(); 
    } 
</script>
