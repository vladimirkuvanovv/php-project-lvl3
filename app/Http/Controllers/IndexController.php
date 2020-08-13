<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function index()
    {
        if (view()->exists('index')) {
            return view('index');
        }

        abort(404);
    }
}
