<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use File;

use App\Product;
use App\Category;
use App\Address;
use App\Reservation;
use Yajra\Datatables\Datatables;

class CategoryController extends Controller
{
    public function index(){
        return view('category.index');
    }

    public function getCategories(){
        //return Datatables::of(Category::query())->make(true);
        if(Auth::user()->isAdmin()){
            $category = Category::where('deleted', false)->orderBy('created_at', 'asc')->get();
        }else{
            $category = Category::where('deleted', false)->where('agent_id', Auth::id())->orderBy('created_at', 'asc')->get();
        }
        return Datatables::of($category)
                ->editColumn('name', function($category){
                    return $category->name;
                })
                ->editColumn('category_image', function($category){
                    return $category->category_image;
                })
                ->addColumn('edit', function ($category) {
                    return '<a href="/category/edit/' .$category->id. '">Posodobi</a>';
                })
                ->addColumn('delete', function($category){
                    return '<a href="/category/delete/'.$category->id.'" onclick="return confirmAction()">Izbriši</a>';
                })
                ->rawColumns(['edit', 'delete'])
                ->make(true);
    }

    public function delete(Request $request){
        $id = $request->id;
        $category = Category::find($id);
        $category->deleted = true;
        $category->deleted_at = now();
        $category->save();
        return view('category.index')->with("successMssg", 'Kategorija ' . $category->name . ' uspešno izbrisana');
    }

    public function saveUpdatedCategory(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => '',
            'category_id' => ''
        ]);

        if ($validator->fails()) {
            return redirect('category/add')
                ->withInput()
                ->withErrors($validator);
        }

        $category = Category::find($request->id);
        $category->name = $request->name;

        if($request->category_image != null){
            if(File::exists($category->category_image)) {
                File::delete($category->category_image);
            }

            $slika_ime = $request['category_image'];
            $slika_ime_array = explode(".", $slika_ime);
            $categoryImageSaveAsName = $category->id . '_' . $request['name'] . '.' . $request['category_image']->getClientOriginalExtension();

            $request['category_image']->move(public_path() . '/category_images', $categoryImageSaveAsName);
            $category->category_image = '/category_images/'.$categoryImageSaveAsName;
        }
        $category->description = $request->description;
        $category->amount = $request->amount;
        $category->opens_at = $request->opens_at;
        $category->closes_at = $request->closes_at;

        $address_check = Address::where('street', $request->street)->where('city', $request->city)
            ->where('postal_code', $request->postal_code)->where('country_code', 'SL')->first();

        $address_id = null;
        if($address_check === null){
            $address = new Address();
            $address->street = $request->street;
            $address->city = $request->city;
            $address->postal_code = $request->postal_code;
            $address->country_code = 'SL';
            $address->save();

            $address_id = $address->id;
        }else{
            $address_id = $address_check->id;
        }
        
        $category->address_id = $address_id;
        $category->save();

        return view('category.index')->with("successMssg", 'Kategorija ' . $category->name . ' uspešno posodobljena');
    }

    public function addNewCategory(){
        return view('category.add', [
            'category' => null
        ]);
    }

    public function editCategory(Request $request){
        $id = $request->id;
        $category = Category::find($id);
        return view('category.add', [
            'category' => $category
        ]);
    }

    public function saveNewCategory(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_image' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('category/add')
                ->withInput()
                ->withErrors($validator);
        }

        $slika_ime = $request['category_image'];
        $slika_ime_array = explode(".", $slika_ime);
        $last_id = Category::max('id');
        $categoryImageSaveAsName = $last_id+1 . '_' . $request['name'] . '.' . $request['category_image']->getClientOriginalExtension();

        $request['category_image']->move(public_path() . '/category_images', $categoryImageSaveAsName);

        $category = new Category;
        $category->name = $request->name;
        $category->agent_id = Auth::id();
        $category->category_image = '/category_images/'.$categoryImageSaveAsName;
        $category->description = $request->description;
        $category->amount = $request->amount;
        $category->opens_at = $request->opens_at;
        $category->closes_at = $request->closes_at;

        $address_check = Address::where('street', $request->street)->where('city', $request->city)
            ->where('postal_code', $request->postal_code)->where('country_code', 'SL')->first();

        $address_id = null;
        if($address_check === null){
            $address = new Address();
            $address->street = $request->street;
            $address->city = $request->city;
            $address->postal_code = $request->postal_code;
            $address->country_code = 'SL';
            $address->save();

            $address_id = $address->id;
        }else{
            $address_id = $address_check->id;
        }
        
        $category->address_id = $address_id;
        $category->save();

        return view('category.index')->with("successMssg", 'Kategorija ' . $category->name . ' uspešno dodana');
    }

    public function showCategoryProducts(Request $request){
        $category_id = $request->id;
        $rent_id = session()->get("rent_id");
        $search_from = session()->get("rental_from");
        $search_to = session()->get("rental_to");

        if(session()->get("rental_to") != null && session()->get("rental_from") != null){
            $neustrezne_rezervacije = Reservation::whereDate('date_from', '<=', $search_to)
            ->whereDate('date_to', '>=', $search_from)->get();

            $productIDS = array();
            $neustrezne_rezervacije_ids = array();
            foreach($neustrezne_rezervacije as $rez){
                array_push($productIDS, $rez->product_id);
                array_push($neustrezne_rezervacije_ids, $rez->id);
            }

            $ustrezne_rezervacije = Reservation::all()->whereNotIn('id', $neustrezne_rezervacije_ids)->whereNotIn('product_id', $productIDS)->pluck('product_id');

            $category_id = $request->id;
            $allRez = Reservation::all()->pluck('product_id');
            $noReservationYet = Product::where('deleted', false)->where('category_id', $category_id)->whereNotIn('id', $allRez)->get();
            $notReservedOnDate = Product::where('deleted', false)->where('category_id', $category_id)->whereIn('id', $ustrezne_rezervacije)->get();

            $products = $noReservationYet->merge($notReservedOnDate);
        }else{
            $products = Product::where('category_id', $category_id)->where('deleted', false)->get();
        }

        return view('category.products', [
            "category_id" => $category_id,
            "products" => $products,
        ]);
    }

    public function showCategories(){
        $categories = Category::where('deleted', false)->get();

        return view('category.show', [
            "categories" => $categories
        ]);
    }
}
