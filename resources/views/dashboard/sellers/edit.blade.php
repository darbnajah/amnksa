@php
    $edit_mode = false;
    //$permissions = [];
    if(isset($seller)){

        $edit_mode = true;
        //$permissions = json_decode($seller->permissions_json);

    }
    //dd($permissions);
@endphp

@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> مندوبي التسويق -
                        @if($edit_mode)
                            @lang('site.edit')
                        @else
                            @lang('site.create')
                        @endif
                    </h1>
                </div>
                <div class="col-md-6 text-left">
                    <a href="{{ route('dashboard.sellers.index') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> @lang('site.back')</a>
                </div>
            </div>

        </section>

        <section class="content">
            @include('partials._errors')
            <form action="{{ ($edit_mode)? route('dashboard.sellers.update', $seller->id) : route('dashboard.sellers.store') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field(($edit_mode)? 'put' : 'post') }}
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="panel panel-primary">
                                    <div class="panel-heading"><i class="fa fa-user"></i> مندوب التسويق</div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label>الإسم المندوب</label>
                                            <input type="text" name="first_name" class="form-control" value="{{ ($edit_mode)? $seller->first_name : old('first_name') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>رقم جوال 1</label>
                                            <input type="text" name="mobile_1" class="form-control" value="{{ ($edit_mode)? $seller->mobile_1 : old('mobile_1') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>رقم جوال 2</label>
                                            <input type="text" name="mobile_2" class="form-control" value="{{ ($edit_mode)? $seller->mobile_2 : old('mobile_2') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>طريقة الدفع</label>
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
                                </div>
                                <div class="panel panel-success">
                                    <div class="panel-heading">الحساب البنكي</div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label>اسم البنك</label>
                                            <input type="text" name="bank_name" class="form-control" value="{{ ($edit_mode)? $seller->bank_name : old('bank_name') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>رقم الحساب</label>
                                            <input type="text" name="bank_account" class="form-control" value="{{ ($edit_mode)? $seller->bank_account : old('bank_account') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>ايبان</label>
                                            <input type="text" name="bank_iban" class="form-control" value="{{ ($edit_mode)? $seller->bank_iban : old('bank_iban') }}">
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-sm-6">
                                <div class="panel panel-danger">
                                    <div class="panel-heading">                                <span><input type="checkbox" name="can_login" id="can_login" style="position:relative; top: 5px" onclick="toggleCanLogin(this)" {{ ($edit_mode && ($seller->can_login))? 'checked' : null }}><label for="can_login"> صلاحية تسجيل الدخول للبرنامج</label></span>
                                    </div>
                                    <div class="panel-body seller_login_wrap" style="display: {{ ($edit_mode && $seller->can_login)? 'block' : 'none' }}">
                                        <div class="form-group">
                                            <label>الإيميل</label>
                                            <input type="text" id="email" name="email" class="form-control" value="{{ ($edit_mode && ($seller->can_login))? $seller->email : old('email') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>كلمة المرور</label>
                                            <input type="text" id="password_visible" name="password_visible" class="form-control" value="{{ ($edit_mode && ($seller->can_login))? $seller->password_visible : old('password_visible') }}">
                                        </div>
                                        <div class="seller_permissions_wrap" style="display: block">
                                            <hr>
                                            <label><i class="fa fa-lock"></i> @lang('site.permissions')</label>
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <tr>
                                                        <th class="">عروض السعر: </th>
                                                        <td class="permissions_td_actions">
                                                            <label><input type="checkbox" name="permissions[]" value="price_offers_read" {{ ($edit_mode && in_array('price_offers_read', $permissions))? 'checked' : null }}>عرض</label>
                                                            <label><input type="checkbox" name="permissions[]" value="price_offers_create" {{ ($edit_mode && in_array('price_offers_create', $permissions))? 'checked' : null }}>إضافة</label>
                                                            <label><input type="checkbox" name="permissions[]" value="price_offers_update" {{ ($edit_mode && in_array('price_offers_update', $permissions))? 'checked' : null }}>تعديل</label>
                                                            <label><input type="checkbox" name="permissions[]" value="price_offers_delete" {{ ($edit_mode && in_array('price_offers_delete', $permissions))? 'checked' : null }}>حذف</label>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <br>
                                <hr>
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-save"></i> @lang('site.save')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>

@endsection
<script>
    import Label from "@/Jetstream/Label";
    export default {
        components: {Label}
    }
</script>
