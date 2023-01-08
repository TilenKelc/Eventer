<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use File;

use App\Product;
use App\Category;
use App\Rent;
use App\Reservation;
use Yajra\Datatables\Datatables;
use Log;

class ProductController extends Controller
{
    public function index(){
        return view('product.index');
    }

    public function getProducts(){
        //return Datatables::of(Product::query())->make(true);
        if(Auth::user()->isAdmin()){
            $products = Product::where('deleted', false)->orderBy('created_at', 'asc')->get();
        }else{
            $category_id = Category::where('agent_id', Auth::user()->id)->pluck('id');

            $products = Product::where('deleted', false)->whereIn('category_id', $category_id)->orderBy('created_at', 'asc')->get();
        }
        
        return Datatables::of($products)
                ->editColumn('name', function($products){
                    return $products->name;
                })
                ->editColumn('created_at', function($products){
                    return date_format(date_create($products->created_at), "d.m.Y h:i");
                })
                ->editColumn('updated_at', function($products){
                    return date_format(date_create($products->updated_at), "d.m.Y h:i");
                })
                ->addColumn('edit', function ($products) {
                    $url = url("/product/edit/$products->id");
                    return "<a href='$url'>Posodobi</a>";

                })
                ->addColumn('delete', function($products){
                    $url = url("/product/delete/$products->id");
                    return "<a href='$url' onclick='return confirmAction()'>Izbriši</a>";
                })
                ->rawColumns(['edit', 'delete'])
                ->make(true);
    }

    public function delete(Request $request){
        $id = $request->id;
        $product = Product::find($id);
        $product->deleted = true;
        $product->deleted_at = now();
        $product->save();
        return view('product.index')->with("successMssg", 'Podkategorija ' .$product->name . ' uspešno izbrisana');
    }

    public function addNewProduct(){
        if(Auth::user()->isAdmin()){
            $categories = Category::where('deleted', false)->orderBy('created_at', 'asc')->get();
        }else{
            $categories = Category::where('deleted', false)->where('agent_id', Auth::id())->orderBy('created_at', 'asc')->get();
        }

        return view('product.add', [
            'product' => null,
            'categories' => $categories,
        ]);
    }

    public function saveNewProduct(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('product/add')
                ->withInput()
                ->withErrors($validator);
        }

        $slika_ime = $request['product_image'];
        $slika_ime_array = explode(".", $slika_ime);
        $last_id = Product::max('id');
        $productImageSaveAsName = $last_id+1 . '_' . $request['name'] . '.' . $request['product_image']->getClientOriginalExtension();

        $request['product_image']->move(public_path() . '/product_images', $productImageSaveAsName);

        $product = new Product;
        $product->name = $request->name;
        $product->image = '/product_images/'.$productImageSaveAsName;
        $product->category_id = $request->category_id;
        $product->save();

