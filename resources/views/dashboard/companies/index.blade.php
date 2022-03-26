@extends('layouts.dashboard.app')

@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> @lang('site.companies.title')</h1>
                </div>
                <div class="col-md-6 text-left">
                    @if(!Session::has('expiration_dt') && (isset($_SESSION["subdomain"]) && $_SESSION["subdomain"] == 'admin'))
                    <a href="{{ route('dashboard.companies.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> @lang('site.create')</a>
                    @endif
                </div>
            </div>

        </section>

        <section class="content">

            <div class="box box-primary">
                <div class="box-header with-border">
                    <input type="text" name="search" class="form-control"  id="dt_search" placeholder="@lang('site.search')">
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-body with-border">
                    @if($companies->count() > 0)
                        <div class="table-responsive">
                            <table id="example" class="table table-hover">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>اللوغو</th>
                                    <th>@lang('site.companies.company_id')</th>
                                    <th>@lang('site.companies.company_name_ar')</th>
                                    <th>@lang('site.companies.company_name_en')</th>
                                    <th>@lang('site.companies.address_ar')</th>
                                    <th>@lang('site.companies.address_en')</th>
                                    <th>@lang('site.companies.vat_number')</th>
                                    <th>المشرف</th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach($companies as $company)
                                    <tr>
                                        <td class="table_actions">
                                            <a href="{{ route('dashboard.companies.show', $company->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a>
                                            <a href="{{ route('dashboard.companies.edit', $company->id) }}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                                            @if($company->company_id > 1 && (isset($_SESSION["subdomain"]) && $_SESSION["subdomain"] == 'admin'))
                                            <form
                                                action="{{ route('dashboard.companies.destroy', $company->id) }}"
                                                method="post" style="display: inline-block">
                                                {{ csrf_field() }}
                                                {{ method_field('delete') }}
                                                <button type="submit" class="delete btn btn-sm btn-danger"><i class="fa fa-times"></i></button>
                                            </form>
                                            @endif
                                        </td>
                                        <td class="td_img">
                                            @if($company->logo)
                                                <img src="{{ asset('storage/'.$company->logo) }}" class="img-responsive thumb_preview">
                                            @else
                                                <img src="{{ asset('img/0.jpg') }}" class="img-responsive thumb_preview">
                                            @endif
                                        </td>
                                        <td>{{ $company->company_id }}</td>
                                        <td>{{ $company->company_name_ar }}</td>
                                        <td>{{ $company->company_name_en }}</td>
                                        <td>{{ $company->address_ar }}</td>
                                        <td>{{ $company->address_en }}</td>
                                        <td>{{ $company->vat_number }}</td>
                                        <td>{{ $company->company_db_user_first_name. ' '.$company->company_db_user_last_name }}</td>
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
