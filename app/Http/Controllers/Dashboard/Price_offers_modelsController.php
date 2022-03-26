<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Price_offers;
use App\Models\Price_offers_models;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Price_offers_modelsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Auth::user()->can('price_offers_models_read')){
            return response('Unauthorized.', 401);
        }
        $price_offers_models = Price_offers_models::all();

        return view('dashboard.price_offers_models.index', compact('price_offers_models'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!Auth::user()->can('price_offers_models_create')){
            return response('Unauthorized.', 401);
        }
        return view('dashboard.price_offers_models.edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Auth::user()->can('price_offers_models_create')){
            return response('Unauthorized.', 401);
        }
        $request->validate([
            'model_name' => 'required',
            'model_text' => 'required'
        ], [
            'model_name.required' => 'تسمية الصيغة ضرورية.',
            'model_text.required' => 'نص الصيغة ضروري.',
        ]);

        $request_data = $request->all();

        Price_offers_models::create($request_data);

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.price_offers_models.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Price_offers_models  $price_offers_models
     * @return \Illuminate\Http\Response
     */
    public function show($account, Price_offers_models $price_offers_models)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Price_offers_models  $price_offers_models
     * @return \Illuminate\Http\Response
     */
    public function edit($account, Price_offers_models $price_offers_model)
    {
        if(!Auth::user()->can('price_offers_models_update')){
            return response('Unauthorized.', 401);
        }
        return view('dashboard.price_offers_models.edit', compact('price_offers_model'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Price_offers_models  $price_offers_models
     * @return \Illuminate\Http\Response
     */
    public function update($account, Request $request, Price_offers_models $price_offers_model)
    {
        if(!Auth::user()->can('price_offers_models_update')){
            return response('Unauthorized.', 401);
        }
        $request->validate([
            'model_name' => 'required',
            'model_text' => 'required'
        ], [
            'model_name.required' => 'تسمية الصيغة ضرورية.',
            'model_text.required' => 'نص الصيغة ضروري.',
        ]);

        $params = [
            'model_name' => $request->model_name,
            'model_text' => $request->model_text,
        ];

        DB::table('price_offers_models')
            ->where('id', $price_offers_model->id)
            ->update($params);

        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.price_offers_models.index');


    }
    public function set_default($account, $id)
    {
        if(!Auth::user()->can('price_offers_models_create')){
            return response('Unauthorized.', 401);
        }
        $price_offers_model = Price_offers_models::find($id);
        if($price_offers_model){
            Price_offers_models::where('id', '>', 0)->update(['is_default' => 0]);
            $price_offers_model->is_default = 1;
            $rs = $price_offers_model->save();
            session()->flash('success', __('site.updated_successfully'));
            return $rs;
        }

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Price_offers_models  $price_offers_models
     * @return \Illuminate\Http\Response
     */

    public function destroy($account, Price_offers_models $price_offers_model)
    {
        if(!Auth::user()->can('price_offers_models_delete')){
            return response('Unauthorized.', 401);
        }

        $rs = $price_offers_model->delete();
        if($rs){
            session()->flash('success', __('site.deleted_successfully'));
            return redirect()->route('dashboard.price_offers_models.index');
        }
    }

    public function delete($account, $id)
    {
        $response = ['status' => 0, 'message' => 'يتعذر الحذف !'];

        $model = Price_offers_models::find($id);
        if(!$model){
            return json_encode($response);
        }
        $price_offers = Price_offers::where('model_id', '=', $id)->get();
        if(count($price_offers)){
            $response['message'] = "يرجى فك ارتباط الصيغة بعروض السعر قبل حذفه !";
            return json_encode($response);
        }

        $rs = $model->delete();
        if($rs){
            $response['status'] = 1;
            session()->flash('success', __('site.deleted_successfully'));
            return json_encode($response);

        }
    }

}
