<?php

use Illuminate\Database\Seeder;
use App\User;
use App\AMV;
use App\Contest;

class InitialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => env('API_USER', 'default'),
            'email' => env('API_EMAIL', 'example@example.com'),
            'password' => app('hash')->make(env('API_PW', 'default'))
        ]);

        $amv = AMV::create([
            'title' => 'Affinity',
            'genre' => 'Drama',
            'animes' => "Kanojo to Kanojo no Neko (She and her Cat: Everything Flows)",
            'music' => 'Mammals feat. Flash Forest - Move Slower',
            'description' => "I've always felt like this anime would be perfect to be put into an AMV. It's a very short anime (probably around ~15 min. total across 4 mini-episodes), but it tells a really sweet story about the intimate relationship between a girl who's living alone for the first time, struggling with her studies and work prospects, and her cat, that has been with her since her childhood. It was really interesting to work on this project. On one hand, the very small amount of source material made it difficult to fill up the 4:30 min of the song in a way that made sense and synchronized with the music. On the other hand, it meant I could basically retell pretty much the exact same story that the anime itself told, without really having to make any major cuts. It was also my first (albeit small) venture into altering the video material further than simple color corrections (such as changing the phone screen, cat eye changes, etc.)",
            'poster' => 'affinity.png',
            'bg' => 'affinity_bg.jpg',
            'video' => '156942975',
            'user_id' => 1
        ]);

        $contest = Contest::create([
            'name' => 'Tsubasacon',
            'year' => 2016
        ]);

        $amv->contests()->attach(1, ['award' => 'Drama Finalist']);

    }
}
