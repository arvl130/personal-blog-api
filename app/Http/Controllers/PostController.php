<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'body' => 'required|max:255',
        ]);

        $post = Post::create([
            "title" => $request->title,
            "body" => $request->body,
        ]);

        return [
            "message" => "Created post.",
            "post" => $post
        ];
    }

    public function index()
    {
        // Read all
        return collect([
            "message" => "Retrieved all posts."
        ])->merge(Post::orderBy('created_at', 'desc')->paginate(10)->through(fn ($post) => [
            "id" => $post->id,
            "title" => $post->title,
            "body" => substr($post->body, 0, 65) . " ...",
            "createdAt" => $post->created_at,
            "updatedAt" => $post->updated_at,
        ]));
    }

    public function show($id)
    {
        // Read one
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                "message" => "No such post."
            ], 404);
        }

        return [
            "message" => "Retrieved post.",
            "post" => [
                "id" => $post->id,
                "title" => $post->title,
                "body" => $post->body,
                "createdAt" => $post->created_at,
                "updatedAt" => $post->updated_at,
            ],
        ];
    }

    public function update($id, Request $request)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                "message" => "No such post."
            ], 404);
        }

        $post->title = $request->title;
        $post->body = $request->body;
        $post->save();

        // Update
        return [
            "message" => "Updated post.",
            "post" => $post,
        ];
    }

    public function destroy($id)
    {
        // Delete
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                "message" => "No such post."
            ], 404);
        }

        $post->delete();

        return [
            "message" => "Deleted post.",
            "posts" => $post,
        ];
    }
}
