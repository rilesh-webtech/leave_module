<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\UserLeaves;

class UserLeave extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
    */
    public function __construct()
    {

    }

    // Get user all leave
    public function getLeave(Request $request)
    {   
        $rules = [
            'user_id' => 'required|integer',
            'leave_type' => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 0,
                'message' => $validator->errors()
            ]);
        }

        if($request->type > 0){
            $leaves = UserLeaves::where('leave_type_id',$request->type)->where('year', date('Y'))->where('user_id',$request->user_id)->get()->first();
        }else{
            $leaves = UserLeaves::where('user_id',$request->user_id)->where('year', date('Y'))->get()->first();
        }
        if(!empty($leaves)){
            return response()->json([
                'status'  => 1,
                'message' => __('User leaves'),
                'total_leave'=> ($leaves) ? $leaves->leave_count : 0,
            ]);
        }else{
            return response()->json([
                'status'  => 0,
                'message' => __('Data not found'),
            ]);
        }
        
    }
}
