@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1>
                        <i class="fa fa-university"></i>
                        <span>العقود</span> -
                        <span>{{ $contract->code }}</span>
                    </h1>
                </div>
                <div class="col-md-6 text-left">
                    <a href="{{ route('dashboard.customers.show', $contract->customer_id) }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> @lang('site.back')</a>
                </div>
            </div>

        </section>


        <section class="content">

            <div class="box box-primary">
                <div class="box-body">
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
                                <div class="panel-heading">
                                    <span>البيانات</span>
                                    <a href="{{ url()->to('dashboard/bulletins/create', $contract->id) }}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> إضافة بيان</a>

                                </div>
                                <div class="panel-body">
                                    @if($bulletins->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th></th>
                                                <th>البيان</th>
                                                <th>العدد</th>
                                                <th>التكلفة الشهرية</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($bulletins as $bulletin)
                                                <tr>
                                                    <td class="table_actions">
                                                        <a href="{{ route('dashboard.bulletins.edit', $bulletin->id) }}" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                                                        <form
                                                            action="{{ route('dashboard.bulletins.destroy', $bulletin->id) }}"
                                                            method="post" style="display: inline-block">
                                                            {{ csrf_field() }}
                                                            {{ method_field('delete') }}
                                                            <button type="submit" class="delete btn btn-danger"><i class="fa fa-times"></i></button>
                                                        </form>
                                                    </td>
                                                    <td>{{ $bulletin->label }}</td>
                                                    <td>{{ $bulletin->nb }}</td>
                                                    <td>{{ $bulletin->cost }}</td>
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
<script>
    import Label from "@/Jetstream/Label";
    import Input from "@/Jetstream/Input";
    import Button from "@/Jetstream/Button";
    export default {
        components: {Button, Input, Label}
    }
</script>
