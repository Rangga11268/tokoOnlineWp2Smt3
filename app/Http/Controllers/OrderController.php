<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

use App\Models\Customer;
use App\Models\Produk;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    // Tambah produk ke keranjang
    public function addToCart($id)
    {
        $customer = Customer::where(
            'user_id',
            Auth::id()
        )->first();

        $produk = Produk::findOrFail($id);

        $order = Order::firstOrCreate(
            [
                'customer_id' => $customer->id,
                'status' => 'pending'
            ],
            [
                'total_harga' => 0
            ]
        );

        $orderItem = OrderItem::firstOrCreate(
            [
                'order_id' => $order->id,
                'produk_id' => $produk->id
            ],
            [
                'quantity' => 1,
                'harga' => $produk->harga
            ]
        );

        if (!$orderItem->wasRecentlyCreated) {

            $orderItem->quantity++;
            $orderItem->save();

        }

        $order->total_harga += $produk->harga;
        $order->save();

        return redirect()
            ->route('order.cart')
            ->with(
                'success',
                'Produk berhasil ditambahkan ke keranjang'
            );
    }

    // Menampilkan keranjang
    public function viewCart()
    {
        $customer = Customer::where(
            'user_id',
            Auth::id()
        )->first();

        $order = Order::where(
            'customer_id',
            $customer->id
        )
        ->where('status', 'pending')
        ->first();

        if ($order) {

            $order->load('orderItems.produk');

        }

        return view(
            'v_order.cart',
            compact('order')
        );
    }

    // Update quantity keranjang
    public function updateCart(Request $request, $id)
    {
        $customer = Customer::where(
            'user_id',
            Auth::id()
        )->first();

        $order = Order::where(
            'customer_id',
            $customer->id
        )
        ->where('status', 'pending')
        ->first();

        if ($order) {

            $orderItem = $order->orderItems()
                ->where('id', $id)
                ->first();

            if ($orderItem) {

                $quantity =
                    $request->input('quantity');

                if (
                    $quantity >
                    $orderItem->produk->stok
                ) {

                    return redirect()
                        ->route('order.cart')
                        ->with(
                            'error',
                            'Jumlah produk melebihi stok yang tersedia'
                        );

                }

                $order->total_harga -=
                    $orderItem->harga *
                    $orderItem->quantity;

                $orderItem->quantity =
                    $quantity;

                $orderItem->save();

                $order->total_harga +=
                    $orderItem->harga *
                    $orderItem->quantity;

                $order->save();

            }

        }

        return redirect()
            ->route('order.cart')
            ->with(
                'success',
                'Jumlah produk berhasil diperbarui'
            );
    }

    // Hapus produk dari keranjang
    public function removeFromCart(Request $request, $id)
    {
        $customer = Customer::where(
            'user_id',
            Auth::id()
        )->first();

        $order = Order::where(
            'customer_id',
            $customer->id
        )
        ->where('status', 'pending')
        ->first();

        if ($order) {

            $orderItem = OrderItem::where(
                'order_id',
                $order->id
            )
            ->where('produk_id', $id)
            ->first();

            if ($orderItem) {

                $order->total_harga -=
                    $orderItem->harga *
                    $orderItem->quantity;

                $orderItem->delete();

                if ($order->total_harga <= 0) {

                    $order->delete();

                } else {

                    $order->save();

                }

            }

        }

        return redirect()
            ->route('order.cart')
            ->with(
                'success',
                'Produk berhasil dihapus dari keranjang'
            );
    }

    // Halaman pilih pengiriman
    public function selectShipping(Request $request)
    {
        $customer = Customer::where('user_id', Auth::id())->first();
        $order = Order::where('customer_id', $customer->id)->where('status', 'pending')->first();
        
        if (!$order || $order->orderItems->count() == 0) {
            return redirect()->route('order.cart')->with('error', 'Keranjang belanja kosong.');
        }

        $totalHarga = 0;
        $totalBerat = 0;
        foreach ($order->orderItems as $item) {
            $totalHarga += $item->harga * $item->quantity;
            $totalBerat += $item->produk->berat * $item->quantity;
        }

        return view('v_order.select_shipping', compact('order', 'totalHarga', 'totalBerat'));
    }

    // Update data ongkir
    public function updateongkir(Request $request)
    {
        $customer = Customer::where(
            'user_id',
            Auth::id()
        )->first();

        $order = Order::where(
            'customer_id',
            $customer->id
        )
        ->where('status', 'pending')
        ->first();

        if ($order) {

            $order->kurir =
                $request->input('kurir');

            $order->layanan_ongkir =
                $request->input('layanan_ongkir');

            $order->biaya_ongkir =
                $request->input('biaya_ongkir');

            $order->estimasi_ongkir =
                $request->input('estimasi_ongkir');

            $order->total_berat =
                $request->input('total_berat');

            $order->alamat =
                $request->input('alamat') .
                ', <br>' .
                $request->input('city_name') .
                ', <br>' .
                $request->input('province_name');

            $order->pos =
                $request->input('pos');

            $order->save();

            return redirect()
                ->route('order.selectpayment');

        }

        return back()->with(
            'error',
            'Gagal menyimpan data ongkir'
        );
    }

    // Ongkir getProvinces
    public function getProvinces()
    {
        $response = Http::withHeaders([
            'key' => env('RAJAONGKIR_API_KEY')
        ])->get(
            env('RAJAONGKIR_BASE_URL') .
            '/destination/province'
        );

        return response()->json(
            $response->json()
        );
    }

    // Ongkir getCities
    public function getCities($provinceId)
    {
        $response = Http::withHeaders([
            'key' => env('RAJAONGKIR_API_KEY')
        ])->get(
            env('RAJAONGKIR_BASE_URL') .
            '/destination/city/' .
            $provinceId
        );

        return response()->json(
            $response->json()
        );
    }

    // Ongkir getCost
    public function getCost(Request $request)
    {
        $request->validate([
            'origin' => 'required',
            'destination' => 'required',
            'weight' => 'required|numeric',
            'courier' => 'required'
        ]);

        $response = Http::asForm()
            ->withHeaders([
                'key' => env('RAJAONGKIR_API_KEY')
            ])
            ->post(
                env('RAJAONGKIR_BASE_URL') .
                '/calculate/domestic-cost',
                [
                    'origin' => $request->origin,
                    'destination' => $request->destination,
                    'weight' => $request->weight,
                    'courier' => $request->courier,
                    'origin_type' => 'city',
                    'destination_type' => 'city'
                ]
            );

        return response()->json(
            $response->json()
        );
    }
    public function selectPayment()
    {
    $customer = Customer::where(
        'user_id',
        Auth::id()
    )->first();

    $order = Order::where(
        'customer_id',
        $customer->id
    )
    ->where('status', 'pending')
    ->first();

    if (!$order) {
        return redirect()
            ->route('order.cart')
            ->with(
                'error',
                'Pesanan tidak ditemukan'
            );
    }

    $order->load('orderItems.produk');

    return view(
        'v_order.select_payment',
        compact('order')
    );
    }
    public function orderHistory()
    {
        $customer = Customer::where('user_id', Auth::id())->first();
        $statuses = ['Paid', 'Kirim', 'Selesai'];
        $orders = Order::where('customer_id', $customer->id)
            ->whereIn('status', $statuses)
            ->orderBy('id', 'desc')
            ->get();
        return view('v_order.history', compact('orders'));
    }

    public function complete()
    {
        // Ambil data customer berdasarkan user yang sedang login
        $customer = Customer::where('user_id', Auth::id())->first();
        if (!$customer) {
            return redirect()->route('order.cart')
                ->with('error', 'Data customer tidak ditemukan.');
        }
        // Cari order yang masih pending
        $order = Order::where('customer_id', $customer->id)
            ->where('status', 'pending')
            ->first();
        if (!$order) {
            return redirect()->route('order.cart')
                ->with('error', 'Keranjang belanja kosong.');
        }
        // Format tanggal: tahun-bulan-tanggal
        $tanggal = date('ymd');
        // Ambil noresi terakhir
        $lastOrder = Order::whereDate('created_at', date('Y-m-d'))
            ->whereNotNull('noresi')
            ->orderBy('noresi', 'desc')
            ->first();
        if ($lastOrder) {
            $lastNumber = intval(substr($lastOrder->noresi, -3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        // Format nomor urut
        $urut = str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        // Gabungkan noresi
        $noresi = $tanggal . $urut;
        // Kurangi stok produk
        foreach ($order->orderItems as $item) {
            $produk = Produk::find($item->produk_id);
            if ($produk) {
                $produk->stok -= $item->quantity;
                $produk->save();
            }
        }
        // Update order
        $order->status = 'Paid';
        $order->noresi = $noresi;
        $order->save();

        return redirect()->route('order.history')
            ->with('success', 'Checkout berhasil. Nomor resi Anda: ' . $noresi);
    }

}