        return view('product.index')->with("successMssg", 'Podkategorija '.$product->name . ' uspešno dodana');
    }

    public function editProduct(Request $request){
        $id = $request->id;
        $product = Product::find($id);
        if(Auth::user()->isAdmin()){
            $categories = Category::where('deleted', false)->orderBy('created_at', 'asc')->get();
        }else{
            $categories = Category::where('deleted', false)->where('agent_id', Auth::id())->orderBy('created_at', 'asc')->get();
        }

        return view('product.add', [
            'product' => $product,
            'categories' => $categories,
            //'sizes' => $sizes,
            //'recommended' => $recommended,
        ]);
    }

    public function saveUpdatedProduct(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('product/add')
                ->withInput()
                ->withErrors($validator);
        }

        $product = Product::find($request->id);
        $product->name = $request->name;
        $product->category_id = $request->category_id;

        if($request->product_image != null){
            if(File::exists($product->image)) {
                File::delete($product->image);
            }

            $slika_ime = $request['product_image'];
            $slika_ime_array = explode(".", $slika_ime);
            $productImageSaveAsName = $product->id . '_' . $request['name'] . '.' . $request['product_image']->getClientOriginalExtension();

            $request['product_image']->move(public_path() . '/product_images', $productImageSaveAsName);
            $product->image = '/product_images/'.$productImageSaveAsName;
        }
        $product->save();

        return view('product.index')->with("successMssg", 'Podkategorija '.$product->name . ' uspešno posodobljena');
    }

    public function showProduct(Request $request){
        $product = Product::find($request->id);
        $recommended_products = array();

        if(session()->get('rental_from') != null && session()->get('rental_to') != null){
            $search_to = session()->get('rental_to');
            $search_from = session()->get('rental_from');

            $neustrezne_rezervacije = Reservation::whereDate('date_from', '<=', $search_to)
            ->whereDate('date_to', '>=', $search_from)->get();

            $productIDS = array();
            $neustrezne_rezervacije_ids = array();
            foreach($neustrezne_rezervacije as $rez){
                array_push($productIDS, $rez->product_id);
                array_push($neustrezne_rezervacije_ids, $rez->id);
            }

            $ustrezne_rezervacije = Reservation::all()->whereNotIn('id', $neustrezne_rezervacije_ids)->whereNotIn('product_id', $productIDS)->pluck('product_id');

            $allRez = Reservation::all()->pluck('product_id');
            $noReservationYet = Product::where('id', '!=', $product->id)->where('deleted', false)->whereNotIn('id', $allRez)->get();//whereIn('product_id', json_decode($product->recommended_items))->get();
            $notReservedOnDate = Product::where('id', '!=', $product->id)->where('deleted', false)->whereIn('id', $ustrezne_rezervacije)->get();//->whereIn('product_id', json_decode($product->recommended_items))->get();

            $recommended_products = $noReservationYet->merge($notReservedOnDate);
        }else{
            if(($product->recommended_items != null) && ($product->recommended_items)){
                $all_products = Product::all();

                $id_array = json_decode($product->recommended_items);
                foreach($id_array as $id){
                    array_push($recommended_products, Product::find($id));
                }
            }
        }

        $category = Category::find($product->category_id);

        $already_in_cart = false;
        if(session()->get('rent_id') != null){
            $rent = Rent::find(session()->get('rent_id'));
            $product_ids = Reservation::whereIn('id', json_decode($rent->reservation_ids))->pluck('product_id');
            $already_in_cart = in_array($product->id, json_decode($product_ids));
        }

        return view('product.show', [
            "product" => $product,
            "category" => $category,
            "recommended_products" => $recommended_products,
            "already_in_cart" => $already_in_cart
        ]);
    }

    public function fetch(Request $request){
        if($request->get('query')) {
            $query = $request->get('query');
            $already_used = $request->get('already');
            if($already_used == null){
                $data = Product::where('name', 'LIKE', "%{$query}%")->orWhere('product_id', 'LIKE', "%{$query}%")->get();

            }else{
                $data = Product::where('name', 'LIKE', "%{$query}%")->whereNotIn('product_id', $already_used)->orWhere('product_id', 'LIKE', "%{$query}%")->get();
            }

            $output = '<ul>';
            foreach($data as $row) {
                $output .= '<li class="najdenrezultat"><a href="javascript:void(0)">'.$row->name.' ('. $row->product_id . ')</a></li>';
            }
            $output .= '</ul>';
            echo $output;
        }
    }


    public function showAllProductsByDate(Request $request){
      $rent_id = session()->get("rent_id");
      $search_from = session()->get("rental_from");
      $search_to = session()->get("rental_to");

      if(session()->get("rental_to") != null && session()->get("rental_from") != null){
            //$neustrezne_rezervacije = Reservation::whereDate('date_from', '<=', $search_to)
            //    ->whereDate('date_to', '>=', $search_from)->get();

            $neustrezne_rezervacije = [];
            $vse_rezervacije = Reservation::all();
            foreach($vse_rezervacije as $rezervacija){
                $rezervacija_date = date('Y-m-d', strtotime($rezervacija->date_from));
                $search_date = date('Y-m-d', strtotime($search_from));

                $rezervacija_time_from = date('H:i', strtotime($rezervacija->date_from));
                $search_time_from = date('H:i', strtotime($search_from));

                $rezervacija_time_to = date('H:i', strtotime($rezervacija->date_to));
                $search_time_to = date('H:i', strtotime($search_to));

                if($rezervacija_date == $search_date && $rezervacija_time_from <= $search_time_to && $rezervacija_time_to >= $search_time_from){
                    array_push($neustrezne_rezervacije, $rezervacija);
                }
            }

            Log::info($neustrezne_rezervacije);  

            $productIDS = array();
            $neustrezne_rezervacije_ids = array();
            foreach($neustrezne_rezervacije as $rez){
                array_push($productIDS, $rez->product_id);
                array_push($neustrezne_rezervacije_ids, $rez->id);
            }

            $ustrezne_rezervacije = Reservation::all()->whereNotIn('id', $neustrezne_rezervacije_ids)->whereNotIn('product_id', $productIDS)->pluck('product_id');

            $tmp_rent = Rent::find($rent_id);
            $tmp_reservation = Reservation::find(json_decode($tmp_rent->reservation_ids)[0]);
            $tmp_product = Product::find($tmp_reservation->product_id);

            $allRez = Reservation::all()->pluck('product_id');
            $noReservationYet = Product::where('deleted', false)->where("category_id", $tmp_product->category_id)->whereNotIn('id', $allRez)->get();
            $notReservedOnDate = Product::where('deleted', false)->where("category_id", $tmp_product->category_id)->whereIn('id', $ustrezne_rezervacije)->get();

            $products = $noReservationYet->merge($notReservedOnDate);

            return view('common.products_by_date', [
                "products" => $products,
            ]);
      }else{
            $categories = Category::where('deleted', false)->get();

            return view('category.show', [
                "categories" => $categories,
            ]);
      }
    }
}
