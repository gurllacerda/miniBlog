<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::factory(5)->create();

        Post::factory(12)->make()->each(function ($post) use ($users) {
            $post->user_id = $users->random()->id;
            $post->save();
        }); //create post and associate an User already created 
    }
}
