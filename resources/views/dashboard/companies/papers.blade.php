@php
    $edit_mode = isset($company)? true : false;
@endphp

@extends('layouts.dashboard.app')

@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> @lang('site.companies.title') - {{ $company->company_name_ar }} - الأوراق الرسمية</h1>
                </div>
                <div class="col-md-6 text-left">
                    <a href="{{ url()->to('dashboard/companies/'.$company->id.'/add_paper') }}" class="btn btn-success"><i class="fa fa-plus"></i> @lang('site.add_paper')</a>

                    <a href="{{ route('dashboard.companies.index') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> @lang('site.back')</a>
                </div>
            </div>

        </section>

        <section class="content">

            <div class="box box-primary">
                <div class="box-body with-border">
                    <h2><i class="fa fa-university"></i>  شركة الحراسة:  <b>{{ $company->company_name_ar }}</b></h2>
                    @if($papers)
                        <div class="table-responsive">
                            <table id="example" class="table table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('site.companies.paper_name')</th>
                                    <th>@lang('site.companies.company_name_at_paper')</th>
                                    <th>@lang('site.companies.iban')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($papers as $paper)
                                    <tr>
                                        <td class="table_actions">
                                            <a href="{{ route('dashboard.edit_paper', ['id' => $paper->company_id, 'paper_id' => $paper->id])  }}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i> @lang('site.edit')</a>

                                            <form
                                                action="{{ route('dashboard.delete_paper', $paper->id) }}"
                                                method="post" style="display: inline-block">
                                                {{ csrf_field() }}
                                                {{ method_field('delete') }}
                                                <button type="submit" class="delete btn btn-sm btn-danger"><i class="fa fa-times"></i> @lang('site.delete')</button>
                                            </form>

                                            @if($paper->is_default)
                                            <label class="badge"><i class="fa fa-check"></i> افتراضي </label>
                                            @else
                                                <button class="btn btn-sm btn-primary" data-id="{{ $paper->id }}" data-token="{{ csrf_token() }}" onclick="setDefault('{{ route('dashboard.set_default_paper', $paper->id) }}')"><i class="fa fa-check-square-o"></i> تحديد افتراضي</button>
                                            @endif
                                        </td>

                                        <td>{{ $paper->paper_name }}</td>
                                        <td>{{ $paper->company_name_at_paper }}</td>
                                        <td>{{ $paper->iban }}</td>
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
<script>
    import Label from "@/Jetstream/Label";
    export default {
        components: {Label}
    }
</script>
