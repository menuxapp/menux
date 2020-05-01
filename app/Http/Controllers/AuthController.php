<?php

namespace App\Http\Controllers;

use Auth;
use App\Menux;
use Validator;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
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
     * user login
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Redirect
     */
    public function login(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), 
                                            array('email' => 'required',
                                                    'password' => 'required'),
                                            $this->messages);

            $validator->setAttributeNames($this->attributes);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }

            $credentials = $request->only('email', 'password');

            if (!Auth::attempt($credentials))
            {
                return redirect()->back()->withErrors('E-mail / Senha incorreto')->withInput();
            }

            return redirect('dashboard');

        }
        catch (\Exception $e)
        {
            Menux::logError(__FILE__, __LINE__, __METHOD__, $e);

            return redirect()->back()->withInput();
            
        }
    }

    /**
     * user registration
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Redirect
     */
    public function register(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), 
                                            $this->rules,
                                            $this->messages);

            $validator->setAttributeNames($this->attributes);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $user = User::create($request->except('password') + ['password' => app('hash')->make($request->input('password'))]);

            Auth::login($user, true);

            return redirect('/dashboard');

        }
        catch (\Exception $e)
        {
            Menux::logError(__FILE__, __LINE__, __METHOD__, $e);

            return redirect()->back()->withInput();
            
        }
    }
}
