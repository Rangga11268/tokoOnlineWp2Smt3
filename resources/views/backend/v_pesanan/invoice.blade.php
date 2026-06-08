<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pesanan #{{ $order->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
            font-size: 14px;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            padding: 30px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #ddd;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .store-details h1 {
            margin: 0 0 10px 0;
            font-size: 24px;
            color: #d10024;
        }
        .store-details p, .invoice-info p {
            margin: 5px 0;
        }
        .invoice-info h2 {
            margin: 0 0 10px 0;
            font-size: 20px;
        }
        .addresses {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .address-box {
            width: 48%;
        }
        .address-box h3 {
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .address-box p {
            margin: 5px 0;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .items-table td {
            border: 1px solid #ddd;
            padding: 10px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .totals-table {
            float: right;
            width: 300px;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 8px 10px;
        }
        .totals-table tr.grand-total {
            border-top: 2px solid #ddd;
            font-size: 16px;
            font-weight: bold;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        .print-btn-container {
            margin-bottom: 20px;
            text-align: right;
        }
        .print-btn {
            background-color: #d10024;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 4px;
        }
        .print-btn:hover {
            background-color: #a0001b;
        }
        @media print {
            .print-btn-container {
                display: none;
            }
            .invoice-box {
                border: none;
                box-shadow: none;
                padding: 0;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="print-btn-container">
        <button class="print-btn" onclick="window.print()">Cetak Invoice</button>
    </div>
    
    <div class="invoice-box">
        <div class="header">
            <div class="store-details">
                <h1>Toko Online BSI</h1>
                <p>Kuliah..? BSI Aja !!!</p>
                <p>Telp/WA: +62 897-8638-973</p>
            </div>
            <div class="invoice-info" style="text-align: right;">
                <h2>INVOICE</h2>
                <p><strong>Order ID:</strong> #{{ $order->id }}</p>
                <p><strong>No. Resi:</strong> {{ $order->noresi ?? '-' }}</p>
                <p><strong>Tanggal:</strong> {{ $order->created_at->format('d F Y H:i') }}</p>
                <p><strong>Status:</strong> {{ $order->status }}</p>
            </div>
        </div>
        
        <div class="addresses">
            <div class="address-box">
                <h3>Pelanggan</h3>
                <p><strong>Nama:</strong> {{ $order->customer->user->nama ?? $order->customer->nama ?? '' }}</p>
                <p><strong>Email:</strong> {{ $order->customer->user->email ?? $order->customer->email ?? '' }}</p>
                <p><strong>No. HP:</strong> {{ $order->customer->user->hp ?? $order->customer->hp ?? '' }}</p>
            </div>
            <div class="address-box">
                <h3>Pengiriman</h3>
                <p><strong>Kurir:</strong> {{ strtoupper($order->kurir) }} - {{ $order->layanan_ongkir }}</p>
                <p><strong>Estimasi:</strong> {{ $order->estimasi_ongkir }} Hari</p>
                <p><strong>Alamat Tujuan:</strong></p>
                <p>{!! $order->alamat !!}</p>
                <p><strong>Kode Pos:</strong> {{ $order->pos }}</p>
            </div>
        </div>
        
        <table class="items-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">No</th>
                    <th>Nama Produk</th>
                    <th class="text-right" style="width: 150px;">Harga</th>
                    <th class="text-center" style="width: 80px;">Qty</th>
                    <th class="text-right" style="width: 150px;">Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $subtotal = 0;
                @endphp
                @foreach ($order->orderItems as $item)
                    @php
                        $itemTotal = $item->harga * $item->quantity;
                        $subtotal += $itemTotal;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $item->produk->nama_produk }}</td>
                        <td class="text-right">Rp. {{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">Rp. {{ number_format($itemTotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="clearfix">
            <table class="totals-table">
                <tr>
                    <td>Subtotal</td>
                    <td class="text-right">Rp. {{ number_format($subtotal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Ongkos Kirim</td>
                    <td class="text-right">Rp. {{ number_format($order->biaya_ongkir, 0, ',', '.') }}</td>
                </tr>
                <tr class="grand-total">
                    <td>Total Bayar</td>
                    <td class="text-right">Rp. {{ number_format($subtotal + $order->biaya_ongkir, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
