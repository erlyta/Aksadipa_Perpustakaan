<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index() {
        $books = Book::with('category')->get();
        return view('books.index', compact('books'));
    }

    public function create() {
        $categories = Category::all();
        return view('books.create', compact('categories'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'author' => 'required|string|max:100',
            'publisher' => 'nullable|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'year_published' => 'required|integer|min:1900|max:' . date('Y'),
            'isbn' => 'nullable|string|max:30',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'cover' => 'nullable|image|max:2048',
        ]);

        $data = [
            'title' => $validated['title'],
            'author' => $validated['author'],
            'publisher' => $validated['publisher'] ?? null,
            'category_id' => $validated['category_id'],
            'year' => $validated['year_published'],
            'isbn' => $validated['isbn'] ?? null,
            'stock' => $validated['stock'],
            'synopsis' => $validated['description'] ?? null,
        ];

        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        Book::create($data);
        return redirect('/books')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(Book $book) {
        $categories = Category::all();
        return view('books.edit', compact('book','categories'));
    }

    public function update(Request $request, Book $book) {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'author' => 'required|string|max:100',
            'publisher' => 'nullable|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'year_published' => 'required|integer|min:1900|max:' . date('Y'),
            'isbn' => 'nullable|string|max:30',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'cover' => 'nullable|image|max:2048',
        ]);

        $data = [
            'title' => $validated['title'],
            'author' => $validated['author'],
            'publisher' => $validated['publisher'] ?? null,
            'category_id' => $validated['category_id'],
            'year' => $validated['year_published'],
            'isbn' => $validated['isbn'] ?? null,
            'stock' => $validated['stock'],
            'synopsis' => $validated['description'] ?? null,
        ];

        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $book->update($data);
        return redirect('/books')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Book $book) {
        $book->delete();
        return redirect('/books');
    }
}
