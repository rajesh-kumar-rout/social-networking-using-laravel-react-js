<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function createPost(Request $request)
    {  
        $request->validate([
            'description' => 'nullable|max:255',
            'image' => 'nullable|image',
            'video_url' => 'nullable'
        ]);

        if($request->description == null && $request->image == null) 
        {
            return response()->json(['error' => 'Either desc or image required'], 422);
        }

        $post = new Post;

        $post->user_id = $request->user()->id;
        $post->description = $request->description;
        $post->video_url = $request->video_url;

        if($request->image) 
        {
            $post->image_url = url('/uploads') . '/' . $request->image->store('images/posts', 'public');
        }

        $post->save();

        return response()->json($post, 201);
    }

    public function deletePost(Request $request, Post $post)
    {  
        if($request->user()->id == $post->user_id) 
        {
            if($post->image_url) 
            {
                $image_url = str_replace(url('/uploads'), '', $post->image_url);

                Storage::disk('public')->delete($image_url);
            }
            
            $post->delete();

            return response()->json(['success' => 'Post deleted successfully']); 
        }

        abort(403);
    }

    public function toggleLike(Request $request, Post $post)
    { 
        if($post->likes()->where('user_id', $request->user()->id)->exists()) 
        {
            $post->likes()->where('user_id', $request->user()->id)->delete();

            return response()->json(['success' => 'Removed from liked post']);
        } 

        $like = $post->likes()->create([
            'user_id' => $request->user()->id
        ]);

        return response()->json($like, 201);
    }

    public function createComment(Request $request, Post $post)
    { 
        $request->validate([
            'comment' => 'required|max:255'
        ]);

        $comment = $post->comments()->create([
            'user_id' => $request->user()->id,
            'comment' => $request->comment
        ]);

        return response()->json($comment, 201);
    }

    public function comments(Request $request, Post $post)
    { 
        $comments = $post->comments()
            ->join('users', 'users.id', 'comments.user_id')
            ->select([
                'users.id as user_id',
                'users.profile_image_url',
                'comments.id',
                'comments.comment',
                'comments.created_at',
            ])
            ->selectRaw("concat(users.first_name, ' ', users.last_name) as user_name")
            ->get();

        return response()->json($comments);
    }

    public function feeds(Request $request)
    { 
        $posts = $request->user
            ->followings()
            ->join('users', 'users.id', 'followers.following_id')
            ->join('posts', 'posts.user_id', 'users.id')
            ->select([
                'users.id as user_id',
                'users.profile_image_url',
                'posts.id',
                'posts.description',
                'posts.image_url',
                'posts.created_at',
            ])
            ->selectRaw("concat(users.first_name, ' ', users.last_name) AS user_name")
            ->selectRaw('exists(select 1 from likes where likes.user_id = ? and likes.post_id = posts.id) AS is_liked', [$request->user->id])
            ->selectRaw('0 as is_posted')
            ->addSelect([
                'total_likes' => Like::whereColumn('post_id', 'posts.id')->selectRaw('count(likes.post_id)'),
                'total_comments' => Comment::whereColumn('post_id', 'posts.id')->selectRaw('count(comments.id)')
            ])
            ->get();

        return response()->json($posts);
    }
}
