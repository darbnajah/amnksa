@extends('layouts.dashboard.app')

@section('content')
    <div class="content-wrapper">
        <input type="hidden" id="route">
        <section class="content-header">
            <div class="row">
                <div class="col-sm-12">
                    <h1 style="margin-bottom: 10px"><i class="fa fa-university"></i>  مركز التكلفة للوسيط</h1>

                </div>
            </div>

        </section>

        <section class="content">
            <div class="box box-primary">
                <div class="box-body with-border" style="min-height: 600px">
                    <div class="row">
                        <div class="col-sm-6 search_inline">
                            <label>العميل</label>
                            <input type="hidden" id="customer_id" value="{{ isset($customer_id)? $customer_id : null }}" readonly>
                            <input type="text" id="customer_name" value="{{ isset($customer_name)? $customer_name : null }}" readonly style="background-color: #eee !important;">
                            <button type="button" class="btn btn-info" onclick="modalCustomers('{{ route('dashboard.customers.modal', 'cost') }}')"><i class="fa fa-search"></i></button>
                            <br>
                            <br>
                        </div>

                        <div class="col-sm-6"></div>
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-3">
                                    <select id="month_id" class="form-control" onchange="resetAllCost(this)">
                                        <option value="">اختر الشهر</option>
                                        @foreach ($months as $month)
                                            <option value="{{ $month['id'] }}">{{ $month['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" id="year" class="form-control" value="{{ date('Y') }}" placeholder="السنة" onkeyup="checkNumber(this); resetAllCost(this)">
                                </div>
                                <div class="col-sm-3">
                                    <input type="checkbox" id="cost_all" onchange="setAllCost(this)" style="width: 30px; height: 30px"> <label for="cost_all" style="position:relative; top: -10px">الكل</label>
                                </div>
                                <div class="col-sm-3 text-left">
                                    <button type="button" class="btn btn-primary btn-block" onclick="loadCustomerCost(0)"> <i class="fa fa-eye"></i> عرض التقرير</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-xs-6 col-sm-6">
                                    <div class="small-box bg-red">
                                        <div class="inner">
                                            <h3 id="suppliers_payments_total" class="currency">0.00</h3>
                                            <p>اجمالي المستحقات على المورد</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-document-text"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6">
                                    <div class="small-box bg-orange">
                                        <div class="inner">
                                            <h3 id="paies_transfered_total" class="currency">0.00</h3>
                                            <p>اجمالي الرواتب المدفوعة</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-ios-list"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-xs-6 col-sm-6">
                                    <div class="small-box bg-aqua">
                                        <div class="inner">
                                            <h3 id="customers_expenses_total" class="currency">0.00</h3>
                                            <p>اجمالي المصاريف على العميل</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-document-text"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6">
                                    <div class="small-box bg-green">
                                        <div class="inner">
                                            <h3 id="net_total" class="currency">0.00</h3>
                                            <p>الصافي </p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-ios-list"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
<div class="modal fade" id="modal_customers" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-danger btn-sm btn-modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">العملاء</h4>
            </div>
            <div class="modal-body" style="padding-bottom: 0">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
<script>
    import Input from "../../../js/Jetstream/Input";
    import Button from "../../../js/Jetstream/Button";
    import Label from "../../../js/Jetstream/Label";
    export default {
        components: {Label, Button, Input}
    }
</script>
