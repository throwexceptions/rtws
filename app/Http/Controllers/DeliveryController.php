<?php

namespace App\Http\Controllers;

use App\Booking;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index()
    {
        return view('delivery', ['page_name' => 'Delivery']);
    }

    public function store(Request $request)
    {
    }
}