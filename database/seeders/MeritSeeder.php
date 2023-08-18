<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Merit;

class MeritSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $merits = [
            'ストーリー展開がいい',
            'キャラクターがいい',
            '文章がいい',
            '絵がいい',
            '写真がいい',
            '勉強になる',
            'わかりやすい',
        ];

        foreach($merits as $m) {
            $array[] = ['merit' => $m];
        }

        Merit::insert($array);
    }
}
