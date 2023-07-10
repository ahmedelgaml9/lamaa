<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\Order;

use Illuminate\Support\Facades\Validator;

class FinancialController extends Controller
{
   
    public function index()
    {
        
         $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
        ];

        $data['page_title'] = trans('admin.payment_methods');
        $data['payments']= PaymentMethod::all();
        
        return view('dashboard.financial.index', $data);

    }

    public function edit($id)
    {
       
         $data['breadcrumb'] = [

              ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
              ['name' => trans('admin.payment_methods'), 'url' => route('financial.index')],
              ['name' => trans('admin.edit'), 'url' => null]
         ];

         $data['page_title'] = trans('admin.payment_methods');
         $data['data']= PaymentMethod::findOrFail($id);

         return view('dashboard.financial.create_edit', $data);
    }

 
    public function update(Request $request, $id)
    {
        
        $update = PaymentMethod::find($id);
        $update->update($request->all());
      
        return  redirect()->route('financial.index')->with('success','تم التعديل بنجاح');
    }

    public function orders()
    {
        
        $data['breadcrumb']=[

             ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
             ['name' => trans('admin.sales'), 'url' => null],
        ];

        $data['page_title'] = trans('admin.sales');
        $data['sales'] = Order::paginate(20);
        
        return view('dashboard.campaigns.sales', $data);

    }

    public function show_invoices(Request $request)
    {
        
         $data['breadcrumb'] = [

             ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
             ['name' => trans('admin.sales'), 'url' => null],

         ];

         $data['page_title'] = trans('admin.sales');
         $data['sales'] = Order::find($id);

         return view('dashboard.campaigns.sales_results', $data);

    }

    public function financial_orders(Request $request)
    {
        
         $data['breadcrumb'] = [
 
              ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
              ['name' => trans('admin.orders'), 'url' => null],
        
          ];

          $data['page_title'] = trans('admin.orders_list');
          $data['orders'] = Order::where('status',8)->get();

         return view('dashboard.financial.orders', $data);
    }
}
