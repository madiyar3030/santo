<?php

namespace App\Traits;

use App\Models\Author;
use App\Models\Comment;
use App\Models\Detail;
use App\Models\Favourable;
use App\Models\Image;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;

trait Commentable {

    //comments relationship ordered by parent_id
    public function comments() {
        return $this->morphMany(Comment::class, 'commentable')->orderBy('root_id')->orderBy('parent_id');
    }
    //comments relationship not ordered
    public function commentsOrderless() {
        return $this->morphMany(Comment::class, 'commentable');
    }

    //returns when model commented last time (datetime)
    public function getLastCommentedAttribute() {
        $last_comment = $this->commentsOrderless()->orderByDesc('created_at')->first();
        if ($last_comment) {
            return $last_comment['created_at']->format('Y-m-d H:i:s');
        }
        return null;
    }
}

trait Authorable {

    //author relationship
    public function author_rs() {
        return $this->belongsTo(Author::class, 'author_id');
    }

    //author relationship joined with user model
    public function authorUser() {
        return $this->author_rs()->join('users', 'users.id', 'authors.user_id')->select('users.id', 'users.name', 'users.last_name', 'users.thumb');
    }

    public function author() {
        return $this->belongsTo(User::class, 'author_id')->select('id', 'name', 'last_name', 'thumb');
    }

    //adds author attribute. If user_id exists then returns user else returns author models.
    public function getAuthorAttribute() {
        $authorUser = $this->authorUser()->first();
        return $authorUser ? $authorUser : $this->author_rs()->first();
        /*$author = $this->author()->first();
        if (!$author) {
            $author = new Author();
            $author->id = 0;
            $author->name = 'USER';
            $author->last_name = 'DELETED';
            $author->thumb = null;
        }
        return $author;*/
    }

}

trait Detailable {
    //details relationship
    public function details() {
        return $this->morphMany(Detail::class, 'detailable');
    }
}

trait Taggable {

    //tags relationship
    public function tags() {
        return $this->morphToMany(Tag::class, 'taggable', 'taggables');
    }

}

trait Imageable {
    //images relationship
    public function images() {
        return $this->morphMany(Image::class, 'imageable');
    }

    /*public function image() {
        return $this->morphOne(Image::class, 'imageable');
    }*/
}

//not used, just feature
trait EnumValue
{
    public static function getEnumValues()
    {
        $fields = DB::connection((new static)->connection)->select(
            DB::raw("SHOW COLUMNS FROM " . config('database.connections.'.(new static)->connection.'.prefix') .(new static)->getTable())
        );
        $result = [];
        foreach ($fields as $field) {
            $enum = self::parsEnumValues($field->Type);
            if (!empty($enum))
                $result[$field->Field] = $enum;
        }
        return $result;

    }

    private static function parsEnumValues($type)
    {
        preg_match('/^enum\((.*)\)$/', $type, $matches);
        $enum = array();
        if (empty($matches))
            return null;

        foreach (explode(',', $matches[1]) as $value) {
            $v = trim($value, "'");
            $enum = array_add($enum, $v, $v);
        }
        return $enum;
    }
}

trait Shareable
{
    public function getShareFileUrlAttribute() {
        if (isset($this->attributes['share_file_url'])) {
            return asset($this->attributes['share_file_url']);
        }
        return null;
    }
}

trait FavourableTrait {
    public function getInFavouriteAttribute() {
        $type = array_keys(Relation::$morphMap, self::class);
        return Favourable::where('favourable_type', $type ?? null)
            ->where('favourable_id', $this->id ?? null)
            ->where('user_id', User::$currentUser->id ?? null)
            ->exists();
    }

}

//not used
trait HasDescriptionDetail
{
    /*public function descriptionDetail() {
        return $this->morphOne(Detail::class, 'detailable')->where('type', Detail::DESCRIPTION);
    }
    public function getDescriptionAttribute() {
        if ($this->descriptionDetail) {
            return $this->descriptionDetail->value;
        }
        return null;
    }*/
}
