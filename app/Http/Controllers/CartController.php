<?php

namespace App\Http\Controllers;

use App\User;
use App\Ticket;
use App\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $carts = Cart::with('ticket')->get();
        return view('cart',compact('carts'));
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
        
        $cart = Cart::where([
            ['user_id','=',$request->user_id],
            ['ticket_id','=',$request->ticket_id],
        ])->first();
        if($cart==null){
            Cart::create($request->all());
        }
        else{
            $carts = Cart::find($cart->id);
            // dd($carts);
            $carts->child_count +=  $request->child_count;
            $carts->adult_count +=  $request->adult_count;
            $carts->save();
        }
        return redirect('/cart');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user=Auth::user();
        $cart = User::find($user->id)->cart;
        $total = 0;
        foreach($cart as $i){
            $total = $total + ($i->ticket->childPrice * $i->child_count) + ($i->ticket->adultPrice * $i->adult_count);
        }
        return view('cart',compact('cart','user','total'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        $cart->update($request->all());
        return redirect('/cart');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        $cart->delete();
        return redirect('/cart');
    }
}
