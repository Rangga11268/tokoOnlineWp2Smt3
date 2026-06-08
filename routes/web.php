<?php

use App\Http\Controllers\BerandaController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RajaOngkirController;
use App\Http\Controllers\RajaOngkirControllerV2;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get("/", function () {
    return redirect()->route("beranda");
});

Route::get("backend/beranda", [
    BerandaController::class,
    "berandaBackend",
])->name("backend.beranda");

Route::get("backend/login", [LoginController::class, "loginBackend"])->name(
    "backend.login",
);
Route::post("backend/login", [
    LoginController::class,
    "authenticateBackend",
])->name("backend.login");
Route::post("backend/logout", [LoginController::class, "logoutBackend"])->name(
    "backend.logout",
);

// Route untuk Customer
Route::resource("backend/customer", CustomerController::class, [
    "as" => "backend",
])->middleware("auth");

// Route::resource('backend/user', UserController::class)->middleware('auth');
Route::resource("backend/user", UserController::class, [
    "as" => "backend",
])->middleware("auth");
Route::resource("backend/kategori", KategoriController::class, [
    "as" => "backend",
])->middleware("auth");

Route::resource("backend/produk", ProdukController::class, [
    "as" => "backend",
])->middleware("auth");
// Route untuk menambahkan foto
Route::post("foto-produk/store", [ProdukController::class, "storeFoto"])
    ->name("backend.foto_produk.store")
    ->middleware("auth");
// Route untuk menghapus foto
Route::delete("foto-produk/{id}", [ProdukController::class, "destroyFoto"])
    ->name("backend.foto_produk.destroy")
    ->middleware("auth");

Route::get("backend/laporan/formuser", [UserController::class, "formUser"])
    ->name("backend.laporan.formuser")
    ->middleware("auth");
Route::post("backend/laporan/cetakuser", [UserController::class, "cetakUser"])
    ->name("backend.laporan.cetakuser")
    ->middleware("auth");
Route::get("backend/laporan/formproduk", [
    ProdukController::class,
    "formProduk",
])
    ->name("backend.laporan.formproduk")
    ->middleware("auth");
Route::post("backend/laporan/cetakproduk", [
    ProdukController::class,
    "cetakProduk",
])
    ->name("backend.laporan.cetakproduk")
    ->middleware("auth");

// Frontend
Route::get("/beranda", [BerandaController::class, "index"])->name("beranda");
Route::get("/produk/detail/{id}", [ProdukController::class, "detail"])->name(
    "produk.detail",
);
Route::get("/produk/kategori/{id}", [
    ProdukController::class,
    "produkKategori",
])->name("produk.kategori");
Route::get("/produk/all", [ProdukController::class, "produkAll"])->name(
    "produk.all",
);

// Group route untuk customer
Route::middleware("is.customer")->group(function () {
    // Route untuk menampilkan halaman akun customer
    Route::get("/customer/akun/{id}", [
        CustomerController::class,
        "akun",
    ])->name("customer.akun");

    // Route untuk mengupdate data akun customer
    Route::put("/customer/updateakun/{id}", [
        CustomerController::class,
        "updateAkun",
    ])->name("customer.updateakun");

    // Route keranjang belanja
    Route::post("add-to-cart/{id}", [
        OrderController::class,
        "addToCart",
    ])->name("order.addToCart");

    Route::get("cart", [OrderController::class, "viewCart"])->name(
        "order.cart",
    );

    Route::post("cart/update/{id}", [
        OrderController::class,
        "updateCart",
    ])->name("order.updateCart");

    Route::post("remove/{id}", [
        OrderController::class,
        "removeFromCart",
    ])->name("order.remove");

    // Ongkir
    Route::post("select-shipping", [
        OrderController::class,
        "selectShipping",
    ])->name("order.selectShipping");

    // Route untuk mengambil data provinsi
    Route::get("provinces", [OrderController::class, "getProvinces"]);

    // Route untuk mengambil data kota berdasarkan provinsi
    Route::get("cities/{provinceId}", [OrderController::class, "getCities"]);

    // Route untuk menghitung ongkos kirim
    Route::post("cost", [OrderController::class, "getCost"])->name(
        "order.cost",
    );

    // Route untuk update ongkir
    Route::post("updateongkir", [OrderController::class, "updateongkir"])->name(
        "order.updateongkir",
    );
    
    // Pembayaran
    Route::get(
        'select-payment',
        [OrderController::class, 'selectPayment']
    )->name('order.selectpayment');
    // History pesanan
    Route::get(
        'history',
        [OrderController::class, 'orderHistory']
    )->name('order.history');
    Route::post(
        'order/complete',
        [OrderController::class, 'complete']
    )->name('order.complete');

    // Route untuk invoice frontend
    Route::get(
        'history/invoice/{id}',
        [OrderController::class, 'invoiceFrontend']
    )->name('order.invoice');
});

