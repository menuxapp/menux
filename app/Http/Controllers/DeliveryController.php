<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Store;
use App\Models\Delivery;
use App\Models\DeliveryItems;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();


        $store = $user->Store;

        $deliveryReceived = $store->Delivery()
                                    ->where('status', '=', _RECEIVED_)
                                    ->get();

        $deliveryMaking = $store->Delivery()
                                    ->where('status', '=', _MAKING_)
                                    ->get();

        $data = array('deliveryReceived' => $deliveryReceived,
                        'deliveryMaking' => $deliveryMaking);

        return response()->json(array('delivery' => $data), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        try
        {
            $store = Store::find($id);

            $categories = $store->ProductCategories;

            $data = array('store' => $store,
                            'categories' => $categories);

            return view('delivery', $data);

        }
        catch (\Exception $e)
        {
            Menux::logError(__FILE__, __LINE__, __METHOD__, $e);

            return response()->json('ERROR', 500);
            
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $amountPaid = null;

        $products = $request->input('products');

        $value = $request->input('value');

        if($request->has('card'))
        {
            $formPayment = $request->input('card');

            $amountPaid = $value;
        }
        else 
        {
            $formPayment = _MONEY_;

            $amountPaid = str_replace(',', '.', $request->input('cash'));

            if($amountPaid < $value)
            {
                //
            }
        }

        $delivery = Delivery::create([
            'store_id' => $request->input('store'),
            'payment_method' => $formPayment,
            'value' => $value,
            'amount_paid' => $amountPaid
        ]);

        foreach ($products as $product)
        {
            $deliveryItem = DeliveryItems::create([
                'delivery_id' => $delivery->id,
                'product_id' => $product['id'],
                'quantity' => $product['qtd'],
            ]);
        }

        return $delivery;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function show(Delivery $delivery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function edit(Delivery $delivery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Delivery $delivery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function destroy(Delivery $delivery)
    {
        //
    }
}
