<?php

use Illuminate\Database\Seeder;
use App\User;
use App\AMV;
use App\Contest;
use App\Genre;

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
            'password' => app('hash')->make(env('API_PW', 'default')),
            'location' => 'Germany',
            'studio' => 'None',
            'avatar' => '/img/avatars/allaire.png'
        ]);

        $affinity = AMV::create([
            'title' => 'Affinity',
            'animes' => "Kanojo to Kanojo no Neko (She and her Cat: Everything Flows)",
            'music' => 'Mammals feat. Flash Forest - Move Slower',
            'description' => "I've always felt like this anime would be perfect to be put into an AMV. It's a very short anime (probably around ~15 min. total across 4 mini-episodes), but it tells a really sweet story about the intimate relationship between a girl who's living alone for the first time, struggling with her studies and work prospects, and her cat, that has been with her since her childhood. It was really interesting to work on this project. On one hand, the very small amount of source material made it difficult to fill up the 4:30 min of the song in a way that made sense and synchronized with the music. On the other hand, it meant I could basically retell pretty much the exact same story that the anime itself told, without really having to make any major cuts. It was also my first (albeit small) venture into altering the video material further than simple color corrections (such as changing the phone screen, cat eye changes, etc.)",
            'poster' => 'affinity.png',
            'bg' => 'affinity_bg.jpg',
            'video' => '186385552',
            'published' => true,
            'user_id' => 1
        ]);

        $reliance = AMV::create([
            'title' => 'Reliance',
            'animes' => 'Sora No Woto (Sound of the Sky)',
            'music' => 'Lucia - Yours',
            'description' => "This was my submission for the Animaco 2016 Exclusive AMV contest. Sora No Woto is one of my favorite animes, and while there are some decent AMVs for it out there, I felt like none really captured the idea of hope in a hopeless setting, which the anime so strongly conveyed. So I decided to make my own. I'm still not very happy with how it turned out, and I feel like I have gone a bit overboard with the drama aspect, given how the anime generally has a very positive and innocent vibe to it (for the most part). I'm blaming the song for that one!",
            'poster' => 'reliance.png',
            'bg' => 'reliance_bg.jpg',
            'video' => '156942975',
            'published' => false,
            'user_id' => 1
        ]);

        Genre::create(['name' => 'Drama']);
        Genre::create(['name' => 'Comedy']);
        Genre::create(['name' => 'Action']);
        Genre::create(['name' => 'Sentimental']);
        Genre::create(['name' => 'Romance']);
        Genre::create(['name' => 'Horror']);
        Genre::create(['name' => 'Upbeat']);
        Genre::create(['name' => 'Trailer']);
        Genre::create(['name' => 'Character Profile']);
        Genre::create(['name' => 'Instrumental']);
        Genre::create(['name' => 'Other']);

        $contest = Contest::create([
            'name' => 'Tsubasacon',
            'year' => 2016
        ]);

        $affinity->contests()->attach(1, ['award' => 'Best Drama']);
        $affinity->genres()->attach([1, 4, 11]);

        $reliance->genres()->attach(1);

    }
}
