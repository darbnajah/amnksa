@php
    $edit_mode = isset($bank)? true : false;

@endphp

@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> @lang('site.companies.title') - {{ $company->company_name_ar }} -  @lang('site.add_bank')</h1>
                </div>
                <div class="col-md-6 text-left">
                    <a href="{{ route('dashboard.companies.show', $company->id) }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> @lang('site.back')</a>
                </div>
            </div>

        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-6">
                    <div class="box box-primary">
                        <div class="box-body with-border">
                            <form action="{{ ($edit_mode)?
                                route('dashboard.update_bank', $bank->id) :
                                route('dashboard.store_bank', $company->id) }}" method="post">
                        {{ csrf_field() }}
                        {{ method_field(($edit_mode)? 'put' : 'post') }}

                            <input type="hidden" name="company_id" class="form-control" value="{{ $company->id }}">

                            <div class="form-group">
                                <label>@lang('site.companies.bank_name')</label>
                                <input type="text" name="bank_name" class="form-control" value="{{ ($edit_mode)? $bank->bank_name : old('bank_name') }}">
                            </div>
                            <div class="form-group">
                                <label>مسمى شركة الحراسات بالبنك بالعربي</label>
                                <input type="text" name="company_name_at_bank" class="form-control" value="{{ ($edit_mode)? $bank->company_name_at_bank : old('company_name_at_bank') }}">
                            </div>
                                <div class="form-group">
                                    <label>مسمى شركة الحراسات بالبنك بالإنجليزي</label>
                                <input type="text" name="company_name_at_bank_en" class="form-control" value="{{ ($edit_mode)? $bank->company_name_at_bank_en : old('company_name_at_bank_en') }}">
                            </div>
                            <div class="form-group">
                                <label>@lang('site.companies.iban')</label>
                                <input type="text" name="iban" class="form-control" value="{{ ($edit_mode)? $bank->iban : old('iban') }}">
                            </div>
                            <div class="form-group">
                                <label>@lang('site.companies.vat_number')</label>
                                <input type="text" name="vat_number" class="form-control" value="{{ ($edit_mode)? $bank->vat_number : old('vat_number') }}">
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
<script>
    import Label from "@/Jetstream/Label";
    import Input from "@/Jetstream/Input";
    import Button from "@/Jetstream/Button";
    export default {
        components: {Button, Input, Label}
    }
</script>
