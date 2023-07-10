<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class BankController extends Controller
{
    
    public function index()
    { 
        
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
        ];

        $data['page_title'] = trans('admin.bank_accounts');
        
        $data['banks'] = Bank::whereNotNull('id')->orderBy('created_at','desc')->paginate(20);

        return view('dashboard.banks.index', $data);
    }

    public function create()
    {
        
        $data['breadcrumb'] = [

            ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
            ['name' => trans('admin.bank_accounts'), 'url' => route('banks.index')],
            ['name' => trans('admin.create'), 'url' => null],
        ];
        
        $data['page_title'] = trans('admin.bank_accounts');
        $data['data'] = new Bank;

        return view('dashboard.banks.create_edit', $data);
    }

    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), (new Bank)->rules());

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->except('logo', 'hint_image');
        $bank = Bank::create($data);

        if ($request->hasFile('logo')) {
            $bank->addMedia($request->file('logo'))
                ->withCustomProperties(['root' => 'user_prr'.uniqid()])
                ->toMediaCollection($bank->mediaLogoCollectionName);
        }

        if ($request->hasFile('hint_image')) {
            $bank->addMedia($request->file('hint_image'))
                ->withCustomProperties(['root' => 'user_prr'.uniqid()])
                ->toMediaCollection($bank->mediaHintImageCollectionName);
        }
         
         return  redirect()->route('banks.index')->with('success','تم لانشاء بنجاج');
    }


    public function edit($id)
    {
        
         $data['breadcrumb'] = [

              ['name' => trans('admin.dashboard'), 'url' => route('dashboard')],
              ['name' => trans('admin.bank_accounts'), 'url' => route('banks.index')],
              ['name' => trans('admin.edit'), 'url' => null]
         ];

         $data['page_title'] = trans('admin.bank_accounts');
        
         $data['data'] = Bank::findOrFail($id);

         return view('dashboard.banks.create_edit', $data);
    }

    public function update(Request $request, $id)
    {
        
        $validator = Validator::make($request->all(), (new Bank)->rules());

        if ($validator->fails()) {

              return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->except('logo', 'hint_image');
        $bank = Bank::findOrFail($id);

        $bank->update($data);
        if ($request->get('logo_remove') || $request->hasFile('logo')) {
            $bank->clearMediaCollection($bank->mediaLogoCollectionName);
        }

        if ($request->get('hint_image_remove') || $request->hasFile('hint_image')) {
            $bank->clearMediaCollection($bank->mediaHintImageCollectionName);
        }

        if ($request->hasFile('logo')) {
            $bank->addMedia($request->file('logo'))
                ->withCustomProperties(['root' => 'user_prr'.uniqid()])
                ->toMediaCollection($bank->mediaLogoCollectionName);
        }

        if ($request->hasFile('hint_image')) {
            $bank->addMedia($request->file('hint_image'))
                ->withCustomProperties(['root' => 'user_prr'.uniqid()])
                ->toMediaCollection($bank->mediaHintImageCollectionName);
        }

          return  redirect()->route('banks.index')->with('success','تم التعديل بنجاح');
     }

    public function destroy($id)
    {


        //
    }
}
