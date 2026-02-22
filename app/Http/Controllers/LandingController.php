<?php

namespace App\Http\Controllers;

use App\Models\Book;

class LandingController extends Controller
{
    public function index()
    {
        $books = Book::latest()->take(8)->get();
        return view('landing', compact('books'));
    }
}