// Route untuk backend pesanan
Route::get("backend/pesanan/proses", [OrderController::class, "statusProses"])
    ->name("pesanan.proses")
    ->middleware("auth");
Route::get("backend/pesanan/selesai", [OrderController::class, "statusSelesai"])
    ->name("pesanan.selesai")
    ->middleware("auth");
Route::get("backend/pesanan/detail/{id}", [OrderController::class, "statusDetail"])
    ->name("pesanan.detail")
    ->middleware("auth");
Route::put("backend/pesanan/update/{id}", [OrderController::class, "statusUpdate"])
    ->name("pesanan.update")
    ->middleware("auth");
Route::get("backend/pesanan/invoice/{id}", [OrderController::class, "invoiceBackend"])
    ->name("pesanan.invoice")
    ->middleware("auth");
Route::get("backend/pesanan/formproses", [OrderController::class, "formOrderProses"])
    ->name("pesanan.formproses")
    ->middleware("auth");
Route::post("backend/pesanan/cetakproses", [OrderController::class, "cetakOrderProses"])
    ->name("pesanan.cetakproses")
    ->middleware("auth");
Route::get("backend/pesanan/formselesai", [OrderController::class, "formOrderSelesai"])
    ->name("pesanan.formselesai")
    ->middleware("auth");
Route::post("backend/pesanan/cetakselesai", [OrderController::class, "cetakOrderSelesai"])
    ->name("pesanan.cetakselesai")
    ->middleware("auth");


Route::get("/cek-ongkir", function () {
    return view("cek-ongkir");
});

Route::get('/ongkir/get-destination', [RajaOngkirControllerV2::class, 'getDestination']);
Route::post('/ongkir/calculate', [RajaOngkirControllerV2::class, 'calculateOngkir']);

Route::get("/provinces", [RajaOngkirController::class, "getProvinces"]);

Route::get("/cities/{provinceId}", [RajaOngkirController::class, "getCities"]);

Route::post("/cost", [RajaOngkirController::class, "getCost"])->name(
    "ongkir.cost",
);
Route::get("/test-cost", function () {
    $response = Http::withHeaders([
        "key" => env("RAJAONGKIR_API_KEY"),
    ])->post(env("RAJAONGKIR_BASE_URL") . "/calculate/domestic-cost", [
        "origin" => 649,
        "destination" => 588,
        "weight" => 1000,
        "courier" => "jne",
        "origin_type" => "city",
        "destination_type" => "city",
    ]);

    dd($response->json());
});

Route::get("/test-ongkir", function () {
    $response = Http::withHeaders([
        "key" => env("RAJAONGKIR_API_KEY"),
    ])->get(env("RAJAONGKIR_BASE_URL") . "/destination/province");
    dd($response->json());
});

//API Google
Route::get("/auth/redirect", [CustomerController::class, "redirect"])->name(
    "auth.redirect",
);
Route::get("/auth/google/callback", [
    CustomerController::class,
    "callback",
])->name("auth.callback");
// Logout
Route::post("/logout", [CustomerController::class, "logout"])->name("logout");

// Route::get("/list-ongkir", function () {
//     $response = Http::withHeaders([
//         "key" => "FbdEAu6ube8721522a7478b7Qk6wSd29",
//     ])->get("https://api.rajaongkir.com/starter/city?province=5"); //ganti 'province' atau 'city'
//     dd($response->json());
// });

Route::get("/list-ongkir", function () {
    $response = Http::withHeaders([
        "key" => env("RAJAONGKIR_API_KEY"),
    ])->get(env("RAJAONGKIR_BASE_URL") . "/destination/city/12");

    dd($response->json());
});
