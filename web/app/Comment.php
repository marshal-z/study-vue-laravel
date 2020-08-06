<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /** Jsonに含める属性 */
    protected $visible = [
        'author', 'content',
    ];

    /**
     * リレーションシップ - users テーブル
     */
    public function author()
    {
        return $this->belongsTo('App\User', 'user_id', 'id', 'users');
    }
}
