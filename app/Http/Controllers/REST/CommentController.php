<?php

namespace App\Http\Controllers\REST;

use App\Events\CommentLiked;
use App\Events\CommentReplied;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\CommentLike;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function getComments(Request $request) {
        $rules = [
            'type' => 'required',
            'id' => 'required|numeric',
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) return $this->Result(400, null, $validator->errors()->first());

        $user = $request['currentUser'];

        $model = Relation::$morphMap[$request['type']];

        if (!$model) return $this->Result(500, null, 'Server error');
        $post = $model::find($request['id']);
        if (!$post) return $this->Result(404);

        $post->setRelation('comments', $post->comments()->paginate(20));
        return response()->json($post);
    }

    public function leaveComment(Request $request) {
        $rules = [
            'model_type' => 'required|string',
            'model_id' => 'required|numeric',
            'parent_id' => 'numeric',
            'comment' => 'string|max:280',
        ];

        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) return $this->Result(400, null, $validator->errors()->first());

        $model = Relation::$morphMap[$request['model_type']];

        if (!$model) return $this->Result(400, null, 'Incorrect model');

        $post = $model::find($request['model_id']);
        if (!$post) return $this->Result(400, null, 'Post not found');

        $comment = new Comment($request->only(['comment']));
        $comment->commentable_type = $request['model_type'];
        $comment->commentable_id = $request['model_id'];
        $comment->user_id = $request['currentUser']->id;
        if ($request['parent_id']) {
            $parent = Comment::find($request['parent_id']);
            $comment->parent_id = $parent->id;
            $comment->root_id = $parent->root_id;
            $comment->to_user_id = $parent->user_id;
            if ($request['currentUser']->id && $parent->user_id) {
                event(new CommentReplied($comment, $parent->user_id, $request['currentUser']->id));
            }
        }
        $comment->save();
        $comment->load('user', 'to_user');
        return response()->json($comment);
    }

    public function likeComment(Request $request) {
        $rules = [
            'comment_id' => 'required|exists:comments,id',
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) return $this->Result(400, null, $validator->errors()->first());

        $comment = Comment::with('user')->find($request['comment_id']);
        if (!$comment) return $this->Result(404, null, 'Comment not found');
        $to_user = User::find($comment->user_id);
        $like = CommentLike::where('user_id', $request['currentUser']->id)->where('comment_id', $request['comment_id'])->first();
        $liked = false;
        if ($like) {
            $like->delete();
        } else {
            $like = CommentLike::Create([
                'user_id' => $request['currentUser']->id,
                'comment_id' => $request['comment_id'],
            ]);
            $like->save();
            $liked = true;
            if ($to_user && $to_user->id != $request['currentUser']->id) {
                event(new CommentLiked($comment, $to_user, $request['currentUser']));
            }
        }
        return response()->json(['liked' => $liked]);
    }
}
