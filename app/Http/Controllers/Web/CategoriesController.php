<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Models\Cat;

class CategoriesController extends Controller
{
    public function getCats(Request $request) {
        $categories = Cat::all();
        
    }
}
