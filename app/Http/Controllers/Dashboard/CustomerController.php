<?php

namespace App\Http\Controllers\Dashboard;

use App\Classes\Checkout;
use App\Classes\Operation;
use App\Exports\CommonExport;
use App\Http\Controllers\Controller;
use App\Imports\OrdersImport;
use App\Imports\CustomersImport;
use App\Models\Conversation;
use App\Models\Order;
use App\Models\Rating;
use App\Models\Role;
use App\Models\UserBalance;
use App\User;
use App\Models\City;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{

    public function index(Request $request)
    {
        
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.customers'), 'url' => null],

        ];

        $data['page_title'] = trans('admin.customers_list');
       
        $data['customers'] = $this->searchResult($request, User::query()->where('user_type','=','customer'))->orderBy('id', 'desc')->paginate($request->get('show_result_count', 15))->withQueryString();
       
        if ($request->ajax()) {

            $gridView = view('dashboard.customers.partials.customers_grid', $data)->render();
            $listView = view('dashboard.customers.partials.customers_list', $data)->render();

            return response()->json(['gridView' => $gridView, 'listView' => $listView]);
        }

        return view('dashboard.customers.index', $data);
    }

   
    public function create()
    {
        
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.users_staf'), 'url' => route('users.index')],
            ['name' => trans('admin.create'), 'url' => null],
        ];

        $data['page_title'] = trans('admin.customer');
        $data['data'] = new User;
        $data['roles'] = Role::all();
        $data['cities'] = City::all();

        return view('dashboard.customers.create_edit', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), (new User)->rules());

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->disabled_payment_methods = json_encode($request->get('disabled_payment_methods', []));
        $user->user_type = "customer";
        $user->password = $request->password ? Hash::make($request->password) : Hash::make(uniqid());
        $user->save();

       // Alert::success('نجحت العملية','تمت الاضافة بنجاح');

        return redirect()->route('customers.index')->with('success', 'تم الانشاء بنجاح');
    }

    public function overview(Request $request, $id)
    {
        
        $customer = User::findOrFail($id);

        return $this->showTemplate($request, $customer, __('admin.overview'), 'overview');
    }

    public function orders(Request $request, $id)
    {
       
        $customer = User::findOrFail($id);
        $orders = $customer->orders();
        if ($ordersStatus = $request->orders_status) {
            $orders->where('status', $ordersStatus);
        }

        $data['orders'] = $orders->orderBy('id', 'desc')->paginate(10)->withQueryString();

        return $this->showTemplate($request, $customer, __('admin.orders'), 'orders', $data);
    }

    public function addresses(Request $request, $id)
    {
        $customer = User::findOrFail($id);
        $data['addresses'] = $customer->addresses()->paginate(10)->withQueryString();
        return $this->showTemplate($request, $customer, __('admin.shipping_addresses'), 'addresses', $data);
    }

    protected function showTemplate($request, $customer, $pageTitle, $template, $attributes = [])
    {
        if ($request->ajax()) {

            return view('dashboard.customers.partials.' . $template . '_table', $attributes)->render();
        }

        $data['breadcrumb'] = [
            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.customers'), 'url' => route('customers.index')],
            ['name' => $pageTitle, 'url' => null],
        ];

        $data['page_title'] = $pageTitle;
        $data['customer'] = $customer;
        $data['countStatistics'] = [
            'user_balance' => $customer->user_balance,
            'all_orders' => $customer->orders()->count(),
            'completed_orders' => $customer->orders()->where('orders.status', Order::STATUS_DELIVERED)->count(),
            'processing_orders' => $customer->orders()->whereNotIn('orders.status', [Order::STATUS_CANCELED, Order::STATUS_DELIVERED])->count(),
            'cancelled_orders' => $customer->orders()->where('orders.status', Order::STATUS_RESERVED)->count(),
            'pending_orders' => $customer->orders()->where('orders.status', Order::STATUS_RESERVED)->count(),
            'sum_payments' => $customer->orders()->where('orders.status', Order::STATUS_DELIVERED)->sum('total'),
        ];

        $data = array_merge($data, $attributes);

        return view('dashboard.customers.sections.' . $template, $data);
    }

   
    public function edit($id)
    {

        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.users_staf'), 'url' => route('users.index')],
            ['name' => trans('admin.edit'), 'url' => null],
        ];

        $data['page_title'] = trans('admin.customer');
        $data['data'] = User::findOrFail($id);
        $data['roles'] = Role::all();
        $data['cities'] = City::all();

        return view('dashboard.customers.create_edit', $data);
    
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), $user->rules(true));
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->disabled_payment_methods = json_encode($request->get('disabled_payment_methods', []));
        $user->user_type = "customer";
        $user->password = $request->password ? Hash::make($request->password) : Hash::make(uniqid());
        $user->save();

        //Alert::success('نجحت العملية','تم التعديل بنجاح');

        return redirect()->route('customers.index')->with('success', 'تم التحديث بنجاح');
    }

    
    public function destroy($id)
    {
        //
    }

    public function importExcel(Request $request)
    {
        \Excel::import(new CustomersImport, $request->file('imported_file'));

        return redirect()->back();
    }

    public function exportToExcel(Request $request)
    {
        
        $customers = $this->searchResult($request, User::query()->where('user_type','=','customer'))->orderBy('id', 'desc')->take($request->get('export_result_count', 100))->get();
        $data['rows'] = [];
        $index = 0;
        foreach ($customers as $customer) {
            $city = $customer->city;
            $sumOrdersTotal = $customer->orders()->where('orders.status', Order::STATUS_DELIVERED)->sum('total');
            $countDeliveredOrders = $customer->orders()->where('orders.status', Order::STATUS_DELIVERED)->count();

            $data['rows'][] = [

                'id' => $customer->id,
                'name' => $customer->name,
                'mobile' => $customer->mobile,
                'email' => $customer->email,
                'city' => $city ? $city->name : null,
                'orders_count' => $customer->Orders_count,
            ];
            
            $index++;
         }

         $data['headings'] = [

            '#',
            'الإسم',
            'رقم الجوال',
            'البريد الإلكتروني',
            'المدينة',
            'عدد الطلبات',
        ];

        return \Excel::download(new CommonExport($data), 'customers-list.xlsx');
    }

    protected function searchResult($request, $customers)
    {
        if ($searchWord = $request->get('search_word')) {
            $customers = $customers->where('name', 'like', '%' . $searchWord . '%')->orWhere('mobile', 'like', '%' . $searchWord . '%')->orWhere('email', 'like', '%' . $searchWord . '%');
        }

        if (!is_null($request->status)) {
            $customers = $customers->where('status', $request->status);
        }

        if ($createdAt = $request->created_at) {
            $array = explode(' >> ', $createdAt);
            $customers = $customers->where('created_at', '>=', $array[0])
                ->where('created_at', '<=', $array[1]);
        }


        if ($request->mobile || $request->name) {
            $customers = $customers->where('mobile', $request->mobile)
                ->orWhere('name', $request->name);
        }

        if ($request->city) {
            $customers = $customers->whereHas('addresses', function ($q) use ($request) {
                $q->where('city_id', $request->city);
            });
        }

        return $customers;
    }

    public function balanceAction(Request $request, $customer_id)
    {

        $validator = Validator::make($request->all(), [

            'operation_type' => 'required',
            'balance_value' => 'required',
            'expiry_date' => 'required',
            'balance_reason' => 'required'

        ]);

        if ($validator->fails()) {
            
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $customer = User::findOrFail($customer_id);

        Operation::updateUserBalance($customer, $request->balance_value, $request->operation_type, 'balance', null, $request->expiry_date, $request->balance_reason, auth()->id());

        return redirect()->back();
    }

    public function qualityReport(Request $request)
    {
        
        
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('تقرير الجودة'), 'url' => null],
        ];
        
        $data['page_title'] = trans('تقرير الجودة');
        $sumOrdersDriverRating = max(Rating::where('type', 'driver')->where('ratingable_type', 'orders')->sum('rating'), 1);
        $sumOrdersProductsRating = max(Rating::where('type', 'product')->where('ratingable_type', 'orders')->sum('rating'), 1);
        $sumChatRating = max(Rating::where('type', 'customer_service')->where('ratingable_type', 'chat')->sum('rating'), 1);

        $countChatRating = max(Rating::where('type', 'customer_service')->where('ratingable_type', 'chat')->count(), 1);
        $countOrdersDriverRating = max(Rating::where('type', 'driver')->where('ratingable_type', 'orders')->count(), 1);
        $countOrdersProductsRating = max(Rating::where('type', 'product')->where('ratingable_type', 'orders')->count(), 1);

        $data['statistics'] = [
            
            'customer_services_ratings' => (int) ($sumChatRating/$countChatRating),
            'drivers_ratings' => (int) ($sumOrdersDriverRating/$countOrdersDriverRating),
            'products_ratings' => (int) ($sumOrdersProductsRating/$countOrdersProductsRating),
            'return_products' => Order::where('status', Order::STATUS_RETURNED)->count(),
            'cancel_products' => Order::where('status', Order::STATUS_CANCELED)->count(),
            'products_unavailability' => 0,
            'delivery_time' => '9:15:00',
            'orders_stopping_days' => 0,
            'chats_opining' => Conversation::where('status', 1)->count(),
            'chats_closing' => Conversation::where('status', 0)->count(),
        ];

        return view('dashboard.customers.quality_report', $data);

    }
}
