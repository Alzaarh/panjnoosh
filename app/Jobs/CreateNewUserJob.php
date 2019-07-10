<?php

namespace App\Jobs;

use App\User;

class CreateNewUserJob extends Job
{
    private $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        User::create($this->data);
        return response()->json(['data' => 'user created'], 201);
    }
}
