@php
    $edit_mode = false;
    $show_permissions = false;
    $permissions = [];
    if(isset($user)){
        $edit_mode = true;
        $permissions = json_decode($user->permissions_json);
        if(auth()->user()->id <= 2 && $user->id > 1){
            $show_permissions = true;
        }
    } else {
        if(auth()->user()->id <= 2){
            $show_permissions = true;
        }
    }


@endphp

@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> المستخدمين -
                        @if($edit_mode)
                            @lang('site.edit')
                        @else
                            @lang('site.create')
                        @endif
                    </h1>
                </div>
                <div class="col-md-6 text-left">
                    <a href="{{ route('dashboard.users.index') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> @lang('site.back')</a>
                </div>
            </div>

        </section>

        <section class="content">
            @include('partials._errors')
            <form action="{{ ($edit_mode)? route('dashboard.users.update', $user->id) : route('dashboard.users.store') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field(($edit_mode)? 'put' : 'post') }}
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="panel panel-primary">
                                    <div class="panel-heading"><i class="fa fa-user"></i> المستخدم</div>
                                    <div class="panel-body">
                                        <div class="form-group" style="display: none">
                                            <label>كود المستخدم</label>
                                            <input type="text" name="code" readonly class="form-control" value="{{ ($edit_mode)? $user->code : (old('code')? old('code') : $user_id) }}">
                                        </div>
                                        <div class="form-group">
                                            <label>الإسم الأول</label>
                                            <input type="text" name="first_name" class="form-control" value="{{ ($edit_mode)? $user->first_name : old('first_name') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>الإسم الأخير</label>
                                            <input type="text" name="last_name" class="form-control" value="{{ ($edit_mode)? $user->last_name : old('last_name') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>رقم جوال 1</label>
                                            <input type="text" name="mobile_1" class="form-control" value="{{ ($edit_mode)? $user->mobile_1 : old('mobile_1') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>رقم جوال 2</label>
                                            <input type="text" name="mobile_2" class="form-control" value="{{ ($edit_mode)? $user->mobile_2 : old('mobile_2') }}">
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <label>الإيميل</label>
                                            <input type="text" name="email" class="form-control" value="{{ ($edit_mode)? $user->email : old('email') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>كلمة المرور</label>
                                            <input type="text" name="password_visible" class="form-control" value="{{ ($edit_mode)? $user->password_visible : old('password_visible') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                @if($show_permissions)
                                <div class="panel panel-danger">
                                    <div class="panel-heading"><i class="fa fa-lock"></i> @lang('site.permissions')</div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                            @foreach($models as $index => $model)
                                                <tr>
                                                    <th class="permissions_td_label"> {{ $sections[$index] }} </th>
                                                    <td  class="permissions_td_actions">
                                                        @if(in_array('r', explode(',', $model)))
                                                            <label><input type="checkbox" name="permissions[]" {{ in_array($index.'_read', $permissions)? 'checked' : null }} value="{{ $index }}_read">@lang('site.read')</label>
                                                        @endif
                                                        @if(in_array('c', explode(',', $model)))
                                                        <label><input type="checkbox" name="permissions[]" {{ in_array($index.'_create', $permissions)? 'checked' : null }} value="{{ $index }}_create">@lang('site.create')</label>
                                                        @endif
                                                        @if(in_array('u', explode(',', $model)))
                                                        <label><input type="checkbox" name="permissions[]" {{ in_array($index.'_update', $permissions)? 'checked' : null }} value="{{ $index }}_update">@lang('site.update')</label>
                                                        @endif
                                                        @if(in_array('d', explode(',', $model)))
                                                        <label><input type="checkbox" name="permissions[]" {{ in_array($index.'_delete', $permissions)? 'checked' : null }} value="{{ $index }}_delete">@lang('site.delete')</label>
                                                    @endif
                                                    @if(in_array('yes', explode(',', $model)))
                                                        <label><input type="checkbox" name="permissions[]" {{ in_array($index.'_yes', $permissions)? 'checked' : null }} value="{{ $index }}_yes"> نعم</label>
                                                    @endif
                                                    @if(in_array('all', explode(',', $model)))
                                                        <label><input type="checkbox" name="permissions[]" {{ in_array($index.'_all', $permissions)? 'checked' : null }} value="{{ $index }}_all"> الكل</label>
                                                    @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                        </div>
                                    </div>
                                </div>
                                @endif
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
