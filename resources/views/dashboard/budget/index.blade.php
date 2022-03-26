@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-money"></i> الميزانية</h1>
                </div>
                <div class="col-md-6 text-left">
                </div>
            </div>

        </section>
        <section class="content">
            <div class="box box-primary">
                <div class="box-body row">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-6">
                        <div class="table-responsive">
                        <table class="table table-hover budget_table">
                            <tbody>
                            <tr>
                                <th>الفواتير المسددة</th>
                                <th class="currency">{{ \App\Helper\Helper::nFormat($budget->payed_invoices) }}</td>
                            </tr>
                            <tr>
                                <th>ايرادات اضافية</th>
                                <th class="currency">{{ \App\Helper\Helper::nFormat(0) }}</td>
                            </tr>
                            <tr>
                                <th>التحصيل</th>
                                <th class="currency">{{ \App\Helper\Helper::nFormat(0) }}</td>
                            </tr>
                            <tr>
                                <th>نسبة الشركة</th>
                                <th class="currency">{{ \App\Helper\Helper::nFormat(0) }}</td>
                            </tr>
                            <tr>
                                <th>الفواتير الغير مسددة</th>
                                <th class="currency">{{ \App\Helper\Helper::nFormat(0) }}</td>
                            </tr>
                            <tr>
                                <th>الضرائب المسددة</th>
                                <th class="currency">{{ \App\Helper\Helper::nFormat(0) }}</td>
                            </tr>
                            <tr>
                                <th>الضرائب الغير مسددة</th>
                                <th class="currency">{{ \App\Helper\Helper::nFormat(0) }}</td>
                            </tr>
                            <tr>
                                <th>سلف على المسوقين</th>
                                <th class="currency">{{ \App\Helper\Helper::nFormat(0) }}</td>
                            </tr>
                            <tr>
                                <th>خصم على المسوقين</th>
                                <th class="currency">{{ \App\Helper\Helper::nFormat(0) }}</td>
                            </tr>
                            <tr>
                                <th>المبالغ المدفوعة للمسوقين</th>
                                <th class="currency">{{ \App\Helper\Helper::nFormat(0) }}</td>
                            </tr>
                            <tr>
                                <th>سلف على الموظفين</th>
                                <th class="currency">{{ \App\Helper\Helper::nFormat(0) }}</td>
                            </tr>
                            <tr>
                                <th>خصم على الموظفين</th>
                                <th class="currency">{{ \App\Helper\Helper::nFormat(0) }}</td>
                            </tr>
                            <tr>
                                <th>رواتب الموظفين</th>
                                <th class="currency">{{ \App\Helper\Helper::nFormat(0) }}</td>
                            </tr>
                            <tr>
                                <th>المشتريات</th>
                                <th class="currency">{{ \App\Helper\Helper::nFormat(0) }}</td>
                            </tr>
                            <tr>
                                <th>المصروفات</th>
                                <th class="currency">{{ \App\Helper\Helper::nFormat(0) }}</td>
                            </tr>
                            <tr class="budget_total">
                                <th>صافي الميزانية الحقيقي</th>
                                <th class="currency">{{ \App\Helper\Helper::nFormat(0) }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    </div>
                    <div class="col-sm-2"></div>

                </div>
            </div>

        </section>
    </div>

@endsection
