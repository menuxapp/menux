<?php

namespace App\Http\Controllers;

use File;
use Image;
use Auth;
use App\Menux;
use Validator;
use App\Models\Product;
use App\Models\ProductCategories;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * @var $rules
     */
    protected $rules = array('name' => 'required|string',
                                'product_category' => 'required|exists:product_categories,id',
                                'description' => 'required',
                                'value' => 'required'); // |confirmed

    /**
     * @var $messages
     */
    protected $messages = array('required' => 'Campo :attribute obrigatório.',
                                'exists' => 'O :attribute não existe.',
                                'unique' => 'O :attribte já está sendo utilizado.');

    /**
     * @var $messages
     */
    protected $attributes = array();

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try
        {
            if(Auth::check())
            {
                $user = Auth::user();
    
                $store = $user->Store;
    
                $products = $store->Product;
            }
            else
            {
                $category = ProductCategories::find($request->input('category'));

                $products = $category->Products;
            }

            return response()->json(array('products' => $products), 200);
        }
        catch (\Exception $e)
        {
            Menux::logError(__FILE__, __LINE__, __METHOD__, $e);

            return response()->json('ERROR', 500);
            
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->except('_token'), 
                                        $this->rules + ['image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'],
                                        $this->messages);

        $validator->setAttributeNames($this->attributes);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 401);
        }

        $file = $request->file('image');

        $img = Image::make($file->path());

        $destinationPath = 'upload/products';

        $filename = 'PRT_' . time() . "." . $file->getClientOriginalExtension();

        $img->resize(380, function ($constraint) {
            $constraint->aspectRatio();
        })->save("$destinationPath/$filename");
        
        $status = true;

        if(!$request->input('status'))
        {
            $status = false;
        }

        $value = str_replace(',', '.', $request->input('value'));

        $category = Product::Create(
            $request->except('_token', '_method', 'image', 'status', 'value') + 
            ['image' => "$destinationPath/$filename", 'status' => $status, 'value' => $value]
        );

        return response()->json($category, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $user = Auth::user();

        $validator = Validator::make($request->except('_token', '_method'), 
                                        $this->rules,
                                        $this->messages);

        $validator->setAttributeNames($this->attributes);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 401);
        }

        $product = Product::find($id);;

        $productImage = $product->image;

        if($request->hasFile('image'))
        {
            if (File::exists($product->image))
            {
                File::delete($product->image);
            }
    
            $file = $request->file('image');

            $img = Image::make($file->path());
            
            $filename = 'PRT_' . time() . "." . $file->getClientOriginalExtension();
            $destinationPath = 'upload/categories';
            
            $img->resize(380, function ($constraint) {
                $constraint->aspectRatio();
            })->save("$destinationPath/$filename");

            $productImage = "$destinationPath/$filename";
        }

        $status = true;

        if(!$request->input('status'))
        {
            $status = false;
        }

        $value = str_replace('.', '', $request->input('value'));

        $value = str_replace(',', '.', $request->input('value'));

        $category = Product::updateOrCreate(
            ['id' => $id],
            $request->except('_token', '_method', 'image', 'status', 'value') + 
            ['image' => $productImage, 'status' => $status, 'value' => $value]
        );

        return response()->json($product, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
