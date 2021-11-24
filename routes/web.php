<?php

use App\Charts\WisataChart;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/grafik-data2', function () {

    $response = Http::get('https://ict-juara.herokuapp.com/api/wisata');
    $respon = json_decode($response);

    $categories = [];
    $data = [];

    foreach($respon->data as $wish) {
        $categories[] = $wish->nama_wisata;
        $data[] = $wish->harga;
    }

    // dd($categories);
    return view('high-chart', compact('categories', 'data'));
});

Route::get('/chart-wisata', function () {
    $wisata = collect(Http::get('https://ict-juara.herokuapp.com/api/wisata')->json());

    $labels = $wisata->flatten(1)->pluck('nama_wisata');
    $data = $wisata->flatten(1)->pluck('harga');

    $colors = $labels->map(function ($item){
        return $acak = '#'. substr(md5(mt_rand()), 0, 6);
    });

    $chart = new WisataChart;
    $chart->labels($labels);
    $chart->dataset('HTM wisata','bar',$data)->backgroundColor($colors);

    //pie
    //line
    //doughnut


    // return dd($labels); 
    return view('htm', compact('chart')); 
});

