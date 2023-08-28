<?php

namespace App\Console\Commands;

use App\Models\Posts;
use App\Models\User;
use App\Services\BlogsServices;
use Illuminate\Console\Command;

class SetupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:required-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(BlogsServices $service)
    {
        // create default user author
        $name = "Ion Plus Technology ";
        $email = "admin@gmail.com";
        $password = "123456";

        $user = $service->creatingAuthor($name, $email, $password);

        Posts::create([
            "titles" => "default title",
            "content" => "default content",
            "published_at" => "default published",
            "author_id" => 1
        ]);

        $this->info("Defalt user and post created successfully");
        return $user;
    }
}
