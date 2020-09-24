<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Resources\CategoryResource;
use App\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    //
    public function index()
    {
        return CategoryResource::collection(Category::all());
    }
}
