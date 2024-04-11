<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View 
    {
        //
        return view('posts.index', [
            'posts' => Post::with('user')->latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // print("bonjour");
        // die;
        // print_r($request->all());

        //
        $validated = $request->validate([
            'message' => 'required|string|max:255',
            'image' => 'required|image'
        ]);

        print_r($validated);
        
        // Store the uploaded image in the storage directory
        $imagePath = $request->file('image')->store('public/images');
        print("AFTER IMAGEPATH");

        // Retrieve the full URL of the stored image
        $imageUrl = Storage::url($imagePath);
        print_r($imageUrl);
        print("AFTER IMAGEURL");
        
        $request->user()->posts()->create([
            print("1"),
            'message' => $validated['message'],
            print("2"),
            'image_path' => $imageUrl, 
            print("3"),
        ]);
        
       
        print("AFTER REQUEST");


        // $request->user()->posts()->create($validated);

        print("bonjour");

        return redirect(route('posts.index'));
        // return redirect()->route('posts.index')->with("success!", "Post créé avec succès !");

    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post): View
    {
        //
        Gate::authorize('update', $post);
        return view('posts.edit', [
            'post' => $post,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post): RedirectResponse
    {
        //
        Gate::authorize('update', $post);
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);
        $post->update($validated);
        return redirect(route('posts.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): RedirectResponse
    {
        //
        Gate::authorize('delete', $post);
        $post->delete();
        return redirect(route('posts.index'));
    }
}
