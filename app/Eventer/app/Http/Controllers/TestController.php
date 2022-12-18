<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use File;

use App\Product;
use App\Category;
use App\Size;
use Yajra\Datatables\Datatables;

use App\Reservation;


class TestController extends Controller
{


    public function showCategoryProductsWithDates(Request $request){

        $iskalni_from = session()->get("rental_from");
        $iskalni_to = session()->get("rental_to");


        // TODO:
        // 1. vrni rezervacije, ki DATE_FROM nimajo med iskalni_from in iskalni_to
        // 2. vrni rezervacije, ki DATE_TO nimajo  med iskalni_from in iskalni_to
        // 3. vrni rezervacije, ki DATE_FROM ni enak $iskalni_from ali $isklani_to
        // 4. vrni rezervacije, ki DATE_TO ni enak $iskalni_from ali $isklani_to


        // $ustrezne_rezervacije = Reservation::whereNotBetween('date_from', [$iskalni_from, $iskalni_to])
        // ->whereNotBetween('date_to', [$iskalni_from, $iskalni_to])
        // ->whereDate('date_from', '!=', $iskalni_from)
        // ->whereDate('date_from', '!=', $iskalni_to)
        // ->whereDate('date_to', '!=', $iskalni_from)
        // ->whereDate('date_to', '!=', $iskalni_to)
        // ->get();


        // vse ki overlapajo z našim from-to
        $productIDS = array();
        $neustrezne_rezervacije = Reservation::whereDate('date_from', '<=', $iskalni_to)
        ->whereDate('date_to', '>=', $iskalni_from)->get();
        $neustrezne_rezervacije_array = array();
        foreach($neustrezne_rezervacije as $rez){
          array_push($productIDS, $rez->product_id);
          array_push($neustrezne_rezervacije_array, $rez->id);
        }
        echo implode("<br>", $productIDS);

        $ustrezne_rezervacije = Reservation::all()->whereNotIn('id', $neustrezne_rezervacije_array)->whereNotIn('product_id', $productIDS)->pluck('product_id');

        $category_id = $request->id;
        $allRez = Reservation::all()->pluck('product_id');
        $noReservationYet = Product::where('deleted', false)->where('category_id', $category_id)->whereNotIn('id', $allRez)->get();
        $notReservedOnDate = Product::where('deleted', false)->where('category_id', $category_id)->whereIn('id', $ustrezne_rezervacije)->get();

        //$products = $noReservationYet->merge($notReservedOnDate);
        //echo $products;
        //echo $noReservationYet . '<br><br>';
        //echo $notReservedOnDate . '<br>';
        
        echo $neustrezne_rezervacije . '<br>';
        foreach($ustrezne_rezervacije as $rezervacija){
          //echo "From: $rezervacija->date_from ";
          //echo "To: $rezervacija->date_to ";
          echo "ID: $rezervacija ";
          echo "<br>";
        }/*
        foreach($products as $product){
          //echo "From: $rezervacija->date_from ";
          //echo "To: $rezervacija->date_to ";
          //echo "ID: $rezervacija->product_id ";
          echo $product->name;
          echo "<br>";
        }

        /*
        $category_id = $request->id;
        $products = Product::where('category_id', $category_id)->where('deleted', false)->get();
        //
        //
        // $sizes = array();
        // foreach($products as $product){
        //     $size = Size::find($product->size_id);
        //     array_push($sizes, $size->size);
        // }
        // $sizes = array_unique($sizes);
        //
        // return view('category.products', [
        //     "category_id" => $category_id,
        //     "products" => $products,
        //     "sizes" => $sizes
        // ]);

        foreach ($products as $product) {

          echo "$product->id<br>";

          $rezervacije_produkta = Reservation::where('product_id', $product->id)->get();

          foreach ($rezervacije_produkta as $rezervacija) {
            echo "rezervaija: $rezervacija->id";
            echo "<br>";

          }
          echo "<br>";

        }
        */
    }

    public function filterBySizeWithDates(Request $request){
        // $category_id = $request->id;
        // $size = $request->size;
        //
        // $size_id = Size::where('size', $size)->get();
        // $products = Product::where('category_id', $category_id)->where('deleted', false)->where('size_id', $size_id[0]->id)->get();
        //
        // return view('category.products', [
        //     "category_id" => $category_id,
        //     "products" => $products,
        //     "sizes" => array($size)
        // ]);
    }
}
