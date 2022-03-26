@extends('layouts.dashboard.app')

@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i>  صيغ عروض السعر</h1>
                </div>
                <div class="col-md-6 text-left">
                    @if(!Session::has('expiration_dt') && auth()->user()->can('price_offers_models_create'))
                    <a href="{{ route('dashboard.price_offers_models.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> @lang('site.add')</a>
                    @endif
                </div>
            </div>

        </section>

        <section class="content">

            <div class="box box-primary">
                <div class="box-body with-border">
                    @if($price_offers_models)
                        <div class="table-responsive">
                            <table id="example" class="table table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>تسمية الصيغة</th>
                                    <th>نص الصيغة</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($price_offers_models as $price_offers_model)
                                    <tr>
                                        <td class="table_actions">
                                            @if(auth()->user()->can('price_offers_models_update'))
                                            <a href="{{ route('dashboard.price_offers_models.edit', $price_offers_model->id)  }}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i> @lang('site.edit')</a>
                                            @endif
                                            @if(auth()->user()->can('price_offers_models_update') && !$price_offers_model->is_default)
                                                    <button type="button" onclick="deleteDefault('{{ route('dashboard.price_offers_models.delete', $price_offers_model->id) }}')" class="btn btn-danger btn-sm"><i class="fa fa-times"></i></button>
                                            @endif
                                            @if($price_offers_model->is_default)
                                            <label class="badge"><i class="fa fa-check"></i> افتراضي </label>
                                            @else
                                                <button class="btn btn-sm btn-primary" data-id="{{ $price_offers_model->id }}" data-token="{{ csrf_token() }}" onclick="setDefault('{{ route('dashboard.price_offers_models.set_default', $price_offers_model->id) }}')"><i class="fa fa-check-square-o"></i> تحديد افتراضي</button>
                                            @endif
                                        </td>

                                        <td>{{ $price_offers_model->model_name }}</td>
                                        <td><?= $price_offers_model->model_text ?></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="alert alert-warning">@lang('site.no_data_found')</p>
                    @endif

                </div>
            </div>

        </section>
    </div>

@endsection
