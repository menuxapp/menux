<?php

namespace App\Http\Controllers;

use Auth;
use App\Menux;
use Validator;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{

    /**
     * @var $rules
     */
    protected $rules = array('name' => 'required|string',
                                'cep' => 'required|string',
                                'address' => 'required| string',
                                'address_number' => 'required',
                                'district' => 'required',
                                'city' => 'required',
                                'uf' => 'required');

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
    public function index()
    {
        //
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

            $validator = Validator::make($request->except('_token'), 
                                            $this->rules,
                                            $this->messages);

            $validator->setAttributeNames($this->attributes);

            if($validator->fails())
            {
                return response()->json($validator->errors(), 401);
            }

            $store = Store::Create($request->except('_token'));
            
            $user->store_id = $store->id;

            $user->save();
            
            return response()->json($store, 201);
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
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function show(Store $store)
    {
        try
        {
            $user = Auth::user();

            $store = $user->Store;

            if(!$store)
            {
                $store = new Store;
            }

            $data = array('store' => $store);

            return view('store', $data);
        }
        catch (\Exception $e)
        {
            Menux::logError(__FILE__, __LINE__, __METHOD__, $e);

            return redirect()->back()->withInput();
            
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        try
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

            $store = Store::updateOrCreate(
                ['id' => $id],
                $request->except('_token', '_method')
            );
            
            return response()->json($store, 200);
        }
        catch (\Exception $e)
        {
            Menux::logError(__FILE__, __LINE__, __METHOD__, $e);

            return response()->json('ERROR', 500);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
        //
    }
}
