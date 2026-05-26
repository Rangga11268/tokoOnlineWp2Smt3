@extends('v_layouts.app')

@section('content')

<div class="col-md-12">

    <div class="order-summary clearfix">

        <div class="section-title">
            <p>HISTORY</p>
            <h3 class="title">KERANJANG BELANJA</h3>
        </div>

        <table class="shopping-cart-table table">

            <thead>

                <tr>
                    <th>Produk</th>
                    <th></th>
                    <th class="text-center">Harga</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-center">Total</th>
                </tr>

            </thead>

            <tbody>

                @php
                    $subtotal = 0;
                @endphp

                @foreach($order->orderItems as $item)

                    @php
                        $total =
                            $item->harga *
                            $item->quantity;

                        $subtotal += $total;
                    @endphp

                    <tr>

                        <td class="thumb">

                            <img src="{{ asset('storage/img-produk/thumb_sm_' . $item->produk->foto) }}"
                                 alt="">

                        </td>

                        <td class="details">

                            <a>
                                {{ $item->produk->nama_produk }}
                            </a>

                            <ul>
                                <li>
                                    <span>
                                        Berat:
                                        {{ $item->produk->berat }} Gram
                                    </span>
                                </li>
                            </ul>

                            <ul>
                                <li>
                                    <span>
                                        Stok:
                                        {{ $item->produk->stok }}
                                    </span>
                                </li>
                            </ul>

                        </td>

                        <td class="price text-center">

                            <strong>
                                Rp.
                                {{ number_format($item->harga,0,',','.') }}
                            </strong>

                        </td>

                        <td class="qty text-center">

                            {{ $item->quantity }}

                        </td>

                        <td class="total text-center">

                            <strong class="primary-color">

                                Rp.
                                {{ number_format($total,0,',','.') }}

                            </strong>

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

        <div class="pull-right">

            <table class="table">

                <tr>

                    <th>
                        SUBTOTAL
                    </th>

                    <td>
                        Rp.
                        {{ number_format($subtotal,0,',','.') }}
                    </td>

                </tr>

                <tr>

                    <th>
                        Ongkos Kirim
                    </th>

                    <td>

                        Rp.
                        {{ number_format($order->biaya_ongkir,0,',','.') }}

                        <br>

                        {{ strtoupper($order->kurir) }}
                        -
                        {{ $order->layanan_ongkir }}

                        <br>

                        Estimasi
                        {{ $order->estimasi_ongkir }}
                        Hari

                    </td>

                </tr>

                <tr>

                    <th>
                        TOTAL BAYAR
                    </th>

                    <td>

                        <strong class="primary-color"
                                style="font-size:24px;">

                            Rp.

                            {{ number_format(
                                $subtotal + $order->biaya_ongkir,
                                0,
                                ',',
                                '.'
                            ) }}

                        </strong>

                    </td>

                </tr>

            </table>

            <form action="{{ route('order.complete') }}" method="POST">
                @csrf
                <button type="submit" class="primary-btn">
                    BAYAR SEKARANG
                </button>
            </form>
        </div>

    </div>

</div>

@endsection
