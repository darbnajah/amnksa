@php
    $edit_mode = isset($price_offers_model)? true : false;
@endphp

@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i><span> صيغ عروض السعر</span> -  @lang('site.add')</h1>
                </div>
                <div class="col-md-6 text-left">
                    <a href="{{ route('dashboard.price_offers_models.index') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> @lang('site.back')</a>
                </div>
            </div>

        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-8">
                    <div class="box box-primary">
                        <div class="box-body with-border">
                            <form action="{{ ($edit_mode)?
                                route('dashboard.price_offers_models.update', $price_offers_model->id) :
                                route('dashboard.price_offers_models.store') }}" method="post">
                            {{ csrf_field() }}
                            {{ method_field(($edit_mode)? 'put' : 'post') }}

                                <div class="form-group">
                                    <label>تسمية الصيغة</label>
                                    <input type="text" name="model_name" class="form-control" value="{{ ($edit_mode)? $price_offers_model->model_name : old('model_name') }}">
                                </div>
                                <div class="form-group">
                                    <label>نص الصيغة</label>
                                    <textarea name="model_text" id="editor" class="form-control" style="height: 400px !important;">{{ ($edit_mode)? $price_offers_model->model_text : old('model_text') }}</textarea>
                                </div>
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-save"></i> @lang('site.save') </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

