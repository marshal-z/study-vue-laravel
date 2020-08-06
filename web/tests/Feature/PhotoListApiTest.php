<?php

namespace Tests\Feature;

use App\Photo; // test対象のモデル
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PhotoListApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function should_正しい構造のjsonを返却する()
    {
        // 五つの写真データを生成する
        factory(Photo::class, 3)->create();

        $response = $this->json('GET', route('photo.index'));

        // 生成した写真データを作成日降順で取得
        $photos = Photo::with(['owner'])->orderBy('created_at', 'desc')->get();

        // data項目の期待値
        $expected_data = $photos->map(function ($photo) {
            return [
                'id' => $photo->id,
                'url' => $photo->url,
                'owner' => [
                    'name' => $photo->owner->name,
                ],
                'likes_count' => 0,
                'liked_by_user' => false,
            ];
        })->all();

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonFragment([
                "data" => $expected_data,
            ]);

    }

}
