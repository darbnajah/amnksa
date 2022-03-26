@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> العملاء -
                        {{ $customer->name_ar }}
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
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>رقم العميل</label>
                            <input type="text" readonly name="code" class="form-control" value="{{ $customer->code }}">
                        </div>
                        <div class="form-group">
                            <label>الإسم العميل عربي</label>
                            <input type="text" readonly name="name_ar" class="form-control" value="{{ $customer->name_ar }}">
                        </div>
                        <div class="form-group">
                            <label>الإسم العميل انجليزي</label>
                            <input type="text" readonly name="name_en" class="form-control" value="{{ $customer->name_en }}">
                        </div>
                        <div class="form-group">
                            <label>المدينة</label>
                            <input type="text" readonly name="city" class="form-control" value="{{ $customer->city }}">
                        </div>
                        <div class="form-group">
                            <label>عنوان العميل عربي</label>
                            <input type="text" readonly name="address_ar" class="form-control" value="{{ $customer->address_ar }}">
                        </div>
                        <div class="form-group">
                            <label>عنوان العميل انجليزي</label>
                            <input type="text" readonly name="address_en" class="form-control" value="{{ $customer->address_en }}">
                        </div>

                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>رقم الجوال</label>
                            <input type="text" readonly name="mobile" class="form-control" value="{{ $customer->mobile }}">
                        </div>
                        <div class="form-group">
                            <label>رقم الهاتف</label>
                            <input type="text" readonly name="tel" class="form-control" value="{{ $customer->tel }}">
                        </div>
                        <div class="form-group">
                            <label>الفاكس</label>
                            <input type="text" readonly name="fax" class="form-control" value="{{ $customer->fax }}">
                        </div>
                        <div class="form-group">
                            <label>الإيميل</label>
                            <input type="text" readonly name="email" class="form-control" value="{{ $customer->email }}">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>الرقم الضريبي</label>
                            <input type="text" readonly name="vat" class="form-control" value="{{ $customer->vat }}">
                        </div>
                        <div class="form-group">
                            <label>المدير المسؤول</label>
                            <input type="text" readonly name="responsible" class="form-control" value="{{ $customer->responsible }}">
                        </div>
                        <div class="form-group">
                            <label>طريقة السداد</label>
                            <input type="text" readonly class="form-control" value="{{ $customer->pm_name }}">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <span>العقود</span>
                                <a href="{{ url()->to('dashboard/contracts/create', $customer->id) }}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> إضافة عقد</a>

                            </div>
                            <div class="panel-body">
                                @if($contracts->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th>رقم العقد</th>
                                            <th>المدينة</th>
                                            <th>العنوان</th>
                                            <th>بداية العقد</th>
                                            <th>نهاية العقد</th>
                                            <th>إجمالي العقد</th>
                                            @if($company->factor)
                                            <th>نسبة المورد</th>
                                            @else
                                            <th>نسبة المسوق</th>
                                            @endif
                                            <th>حالة العقد</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($contracts as $contract)
                                            <tr>
                                                <td class="table_actions">
                                                    <button type="button" onclick="showBulletions({{ $contract->id }}, '{{ route('dashboard.bulletions_modal', $contract->id) }}')" class="btn btn-info btn-sm"><i class="fa fa-list"></i> البيانات</button>

                                                    <a href="{{ route('dashboard.contracts.edit', $contract->id) }}" class="btn btn-warning"><i class="fa fa-edit"></i></a>

                                                    <button type="button" onclick="deleteDefault('{{ route('dashboard.contracts.delete', $contract->id) }}')" class="btn btn-danger"><i class="fa fa-times"></i></button>

                                                </td>
                                                <td>{{ $contract->code }}</td>
                                                <td>{{ $contract->city }}</td>
                                                <td>{{ $contract->address }}</td>
                                                <td>{{ $contract->dt_start }}</td>
                                                <td>{{ $contract->dt_end }}</td>
                                                <td class="currency bold">{{ \App\Helper\Helper::nFormat($contract->contract_total) }}</td>
                                            @if($company->factor)
                                                    <td class="currency">{{ \App\Helper\Helper::nFormat($contract->supplier_commission) }}</td>
                                                @else
                                                    <td class="currency">{{ \App\Helper\Helper::nFormat($contract->seller_commission) }}</td>
                                                @endif
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
