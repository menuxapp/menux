<?php

namespace App\Http\Controllers;

use File;
use Image;
use Auth;
use App\Menux;
use Validator;
use Illuminate\Http\Request;
use App\Models\ProductCategories;

class ProductCategoriesController extends Controller
{

    /**
     * @var $rules
     */
    protected $rules = array('information' => 'required|string',
                                'description' => 'required'); // |confirmed

    /**
     * @var $messages
     */
    protected $messages = array('required' => 'Campo :attribute obrigatório.',
                                'image' => 'O :attribute tem que ser uma imagem.');

    /**
     * @var $messages
     */
    protected $attributes = array();

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try
        {
            
            $user = Auth::user();

            $categories = $user->ProductCategories;

            return response()->json(array('categories' => $categories), 200);

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
        try
        {
            $user = Auth::user();

            $validator = Validator::make($request->except('_token', '_method'), 
                                            $this->rules + ['image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'],
                                            $this->messages);

            $validator->setAttributeNames($this->attributes);

            if($validator->fails())
            {
                return response()->json($validator->errors(), 401);
            }

            $file = $request->file('image');
            
            $img = Image::make($file->path());
            
            $filename = 'CAT_' . time() . "." . $file->getClientOriginalExtension();
            $destinationPath = 'upload/categories';
            
            $img->resize(380, function ($constraint) {
                $constraint->aspectRatio();
            })->save("$destinationPath/$filename");

            $category = ProductCategories::Create(
                $request->except('_token', '_method', 'image') + ['store_id' => $user->Store->id, 'image' => "$destinationPath/$filename"]
            );

            return response()->json($category, 201);
        }
        catch (\Exception $e)
        {
            Menux::logError(__FILE__, __LINE__, __METHOD__, $e);

            return response()->json('ERROR', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductCategories  $productCategories
     * @return \Illuminate\Http\Response
     */
    public function show(ProductCategories $productCategories)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductCategories  $productCategories
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductCategories  $productCategories
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

        $category = ProductCategories::find($id);

        $categoryImage = $category->image;

        if($request->hasFile('image'))
        {
            if (File::exists($category->image))
            {
                File::delete($category->image);
            }
    
            $file = $request->file('image');

            $img = Image::make($file->path());
            
            $filename = 'CAT_' . time() . "." . $file->getClientOriginalExtension();
            $destinationPath = 'upload/categories';
            
            $img->resize(380, function ($constraint) {
                $constraint->aspectRatio();
            })->save("$destinationPath/$filename");

            $categoryImage = "$destinationPath/$filename";
        }
        
        
        $category = ProductCategories::updateOrCreate(
            ['id' => $id],
            $request->except('_token', '_method', 'image') + ['store_id' => $user->Store->id, 'image' => $categoryImage]
        );

        return response()->json($category, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductCategories  $productCategories
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductCategories $productCategories)
    {
        //
    }
}
