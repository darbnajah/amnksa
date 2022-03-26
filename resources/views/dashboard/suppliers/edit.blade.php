@php
    $edit_mode = false;

    if(isset($supplier)){
        $edit_mode = true;
    }

@endphp

@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> الموردين -
                        @if($edit_mode)
                            @lang('site.edit')
                        @else
                            @lang('site.create')
                        @endif
                    </h1>
                </div>
                <div class="col-md-6 text-left">
                    <a href="{{ route('dashboard.suppliers.index') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> @lang('site.back')</a>
                </div>
            </div>

        </section>


        <section class="content">

            <div class="box box-primary">
                <div class="box-body">
                    @include('partials._errors')
                    <form action="{{ ($edit_mode)? route('dashboard.suppliers.update', $supplier->id) : route('dashboard.suppliers.store') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field(($edit_mode)? 'put' : 'post') }}

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>اسم المورد</label>
                                <input type="text" name="supplier_name" class="form-control" value="{{ ($edit_mode)? $supplier->supplier_name : old('supplier_name') }}">
                            </div>
                            <div class="form-group">
                                <label>رقم الجوال</label>
                                <input type="text" name="mobile" class="form-control" value="{{ ($edit_mode)? $supplier->mobile : old('mobile') }}">
                            </div>
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
