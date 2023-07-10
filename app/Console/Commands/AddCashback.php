<?php

namespace App\Console\Commands;

use App\Classes\Operation;
use App\Models\Order;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AddCashback extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mawared:add-cashback';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mawared add users cashback';

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
     * @return void
     */
    public function handle()
    {
        $usersToAddNewCashback = User::where('payments_not_in_cashback', '>=', get_setting('min_value_to_get_cashback_balance', 0))->take(30)->get();
        foreach ($usersToAddNewCashback as $user){
            $old_payments_not_in_cashback = $user->payments_not_in_cashback;
            $sumTotals = Order::where('user_id', $user->id)->where('cash_back_id', null)->where('status', Order::STATUS_DELIVERED)->sum('total');
            if($sumTotals < $old_payments_not_in_cashback){
                continue;
            }
            $user->payments_not_in_cashback = 0;
            if($user->save()){
                $cashback_percent = get_setting('cashback_percent', 0);
                if($cashback_percent > 0 && $sumTotals > 0){
                    $cashbackValue = $sumTotals*$cashback_percent/100;
                    $cashbackExpiryAt = Carbon::now()->addDays(get_setting('cashback_expiry_duration', 30));
                    $response = Operation::updateUserBalance($user, $cashbackValue, 'plus', 'cashback', null, $cashbackExpiryAt);
                    Order::where('user_id', $user->id)->where('cash_back_id', null)->where('status', Order::STATUS_DELIVERED)->update(['cash_back_id' => $response['userBalanceObj']->id]);
                }
            }
        }

        $this->info("Add cashback successfully");
    }
}
