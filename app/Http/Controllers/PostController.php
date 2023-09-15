<?php

namespace App\Http\Controllers;

use App\Models\Post;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public  function makePost(Request $request)
    {

        $rules = [
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $post = Post::create([
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'uid' =>  $request->user()->id,
        ]);

        return response()->json(['post' => $post, 'message' => 'Post created successfully'], 201);
    }
    public function deletePost(Request $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }
        $user = auth()->user();
        if ($user->role == "admin") {
            $post->delete();
            return response()->json(['message' => 'Post deleted successfully']);
        }

        if ($post->uid !== $request->user()->id) {
            return response()->json(['error' => 'You do not have permission to delete this post'], 403);
        }
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }
    public function getAllPosts()
    {
        $posts = Post::all();
        return response()->json(['posts' => $posts]);
    }

    public function updatePost($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }
        $user = auth()->user();
        if ($user->id == $post->uid) {
            $post->body = request('body');
            $post->save();
            return response()->json(['message' => 'Post updated successfully']);
        } else {
            return response()->json(['error' => 'You do not have permission to update this post'], 403);
        }
    }

    public function getPostById($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }
        return response()->json(['post' => $post]);
    }
}
