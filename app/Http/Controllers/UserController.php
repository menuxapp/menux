<?php

namespace App\Http\Controllers;

use Auth;
use App\Menux;
use Validator;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    /**
     * @var $rules
     */
    protected $rules = array('name' => 'required|string',
                                'email' => 'required|email|unique:users',
                                'password' => 'required'); // |confirmed

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
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
            $validator = Validator::make($request->all(), 
                                            $this->rules,
                                            $this->messages);

            $validator->setAttributeNames($this->attributes);

            if($validator->fails())
            {
                return response()->json(['errors' => $validator->errors()], 409);
            }

            $user = User::create($request->except('password') + ['password' => app('hash')->make($request->input('password'))]);

            Auth::login($user, true);

            return response()->json($user, 200);

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
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        
    }
}
