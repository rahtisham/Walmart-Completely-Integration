<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Walmart\ItemsManager;
use App\Integration\walmart;
use App\Mail\SendMail;
use App\Models\Walmart\Items;
use App\Models\User;
use App\Models\WalmartMarketPlace;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class WalmartGetOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:getOrder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $items = ItemsManager::all();
        foreach ($items as $item)
        {
            $res = $item->marketPlace;
        }
        \Log::info("Status is Pending" .$items);

    }
}
