<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\Models\City;

class HomeController extends Controller
{
   
    public function __construct()
    {

        $this->middleware('auth');
    }

    public function index()
    {
        return redirect('admin');
    }
 
}
