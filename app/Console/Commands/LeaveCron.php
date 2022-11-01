<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UserLeaves;
class LeaveCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave:cron';

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
    {      //Log::info('$message');
        $this->genrate_leaves();
    }

    public function genrate_leaves()
    {   
        //Log::info('$message1');
        $users =  User::where('joined_at','<=',date('Y-m-d'))->whereNotIn('id', function($query)
        {   
            $query->select('user_id')->from('user_leaves')->where('user_leaves.year', date('Y'));
        })->get();

        $month_list1 = array(
            'months' => array('Jan', 'Apr', 'Jul', 'Oct'),
            'first_half_leave'=> 4,
            'second_half_leave'=> 4,
        );
        $month_list2 = array(
            'months' => array('Feb', 'May', 'Aug', 'Nov'),
            'first_half_leave'=> 2.5,
            'second_half_leave'=> 2
        );
        $month_list3 = array(
            'months' => array('Mar', 'Jun', 'Sep', 'Dec'),
            'first_half_leave'=> 1,
            'second_half_leave'=> 0
        );

        foreach ($users as $key => $user) {
            $timestamp = strtotime($user->joined_at);
            $month = date('M', $timestamp);
            $leave_count = 0;
            $date = date('d', $timestamp);
            $year = date('Y', $timestamp);
            switch ($month) {
                case (in_array($month,$month_list1['months']) && $year <= date('Y')):
                    break;
                case (in_array($month,$month_list2['months']) && $year <= date('Y')):
                    $leave_count = ($this->date_is_first_half($date)) ? $month_list2['first_half_leave'] : $month_list2['second_half_leave']; 
                    break;
                case (in_array($month,$month_list3['months']) && $year <= date('Y')):
                    $leave_count = ($this->date_is_first_half($date)) ? $month_list3['first_half_leave'] : $month_list3['second_half_leave']; 
                    break;
                default:
                $leave_count = 16;
                break;
            }
            $this->insert_user_leaves($user->id,$leave_count);
        }
    }

    public function date_is_first_half($date){
        return ($date <= 15) ? true : false;
    }

    public function insert_user_leaves($user_id,$leave_count){
        
        $userLeaves = new UserLeaves();
        $userLeaves->user_id = $user_id;
        $userLeaves->leave_type_id = 1;
        $userLeaves->leave_count = $leave_count;
        $userLeaves->year = date('Y');
        $userLeaves->save();
    }

}
