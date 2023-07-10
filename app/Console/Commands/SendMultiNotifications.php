<?php

namespace App\Console\Commands;

use App\Classes\Notification;
use App\Models\Notification as NotificationModel;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SendMultiNotifications extends Command
{
    
    protected $signature = 'lam3a:send-multi-notifications';
    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
   public function handle()
    {
        $notification = NotificationModel::where('status', 0)->where('created_at', '>=', Carbon::now()->subDays(4))->where('group', '!=', 'private')->orderBy('id', 'ASC')->first();
        
        if($notification){
            
            Notification::sendMultiNotification($notification);
        }
        
        $this->info("Send notification successfully");
    }
}
