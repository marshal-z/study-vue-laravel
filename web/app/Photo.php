<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Photo extends Model
{
    protected $keyType = 'string';
    protected $perPage = 3;
    const ID_LENGTH = 12;

    /** jsonに含める属性 */
    protected $visible = [
        'id', 'url', 'owner', 'comments',
        'likes_count', 'liked_by_user',
    ];

    /** jsonに含める属性 */
    protected $appends = [
        'url', 'likes_count', 'liked_by_user',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (! Arr::get($this->attributes, 'id')) {
            $this->setId();
        }
    }

    /**
     * ランダムなID値をid属性に代入する
     */
    private function setId()
    {
        $this->attributes['id'] = $this->getRandomId();
    }

    /**
     * ランダムなID値を生成する
     */
    private function getRandomId()
    {
        $characters = array_merge(
            range(0, 9), range('a', 'z'),
            range('A', 'Z'), ['-', '_']
        );

        $length = count($characters);
        $id = "";

        for ($i = 0; $i < self::ID_LENGTH; $i++) {
            $id .= $characters[random_int(0, $length - 1)];
        }

        return $id;
    }
    /**
     * リレーションシップ - users テーブル
     */
    public function owner()
    {
        return $this->belongsTo('App\User', 'user_id', 'id', 'users');
    }
    /**
     * リレーションシップ - comments テーブル
     */
    public function comments()
    {
        return $this->hasMany('App\Comment')->orderBy('id', 'desc');
    }

    /**
     * リレーションシップ - users テーブル 多対多(photos テーブルと users テーブルの中間テーブルを定義)
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

     public function likes()
     {
        return $this->belongsToMany('App\User', 'likes')->withTimestamps();
     }

    /**
     * アクセサ url
     * @return string
     */
    public function getUrlAttribute()
    {
        return Storage::cloud()->url($this->attributes['filename']);
    }

    /**
     * アクセサ - likes_count
     */
    public function getLikesCountAttribute()
    {
        return $this->likes->count();
    }

    /**
     * アクセサ - liked_by_user
     * @return boolean
     */
    public function getLikedByUserAttribute()
    {
        if (Auth::guest()) {
            return false;
        }

        return $this->likes->contains(function ($user) {
            return $user->id === Auth::user()->id;
        });
    }

}
