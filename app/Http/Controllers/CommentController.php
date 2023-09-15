<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class CommentController extends Controller
{
    public function createComment(Request $request)
    {
        // Validate the request
        $post = Post::find($request->input('postid'));
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        $request->validate([
            'name' => 'string|max:255',
            'body' => 'required|string',
            'postid' => 'required|exists:posts,id',
        ]);

        Comment::create([
            'name' => $request->input('name'),
            'body' => $request->input('body'),
            'postid' => $request->input('postid'),
        ]);

        return response()->json(['message' => 'Comment added successfully']);
    }
    public function getCommentsByPostId($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }
        $comments = Comment::where('postid', $id)->get();
        return response()->json(['comments' => $comments]);
    }
}
