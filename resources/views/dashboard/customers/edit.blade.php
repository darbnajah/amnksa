@php
    $edit_mode = false;

    if(isset($customer)){
        $edit_mode = true;
    }

@endphp

@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> العملاء -
                        @if($edit_mode)
                            @lang('site.edit')
                        @else
                            @lang('site.create')
                        @endif
                    </h1>
                </div>
                <div class="col-md-6 text-left">
                    <a href="{{ route('dashboard.customers.index') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> @lang('site.back')</a>
                </div>
            </div>

        </section>


        <section class="content">

            <div class="box box-primary">
                <div class="box-body">
                    @include('partials._errors')
                    <form action="{{ ($edit_mode)? route('dashboard.customers.update', $customer->id) : route('dashboard.customers.store') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field(($edit_mode)? 'put' : 'post') }}

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>رقم العميل <span class="required_field_star">*</span></label>
                                <input type="text" name="code" class="form-control" value="{{ ($edit_mode)? $customer->code : (old('code')? old('code') : $customer_code) }}" required>
                            </div>
                            <div class="form-group">
                                <label>الإسم العميل عربي <span class="required_field_star">*</span></label>
                                <input type="text" name="name_ar" class="form-control" value="{{ ($edit_mode)? $customer->name_ar : old('name_ar') }}" required>
                            </div>
                            <div class="form-group">
                                <label>الإسم العميل انجليزي</label>
                                <input type="text" name="name_en" class="form-control" value="{{ ($edit_mode)? $customer->name_en : old('name_en') }}">
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label>المدينة <span class="required_field_star">*</span></label>
                                    <input type="text" name="city" class="form-control" value="{{ ($edit_mode)? $customer->city : old('city') }}" required>
                                </div>
                                <label>عنوان العميل عربي <span class="required_field_star">*</span></label>
                                <input type="text" name="address_ar" class="form-control" value="{{ ($edit_mode)? $customer->address_ar : old('address_ar') }}" required>
                            </div>
                            <div class="form-group">
                                <label>عنوان العميل انجليزي</label>
                                <input type="text" name="address_en" class="form-control" value="{{ ($edit_mode)? $customer->address_en : old('address_en') }}">
                            </div>
                            <div class="form-group">
                                <label>الرقم الضريبي</label>
                                <input type="text" name="vat" class="form-control" value="{{ ($edit_mode)? $customer->vat : old('vat') }}">
                            </div>

                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>اسم المدير المسؤول <span class="required_field_star">*</span></label>
                                <input type="text" name="responsible" class="form-control" value="{{ ($edit_mode)? $customer->responsible : old('responsible') }}" required>
                            </div>
                            <div class="form-group">
                                <label>رقم الجوال <span class="required_field_star">*</span></label>
                                <input type="text" name="mobile" class="form-control" value="{{ ($edit_mode)? $customer->mobile : old('mobile') }}" required>
                            </div>
                            <div class="form-group">
                                <label>رقم الهاتف</label>
                                <input type="text" name="tel" class="form-control" value="{{ ($edit_mode)? $customer->tel : old('tel') }}">
                            </div>
                            <div class="form-group">
                                <label>الفاكس</label>
                                <input type="text" name="fax" class="form-control" value="{{ ($edit_mode)? $customer->fax : old('fax') }}">
                            </div>
                            <div class="form-group">
                                <label>الإيميل</label>
                                <input type="text" name="email" class="form-control" value="{{ ($edit_mode)? $customer->email : old('email') }}">
                            </div>
                            <div class="form-group">
                                <label>طريقة السداد</label>
                                <select name="payment_method_id" class="form-control">
                                    @foreach ($payments_methods as $pm)
                                        <option value="{{ $pm->id }}"
                                                @if ($pm->id == old('payment_method_id', $pm->id))
                                                selected="selected"
                                            @endif
                                        >{{ $pm->pm_name }}</option>
                                    @endforeach
                                </select>
                            </div>


                        </div>

                        <div class="col-lg-12">
                            <hr>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-save"></i> @lang('site.save')</button>
                            </div>
                        </div>

                    </form>

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
