<?php

namespace App\Http\Controllers\Api\V3;
use App\Http\Controllers\Controller;
use App\Http\Resources\V3\AddressResource;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App;


class PageController extends Controller
{
   
    public function __construct(Request $request)
    {
        App::setLocale($request->header('lang'));
    }
   
    public function index(Request $request)
    {
          
         $page = Page::select('title','content')->get();

         return $this->sendResponse($page , trans('messages.get_data_success'));
    }

    public function page(Request $request, $slug)
    {
       
        $page = Page::where('slug', $slug)->first();

        if(!$page){

            return $this->sendError([], trans('messages.not_found_data'), 404);
        }
        
          return $this->sendResponse(['title' => $page->title,'content' => strip_tags($page->content)], trans('messages.get_data_success'));
    }

}
