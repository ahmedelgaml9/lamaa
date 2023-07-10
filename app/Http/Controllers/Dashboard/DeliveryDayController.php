<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use App\Models\DeliveryDay;
use App\Models\DeliveryTime;
use App\Models\City;
use Illuminate\Http\Request;


class DeliveryDayController extends Controller
{

    public function index()
    {

        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],

        ];

        $data['page_title'] = trans('admin.deliverytime');
        $data['deliverydays'] = DeliveryDay::paginate(10);

        return view('dashboard.deliveryday.index',$data);

    }

    public function create()
    {

        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.deliverytime'), 'url' => null],
            ['name' => trans('admin.create'), 'url' => null]

        ];

        $data['page_title'] = trans('admin.deliverytime');

        $data['cities'] = City::all();
        $data['day'] = new DeliveryDay;
        $data['cities'] = City::all();
        $data['periods']  = DeliveryTime::where('delivery_day_id',0)->get();


        return view('dashboard.deliveryday.create',$data);
    }


    public function store(Request $request)
    {

        $insert= new DeliveryDay();
        $insert->day_of_week= $request->day_of_week;
        $insert->status= (boolean) $request->status;
        $insert->save();

        if($request->has('start_time'))
        {
            $starttime= $request['start_time'];
            $endtime=$request['end_time'];
            $city = $request['city_id'];
            $max_orders_to_accept = $request['max_orders_to_accept'];

            for( $i =0 ; $i < count($starttime); $i++)
            {
                if (!empty($starttime[$i])) {

                    $time= new DeliveryTime();
                    $time->delivery_day_id =$insert->id;
                    $time->start_time=$starttime[$i];
                    $time->max_orders_to_accept =isset($max_orders_to_accept[$i])?$max_orders_to_accept[$i]:0;
                    $time->end_time =$endtime[$i];
                    $time->city_id= $city[$i];
                    $time->save();

                }
            }
         }
                    
         return redirect('admin/deliverydays');
    }

    public function edit($id)
    {
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.deliverytime'), 'url' => null],
            ['name' => trans('admin.edit'), 'url' => null]

        ];

        $data['page_title'] = trans('admin.deliverytime');
        $data['day'] = DeliveryDay::find($id);
        $data['cities'] = City::all();
        $data['periods']  = DeliveryTime::where('delivery_day_id',$id)->get();

        return view('dashboard.deliveryday.edit',$data);

    }

    public function update(Request $request, $id)
    {

        $insert = DeliveryDay::find($id);
        $insert->day_of_week= $request->day_of_week;
        $insert->status= (boolean) $request->status;
        $insert->save();

        if($request->has('start_time'))
        {
            $starttime= $request['start_time'];
            $endtime=$request['end_time'];
            $city = $request['city_id'];
            $max_orders_to_accept = $request['max_orders_to_accept'];
            $delete = DeliveryTime::where('delivery_day_id',$id)->delete();
            for( $i =0 ; $i<count($starttime); $i++)
            {
                if (!empty($starttime[$i])) {

                    $time= new DeliveryTime();
                    $time->delivery_day_id =$id;
                    $time->start_time=$starttime[$i];
                    $time->end_time =$endtime[$i];
                    $time->city_id = $city[$i];
                    $time->max_orders_to_accept =isset($max_orders_to_accept[$i])?$max_orders_to_accept[$i]:0;
                    $time->save();

                }
            }
        }

        return redirect('admin/deliverydays');

    }

    public function destroy($id)
    {
        $delete= DeliveryDay::destroy($id);

        if($delete)
        {
            $deletetimes = DeliveryTime::where('delivery_day_id',$id)->delete();

            return back();
        }

        else{

            return back();
        }
    }
}
