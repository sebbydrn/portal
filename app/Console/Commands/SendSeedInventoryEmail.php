<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;

class SendSeedInventoryEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:seed_inventory_email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends seed inventory email';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $request = Request::create(route('monitoring.seed_inventory'), 'GET');
        $response = app()->handle($request);
        $responseBody = json_decode($response->getContent(), true);
        echo $responseBody;
    }
}
