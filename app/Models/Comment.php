<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use phpDocumentor\Reflection\Types\Self_;

class Comment extends Model
{
    use SoftDeletes;
    protected $fillable = ['comment'];
    protected $hidden = ['updated_at', 'deleted_at', 'user_id', 'to_user_id', 'commentable_id', 'commentable_type', 'commentLike'];
    protected $withCount = ['likes'];
    protected $with = ['user', 'to_user', 'commentLike'];
    protected $appends = ['is_liked', 'published_ago'];

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        self::created(function(Comment $comment) {
            if (is_null($comment->parent_id) && is_null($comment->root_id)) {
                $comment->parent_id = $comment->id;
                $comment->root_id = $comment->id;
                $comment->save();
            }
        });
        self::deleting(function(Comment $comment) {
            $childComments = Comment::where('root_id', $comment->root_id)->where('id', '>', $comment->id)->get();
            foreach ($childComments as $comment) {
                $comment->delete();
            }
        });
    }

    #region Relations

    public function commentable() {
        return $this->morphTo();
    }

    public function user() {
        return $this->belongsTo(User::class)->select('id', 'name', 'last_name', 'thumb', 'blocked');
    }

    public function to_user() {
        return $this->belongsTo(User::class)->select('id', 'name', 'last_name', 'thumb');
    }

    public function likes() {
        return $this->belongsToMany(User::class, 'comment_likes');
    }

    public function commentLike() {
        return $this->hasOne(CommentLike::class)->where('user_id', User::$currentUser->id ?? 0);
    }


    #endregion

    #region Relations
    #endregion

    #region Mutators
    #endregion

    #region Accessors

    public function getIsLikedAttribute() {
        return isset($this->commentLike);
    }

    public function getPublishedAgoAttribute() {
        $now = Carbon::now();
        $created = Carbon::make($this->created_at ?? Carbon::now());
        $created->locale('ru');
        $time = $created->longAbsoluteDiffForHumans($now);
        return $time;
    }

    #endregion


}
