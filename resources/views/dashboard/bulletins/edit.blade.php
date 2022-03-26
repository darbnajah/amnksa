@php
    $edit_mode = false;

    if(isset($bulletin)){
        $edit_mode = true;
    }

@endphp

@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> العقود -
                        @if($edit_mode)
                            @lang('site.edit')
                        @else
                            @lang('site.create')
                        @endif
                        بيان
                    </h1>
                </div>
                <div class="col-md-6 text-left">
                    <a href="{{ route('dashboard.contracts.index') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> @lang('site.back')</a>
                </div>
            </div>

        </section>


        <section class="content">

            <div class="box box-primary">
                <div class="box-body">
                    @include('partials._errors')
                    <form action="{{ ($edit_mode)? route('dashboard.bulletins.update', $bulletin->id) : route('dashboard.bulletins.store') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field(($edit_mode)? 'put' : 'post') }}

                        <input type="hidden" name="contract_id" value="{{ $contract->id }}">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>رقم العميل</label>
                                <input type="text" class="form-control" readonly value="{{ $customer->code  }}">
                            </div>
                            <div class="form-group">
                                <label>اسم العميل</label>
                                <input type="text" class="form-control" readonly value="{{ $customer->name_ar  }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>رقم العقد</label>
                                <input type="text" class="form-control" readonly value="{{ $contract->code  }}">
                            </div>
                            <div class="form-group">
                                <label>العنوان</label>
                                <input type="text" class="form-control" readonly value="{{ $contract->address  }}">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="panel panel-info">
                                <div class="panel-heading">البيان</div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label>البيان</label>
                                        <input type="text" name="label" class="form-control" value="{{ ($edit_mode)? $bulletin->label : old('label') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>العدد</label>
                                        <input type="number" name="nb" class="form-control" value="{{ ($edit_mode)? $bulletin->nb : old('nb') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>التكلفة الشهرية</label>
                                        <input type="number" name="cost" class="form-control" value="{{ ($edit_mode)? $bulletin->cost : old('cost') }}">
                                    </div>
                                </div>
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
