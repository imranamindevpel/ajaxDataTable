<?php

namespace App\Http\Controllers;
    
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Resources\ProductResource;
use Illuminate\Http\JsonResponse;
use DataTables;

class ProductController extends Controller
{ 
    public function index()
    {
        $pageName = "Products";
        return view('products.index', compact('pageName'));
    }
    
    public function get_products_data(){
        $jsonData = ProductResource::collection(Product::all())->toJson();
        $data = json_decode($jsonData);
        return Datatables::of($data)->make(true);
    }

    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'detail' => 'required',
        ]);
        $data = [
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'quantity' => $request->input('quantity'),
            'detail' => $request->input('detail'),
            'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=989&q=80', // Replace with your hardcoded image URL
        ];
        if($request->input('id')){
            Product::where('id', $request->input('id'))->update($data);
            return response()->json(['success' => 'Product Updated successfully']);              
        }else{
            Product::create($data);
            return response()->json(['success' => 'Product Created successfully']);              
        }
    }
    
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }
    
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['success' => 'Product deleted successfully']);
    }
    
    // public function create(){}
    // public function edit(Product $product){}
    // public function update(Request $request, Product $product){}
}