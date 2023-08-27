<?php

namespace App\Console\Commands;

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
        $email = "admin1@gmail.com";
        $password = "123456";

        $user = $service->creatingAuthor($name, $email, $password);

        $this->info("Defalt user created successfully");
        return $user;
    }
}
