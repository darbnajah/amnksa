@php
    $edit_mode = false;

    if(isset($role)){
        $edit_mode = true;
    }
@endphp

@extends('layouts.dashboard.app')

@section('content')


    <div class="content-wrapper">

        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> الوظائف -
                        @if($edit_mode)
                            @lang('site.edit')
                        @else
                            @lang('site.create')
                        @endif
                    </h1>
                </div>
                <div class="col-md-6 text-left">
                    <a href="{{ route('dashboard.roles.index') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> @lang('site.back')</a>
                </div>
            </div>

        </section>

        <section class="content">
            @include('partials._errors')
            <form action="{{ route('dashboard.roles.store') }}" method="post">
                {{ csrf_field() }}
                {{ method_field('post') }}
                <div class="box box-primary">
                    <div class="box-body">

                        <div class="form-group">
                            <label><span>كود الوظيفة</span>
                                <i class="fa fa-question-circle"
                                   data-toggle="tooltip" data-html="true"
                                   data-placement="top"
                                   style="font-size: 18px"
                                   title="<ul dir='ltr' style='text-align: left'>
                                        <li>English lowercase characters (a – z)</li>
                                        <li>Underscore ( _ )</li>
                                    </ul>"></i>
                            </label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                        </div>
                        <div class="form-group">
                            <label>اسم الوظيفة</label>
                            <input type="text" name="display_name" class="form-control" value="{{ old('display_name') }}">
                        </div>

                        <!--
                        <div class="form-group">
                            <label>@lang('site.permissions')</label>
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">

                                    @foreach($models as $index => $model)
                                        <li class="{{ $index == 0 ? 'active' : null }}"><a href="#tab_{{ $model }}" data-toggle="tab" aria-expanded="true">@lang('site.models.'.$model)</a></li>
                                    @endforeach
                                </ul>
                                <div class="tab-content">
                                    @foreach($models as $index => $model)
                                        <div class="tab-pane {{ $index == 0 ? 'active' : null }}" id="tab_{{ $model }}">
                                            <label><input type="checkbox" name="permissions[]" value="create_{{ $model }}">@lang('site.create')</label>
                                            <label><input type="checkbox" name="permissions[]" value="read_{{ $model }}">@lang('site.read')</label>
                                            <label><input type="checkbox" name="permissions[]" value="update_{{ $model }}">@lang('site.update')</label>
                                            <label><input type="checkbox" name="permissions[]" value="delete_{{ $model }}">@lang('site.delete')</label>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                        -->
                    </div>
                </div>
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h4><b><i class="fa fa-lock"></i> @lang('site.permissions')</b></h4>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                @foreach($models as $index => $model)
                                <tr>
                                    <th class="permissions_td_label">@lang('site.models.'.$model): </th>
                                    <td  class="permissions_td_actions">
                                        <label><input type="checkbox" name="permissions[]" value="create_{{ $model }}">@lang('site.create')</label>
                                        <label><input type="checkbox" name="permissions[]" value="read_{{ $model }}">@lang('site.read')</label>
                                        <label><input type="checkbox" name="permissions[]" value="update_{{ $model }}">@lang('site.update')</label>
                                        <label><input type="checkbox" name="permissions[]" value="delete_{{ $model }}">@lang('site.delete')</label>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-save"></i> @lang('site.save') </button>
                </div>

            </form>

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
