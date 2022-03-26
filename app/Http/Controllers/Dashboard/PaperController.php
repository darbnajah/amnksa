<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Paper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PaperController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($company_id = null)
    {
        if($company_id){
            $papers = DB::table('papers')->where('company_id', '=', $company_id)->get();
        } else {
            $papers = Paper::all();

        }
        return view('dashboard.papers.index', compact('papers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.papers.edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Paper  $paper
     * @return \Illuminate\Http\Response
     */
    public function show($account, Paper $paper)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Paper  $paper
     * @return \Illuminate\Http\Response
     */
    public function edit($account, Paper $paper)
    {
        return view('dashboard.papers.edit', compact('paper'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Paper  $paper
     * @return \Illuminate\Http\Response
     */
    public function update($account, Request $request, Paper $paper)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Paper  $paper
     * @return \Illuminate\Http\Response
     */
    public function destroy($account, $id)
    {

        $paper = Paper::find($id);
        if($paper){
            Storage::delete('public/'.$paper->header_img);
            Storage::delete('public/'.$paper->footer_img);

            $rs = $paper->delete();
            if($rs) {
                session()->flash('success', __('site.deleted_successfully'));
                return redirect()->route('dashboard.companies.show', 1);
            }
            session()->flash('success', __('site.delete_error'));
            return redirect()->route('dashboard.companies.show', 1);
        }

    }
    public function set_default_paper($account, $id)
    {

        $paper = Paper::find($id);
        if($paper){
            Paper::where('company_id', '=', $paper->company_id)->update(['is_default' => 0]);
            $paper->is_default = 1;
            $rs = $paper->save();
            session()->flash('success', __('site.updated_successfully'));
            return $rs;
        }

    }

    public function bycompany($id)
    {

        $papers = DB::table('papers')->where('company_id', '=', $id)->get();

        return view('dashboard.papers.index', compact( 'papers'));

    }

}
