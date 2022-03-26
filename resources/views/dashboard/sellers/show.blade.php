@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> مندوبي التسويق -
                        {{ $seller->first_name }}
                    </h1>
                </div>
                <div class="col-md-6 text-left">
                    <a href="{{ route('dashboard.sellers.index') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> @lang('site.back')</a>
                </div>
            </div>

        </section>

        <section class="content">

            <div class="box box-primary">
                <div class="box-body">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>الإسم المندوب</label>
                            <input type="text" readonly class="form-control" value="{{ $seller->first_name }}">
                        </div>
                        <div class="form-group">
                            <label>طريقة الدفع</label>
                            <input type="text" readonly class="form-control" value="{{ $seller->pm_name }}">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>رقم جوال 1</label>
                            <input type="text" readonly class="form-control" value="{{ $seller->mobile_1 }}">
                        </div>
                        <div class="form-group">
                            <label>رقم جوال 2</label>
                            <input type="text" readonly class="form-control" value="{{ $seller->mobile_2 }}">
                        </div>
                        <div class="form-group">
                            <label>الإيميل</label>
                            <input type="text" readonly  class="form-control" value="{{ $seller->email }}">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>اسم البنك</label>
                            <input type="text" readonly class="form-control" value="{{  $seller->bank_name }}">
                        </div>
                        <div class="form-group">
                            <label>رقم الحساب</label>
                            <input type="text" readonly class="form-control" value="{{ $seller->bank_account }}">
                        </div>
                        <div class="form-group">
                            <label>ايبان</label>
                            <input type="text" readonly class="form-control" value="{{  $seller->bank_iban}}">
                        </div>
                    </div>
                    @if($seller->can_login && auth()->user()->id <= 2)
                    <div class="col-lg-12">
                        <div class="panel panel-danger">
                                <div class="panel-heading">
                                    <label> صلاحية تسجيل الدخول للبرنامج</label>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label>الإيميل</label>
                                        <input type="text" id="email" name="email" class="form-control" value="{{ ($seller->can_login)? $seller->email : old('email') }}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>كلمة المرور</label>
                                        <input type="text" id="password_visible" name="password_visible" class="form-control" value="{{ ($seller->can_login)? $seller->password_visible : old('password_visible') }}" readonly>
                                    </div>
                                    <div class="seller_permissions_wrap" style="display: block">
                                        <hr>
                                        <label><i class="fa fa-lock"></i> @lang('site.permissions')</label>
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <tr>
                                                    <th class="">عروض السعر: </th>
                                                    <td class="permissions_td_actions">
                                                        <label><input type="checkbox" name="permissions[]" value="price_offers_read" {{ (in_array('price_offers_read', $permissions))? 'checked' : null }} disabled>عرض</label>
                                                        <label><input type="checkbox" name="permissions[]" value="price_offers_create" {{ (in_array('price_offers_create', $permissions))? 'checked' : null }} disabled>إضافة</label>
                                                        <label><input type="checkbox" name="permissions[]" value="price_offers_update" {{ (in_array('price_offers_update', $permissions))? 'checked' : null }} disabled>تعديل</label>
                                                        <label><input type="checkbox" name="permissions[]" value="price_offers_delete" {{ (in_array('price_offers_delete', $permissions))? 'checked' : null }} disabled>حذف</label>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    @endif
                    <div class="col-lg-12">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <span>العقود</span>
                            </div>
                            <div class="panel-body">
                                @if($contracts->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>العميل</th>
                                            <th>رقم العقد</th>
                                            <th>العنوان</th>
                                            <th>نسبة المسوق</th>
                                            <th>حالة العقد</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($contracts as $contract)
                                            <tr>
                                                <td>{{ $contract->customer_name }}</td>
                                                <td>{{ $contract->code }}</td>
                                                <td>{{ $contract->address }}</td>
                                                <td class="currency">{{ \App\Helper\Helper::nFormat($contract->seller_commission) }}</td>
                                                <td>
                                                    @if($contract->status == 1)
                                                        <label class="label label-success"><i class="fa fa-check"></i> ساري</label>
                                                    @else
                                                        <label class="label label-danger"><i class="fa fa-warning"></i> مفسوخ</label>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    </div>
                                @else
                                    <h2>@lang('site.no_data_found')</h2>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>

@endsection
<div class="modal fade" id="modal_bulletions" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-danger btn-sm btn-modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">البيانات</h4>
            </div>
            <div class="modal-body" style="padding-bottom: 0">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
