<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function index()
    {

        $vendors = User::role('vendor')->count();
        $customers = User::role('customer')->count();
        $pending = Order::where('order_status', 'pending')->count();

        $complete =  Order::where('order_status', 'complete')->count();


       
// dd( $userArr);

        return view('dashboard.index', [
            'vendors' => $vendors,
            'customers' => $customers,
            'pending' => $pending,
            'complete' => $complete,

        ]);
    }

    public function manageChart(){

        $users = User::role('customer')->select('id', 'created_at')
        ->get()
        ->groupBy(function($date) {
            //return Carbon::parse($date->created_at)->format('Y'); // grouping by years
            return Carbon::parse($date->created_at)->format('m'); // grouping by months
        });

        $usermcount = [];
        $userArr = [];

        foreach ($users as $key => $value) {
            $usermcount[(int)$key] = count($value);
        }

        for($i = 1; $i <= 12; $i++){
        //    $month = date("M", strtotime("$i/12/10"));
            if(!empty($usermcount[$i])){
                $userArr[$i] = $usermcount[$i];    
            }else{
                $userArr[$i] = 0;    
            }
        }

        foreach($userArr as $value){
            $temp= $value;
            $userArrr[]= $temp;


        }

        return response()->json(['success' => true,'data'=>$userArrr]);


    }


    

    public function manageRevChart(){

        $users = Order::select('total', 'created_at')
        ->get()
        ->groupBy(function($date) {
            //return Carbon::parse($date->created_at)->format('Y'); // grouping by years
            return Carbon::parse($date->created_at)->format('m'); // grouping by months
        });


        $usermcount = [];
        $userArr = [];
           $total = 0;
        foreach ($users as $key => $value) {
           
            // $usermcount[(int)$key] = SUM($value);
            foreach($value as $val){
                $total +=  $val->total;

            }

            $usermcount[(int)$key] =  $total;
        }
          
        for($i = 1; $i <= 12; $i++){
        //    $month = date("M", strtotime("$i/12/10"));
            if(!empty($usermcount[$i])){
                $userArr[$i] = $usermcount[$i];    
            }else{
                $userArr[$i] = 0;    
            }
        }

        foreach($userArr as $res){
            $temp= $res;
            $userArrr[]= $temp;


        }

        return response()->json(['success' => true,'data'=>$userArrr]);

    }
}
