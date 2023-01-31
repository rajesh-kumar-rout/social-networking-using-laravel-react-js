<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    public function deleteComment(Request $request, Comment $comment)
    { 
        if($request->user()->id == $comment->user_id) 
        {      
            $comment->delete();

            return response()->json(['success' => 'Comment deleted successfully']); 
        }

        abort(403);
    }
}
