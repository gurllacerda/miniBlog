<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;

class CleanUpTrashedPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-up-trashed-posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up trashed posts from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
       $count = Post::onlyTrashed()->forceDelete();

       $this->info("Sucesso! Foram removidos definitivamente {$count} posts.");
    }
}
