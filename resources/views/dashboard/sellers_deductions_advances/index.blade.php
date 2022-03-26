
@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> الخصومات والسلف </h1>
                </div>
                <div class="col-md-6 text-left">

                </div>
            </div>
        </section>

        <section class="content">
            <div class="box box-primary">
                <div class="box-body with-border">
                    <div class="row">
                        <div class="col-sm-6 search_inline">
                            <label>مندوب التسويق</label>
                            <input type="hidden" id="seller_id" value="{{ isset($seller_id)? $seller_id : null }}" readonly>
                            <input type="text" id="seller_name" value="{{ isset($seller_name)? $seller_name : null }}" readonly>
                            <button type="button" class="btn btn-default" onclick="modalSellers('{{ route('dashboard.sellers.modal', 'deductions_advances') }}')"><i class="fa fa-search"></i></button>

                        </div>
                    </div>
                </div>
            </div>
            <div class="box box-primary deductions_advances_wrap" style="display: {{ isset($seller_id)? 'block' : 'none' }}">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="panel panel-warning">
                                <div class="panel-heading">
                                    <span>السلف</span>
                                    @if(auth()->user()->can('sellers_deductions_advances_create'))
                                    <button type="button" class="btn btn-warning btn-sm" onclick="showModalAddDeductionAdvance('advance')"><i class="fa fa-plus"></i> إضافة سلفة</button>
                                    @endif
                                </div>
                                <div class="panel-body">
                                    <div class="box box-primary">
                                        <div class="box-header with-border">
                                            <input type="text" name="search" class="form-control"  id="advances_search" placeholder="@lang('site.search')">
                                        </div>
                                    </div>
                                <?= $advances ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="panel panel-danger">
                                <div class="panel-heading">
                                    <span>الخصومات</span>
                                    @if(auth()->user()->can('sellers_deductions_advances_create'))
                                    <button type="button" class="btn btn-danger btn-sm" onclick="showModalAddDeductionAdvance('deduction')"><i class="fa fa-plus"></i> إضافة خصم</button>
                                    @endif

                                </div>
                                <div class="panel-body">
                                    <div class="box box-primary">
                                        <div class="box-header with-border">
                                            <input type="text" name="search" class="form-control"  id="deductions_search" placeholder="@lang('site.search')">
                                        </div>
                                    </div>
                                <?= $deductions ?>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </section>
    </div>

@endsection

<div class="modal fade" id="modal_sellers" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-danger btn-sm btn-modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">مندوبي التسويق</h4>
            </div>
            <div class="modal-body" style="padding-bottom: 0">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_add_advance" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-danger btn-sm btn-modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">إضافة سلفة جديدة</h4>
            </div>
            <div class="modal-body" style="padding: 20px">
                <div class="form-group">
                    <label>التاريخ</label>
                    <input type="date" class="form-control advance_dt">
                </div>
                <div class="form-group">
                    <label>البيان</label>
                    <input type="text" class="form-control advance_label" placeholder="البيان">
                </div>
                <div class="form-group">
                    <label>القيمة</label>
                    <input type="text" class="form-control advance_debit" placeholder="القيمة" onkeyup="checkNumber(this)">
                </div>
            </div>
            <div class="modal-footer">
                <div class="pull-right">
                    <button type="button" class="btn btn-default btn-lg" data-dismiss="modal">إغلاق</button>
                </div>
                <div class="pull-left">
                    <button type="button" class="btn_pay_invoice btn btn-warning btn-lg" onclick="saveSellerDeductionsAdvance('{{ route('dashboard.sellers_deductions_advances.store') }}', 'advance')"><i class="fa fa-save"></i> حفظ</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_add_deduction" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-danger btn-sm btn-modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">إضافة خصم جديد</h4>
            </div>
            <div class="modal-body" style="padding: 20px">
                <div class="form-group">
                    <label>التاريخ</label>
                    <input type="date" class="form-control deduction_dt">
                </div>
                <div class="form-group">
                    <label>البيان</label>
                    <input type="text" class="form-control deduction_label" placeholder="البيان">
                </div>
                <div class="form-group">
                    <label>القيمة</label>
                    <input type="text" class="form-control deduction_debit" placeholder="القيمة" onkeyup="checkNumber(this)">
                </div>
            </div>
            <div class="modal-footer">
                <div class="pull-right">
                    <button type="button" class="btn btn-default btn-lg" data-dismiss="modal">إغلاق</button>
                </div>
                <div class="pull-left">
                    <button type="button" class="btn_pay_invoice btn btn-danger btn-lg" onclick="saveSellerDeductionsAdvance('{{ route('dashboard.sellers_deductions_advances.store') }}', 'deduction')"><i class="fa fa-save"></i> حفظ</button>
                </div>
            </div>
        </div>
    </div>
</div>
