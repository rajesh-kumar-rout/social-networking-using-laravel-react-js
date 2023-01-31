<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Follower;

class UserController extends Controller
{
    public function users(Request $request)
    { 
        $users = User::select([
                'id',
                'profile_image_url'
            ])
            ->selectRaw("concat(first_name, ' ', last_name) as full_name")
            ->where('first_name', 'like', '%' . $request->query('query') . '%')
            ->orWhere('last_name', 'like', '%' . $request->query('query') . '%')
            ->limit($request->limit)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($users);
    }

    public function posts(Request $request, User $user)
    { 
        $posts = $user->posts()
            ->select([
                'posts.id',
                'posts.description',
                'posts.image_url',
                'posts.video_url',
                'posts.created_at'
            ])
            ->selectRaw("'$user->id' as user_id")
            ->selectRaw("'$user->first_name $user->last_name' as user_name")
            ->selectRaw("'$user->profile_image_url' as profile_image_url")
            ->selectRaw('exists(select 1 from likes where likes.user_id = ? and likes.post_id = posts.id) AS is_liked', [$request->user->id])
            ->selectRaw('IF(posts.user_id = ?, 1, 0) AS is_posted', [$request->user->id])
            ->addSelect([
                'total_likes' => Like::whereColumn('post_id', 'posts.id')->selectRaw('count(likes.post_id)'),
                'total_comments' => Comment::whereColumn('post_id', 'posts.id')->selectRaw('count(comments.id)'),
            ])
            ->get();

        return response()->json($posts);
    }

    public function followers(Request $request, User $user)
    {
        $followers = $user->followers()
            ->join('users', 'users.id', 'followers.follower_id')
            ->select([
                'users.id',
                'users.profile_image_url'
            ])
            ->selectRaw("concat(users.first_name, ' ', users.last_name) as full_name")
            ->get();

        return response()->json($followers);
    }

    public function toggleFollow(Request $request, User $user)
    {
        if($request->user()->id == $user->id)
        {
            return response()->json(['error' => 'You can not follow your self'], 422);
        }

        if($request->user()->followings()->where('following_id', $user->id)->exists())
        {
            $request->user()->followings()->where('following_id', $user->id)->delete();
        }
        else 
        {
            $request->user()->followings()->create(['following_id' => $user->id]);
        }

        return response()->json(['success' => 'Toggle follow state successfully']);
    }

    public function followings(Request $request, User $user)
    {
        $followings = $user->followings()
            ->join('users', 'users.id', 'followers.following_id')
            ->select([
                'users.id',
                'users.profile_image_url'
            ])
            ->selectRaw("concat(users.first_name, ' ', users.last_name) as full_name")
            ->get();

        return response()->json($followings);
    }

    public function photos(Request $request, User $user)
    {
        $photos = $user->posts()
            ->whereNotNull('image_url')
            ->select('image_url')
            ->limit($request->limit)
            ->orderBy('posts.id', 'desc')
            ->get();

        return response()->json($photos);
    }

    public function user(Request $request, User $user)
    {
        $user->total_followings = $user->followings()->count();

        $user->total_followers = $user->followers()->count();

        $user->total_posts = $user->posts()->count();

        return response()->json($user);
    }
}
