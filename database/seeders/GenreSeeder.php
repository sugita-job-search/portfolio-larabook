<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Genre;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genres = [
            "小説（ミステリー）",
            "小説（SF）",
            "小説（ファンタジー）",
            "小説（ホラー）",
            "小説（歴史・時代）",
            "小説（恋愛）",
            "小説（ギャグ・コメディー）",
            "小説（その他）",
            "エッセイ・随筆",
            "ノンフィクション",
            "詩歌・戯曲",
            "文芸（その他）",
            "コミック（ミステリー）",
            "コミック（SF）",
            "コミック（ファンタジー）",
            "コミック（ホラー）",
            "コミック（歴史・時代）",
            "コミック（恋愛）",
            "コミック（ギャグ・コメディー）",
            "コミック（その他）",
            "ビジネス",
            "暮らし",
            "趣味",
            "語学",
            "技術・工学",
            "医学",
            "社会科学",
            "人文科学",
            "自然科学",
            "美術・工芸",
            "哲学・宗教・心理",
            "その他",
        ];
        foreach($genres as $g) {
            $array[] = ['genre' => $g];
        }

        Genre::insert($array);
    }
}
