<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Paper;
use App\Models\Price_offer_bulletins;
use App\Models\Price_offers;
use App\Models\Price_offers_models;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Price_offersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $whr = [];
        if(!Auth::user()->can('privacy_all')){
            $whr[] = ['price_offers.commercial_id', '=', Auth::id()];
        }

        if(!Auth::user()->can('price_offers_read')){
            return response('Unauthorized.', 401);
        }
        $price_offers = DB::table('price_offers')
            ->join('users', 'users.id', '=', 'price_offers.commercial_id')
            ->selectRaw('price_offers.*,
            users.first_name as commercial_first_name,
            users.last_name as commercial_last_name,
            users.mobile_1 as commercial_mobile_1,
            users.mobile_2 as commercial_mobile_2
            ')
            ->where($whr)
            ->orderBy('price_offers.id', 'DESC')
            ->get();
        $paper = Paper::where('is_default', '=', 1)->first();
        $paper_id = ($paper)? $paper->id : 1;

        $papers = Paper::all();
        $price_offers_models = Price_offers_models::all();
        return view('dashboard.price_offers.index', compact('price_offers', 'papers', 'paper_id', 'price_offers_models'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!Auth::user()->can('price_offers_create')){
            return response('Unauthorized.', 401);
        }
        return view('dashboard.price_offers.edit');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Auth::user()->can('price_offers_create')){
            return response('Unauthorized.', 401);
        }
        $params = [
            'customer_name' => $request->customer_name,
            'customer_dealer' => $request->customer_dealer,
            'customer_dealer_mobile' => $request->customer_dealer_mobile,
            'customer_dealer_email' => $request->customer_dealer_email,
            'customer_tel' => $request->customer_tel,
            'customer_city' => $request->customer_city,
            'customer_address' => $request->customer_address,
            'total' => $request->price_offer_total,
            'commercial_id' => auth()->user()->id,
        ];

        $price_offer = Price_offers::create($params);

        if($price_offer && isset($request->bulletins)) {
            $bulletins = explode('::', trim($request->bulletins));

            if(count($bulletins) > 0) {
                for($i = 0; $i < count($bulletins) -1; $i++){
                    $bulletin = explode(';', $bulletins[$i]);

                    Price_offer_bulletins::create([
                        'label' => $bulletin[0],
                        'nb_hours' => $bulletin[1],
                        'nb' => $bulletin[2],
                        'cost' => $bulletin[3],
                        'price_offer_id' => $price_offer->id,
                    ]);
                }
            }
        }

        session()->flash('success', __('site.added_successfully'));
        $data = ['valid' => 1, 'route' => route('dashboard.price_offers.index')];
        return json_encode($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Price_offers  $price_offers
     * @return \Illuminate\Http\Response
     */
    public function show($account, Price_offers $price_offers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Price_offers  $price_offers
     * @return \Illuminate\Http\Response
     */
    public function edit($account, Price_offers $price_offer)
    {
        if(!Auth::user()->can('price_offers_update')){
            return response('Unauthorized.', 401);
        }
        $price_offer = DB::table('price_offers')
            ->join('users', 'users.id', '=', 'price_offers.commercial_id')
            ->where('price_offers.id', '=', $price_offer->id)
            ->selectRaw('price_offers.*,
            users.first_name as commercial_first_name,
            users.last_name as commercial_last_name,
            users.mobile_1 as commercial_mobile_1,
            users.mobile_2 as commercial_mobile_2
            ')
            ->get()->first();


        $bulletins = Price_offer_bulletins::where('price_offer_id', '=', $price_offer->id)->get();

        $price_offer_model = Price_offers_models::find($price_offer->model_id);

        return view('dashboard.price_offers.edit', compact(
            'price_offer',
            'bulletins',
            'price_offer_model'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Price_offers  $price_offers
     * @return \Illuminate\Http\Response
     */
    public function update($account, Request $request, Price_offers $price_offer)
    {
        if(!Auth::user()->can('price_offers_update')){
            return response('Unauthorized.', 401);
        }
        $price_offer_id = $price_offer->id;
        $params = [
            'customer_name' => $request->customer_name,
            'customer_dealer' => $request->customer_dealer,
            'customer_dealer_mobile' => $request->customer_dealer_mobile,
            'customer_dealer_email' => $request->customer_dealer_email,
            'customer_tel' => $request->customer_tel,
            'customer_city' => $request->customer_city,
            'customer_address' => $request->customer_address,
            'total' => $request->price_offer_total,
            'commercial_id' => auth()->user()->id,
        ];

        $price_offer = Price_offers::where('id', $price_offer_id)->update($params);

        if($price_offer && isset($request->bulletins)) {
            $bulletins = explode('::', trim($request->bulletins));

            if(count($bulletins) > 0) {

                Price_offer_bulletins::where('price_offer_id', '=', $price_offer_id)->delete();

                for($i = 0; $i < count($bulletins) -1; $i++){
                    $bulletin = explode(';', $bulletins[$i]);

                    Price_offer_bulletins::create([
                        'label' => $bulletin[0],
                        'nb_hours' => $bulletin[1],
                        'nb' => $bulletin[2],
                        'cost' => $bulletin[3],
                        'price_offer_id' => $price_offer_id,
                    ]);
                }
            }
        }

        session()->flash('success', __('site.updated_successfully'));
        $data = ['valid' => 1, 'route' => route('dashboard.price_offers.index')];
        return json_encode($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Price_offers  $price_offers
     * @return \Illuminate\Http\Response
     */
    public function destroy($account, Price_offers $price_offer)
    {
        if(!Auth::user()->can('price_offers_delete')){
            return response('Unauthorized.', 401);
        }
        $rs = $price_offer->delete();
        DB::table('price_offer_bulletins')
            ->where('price_offer_id', $price_offer->id)
            ->delete();
        if($rs){
            session()->flash('success', __('site.deleted_successfully'));
            return redirect()->route('dashboard.price_offers.index');
        }
    }

    public function preview($account, $id, $paper_id = null, $signature = null, $cachet = null){
        if(!Auth::user()->can('price_offers_read')){
            return response('Unauthorized.', 401);
        }
        $price_offer = DB::table('price_offers')
            ->join('users', 'users.id', '=', 'price_offers.commercial_id')
            ->where('price_offers.id', '=', $id)
            ->selectRaw('price_offers.*,
            users.first_name as commercial_first_name,
            users.last_name as commercial_last_name,
            users.mobile_1 as commercial_mobile_1,
            users.mobile_2 as commercial_mobile_2
            ')
            ->get()->first();

        $bulletins = Price_offer_bulletins::where('price_offer_id', '=', $price_offer->id)->get();

        $paper = ($paper_id)?
            Paper::find($paper_id) :
            Paper::where('is_default', '=', 1)->first();

        $price_offer_model = Price_offers_models::find($price_offer->model_id);

        $company = Company::find(1);

        return view('dashboard.price_offers.preview', compact(
            'price_offer',
            'bulletins',
            'paper',
            'price_offer_model',
            'company',
            'signature',
            'cachet'
        ));
    }

    public function accept_price_offer($account, $id){
        $price_offer = DB::table('price_offers')
            ->where('id', $id)
            ->update([
                'status' => 1,
                'accept_dt' => request()->accept_dt,
                'model_id' => request()->model_id
            ]);
        if($price_offer) {
            session()->flash('success', __('تم قبول عرض السعر بنجاح!'));
            return 1;
        }
        return 0;
    }

    public function deny_price_offer($account, $id){
        $price_offer = DB::table('price_offers')
            ->where('id', $id)
            ->update([
                'status' => -1,
                'notes' => request()->notes
            ]);
        if($price_offer) {
            session()->flash('success', __('تم رفض عرض السعر بنجاح!'));
            return 1;
        }
        return 0;
    }

    public function reset_price_offer($account, $id){
        $price_offer = DB::table('price_offers')
            ->where('id', $id)
            ->update([
                'status' => 0,
                'notes' => NULL,
                'accept_dt' => NULL,
                'model_id' => NULL,
            ]);
        if($price_offer) {
            session()->flash('success', __('تم إرجاع عرض السعر لحالته العادية بنجاح!'));
            return 1;
        }
        return 0;
    }

}
