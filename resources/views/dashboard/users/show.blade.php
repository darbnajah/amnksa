
@extends('layouts.dashboard.app')

@section('content')
@php
    $permissions = json_decode($user->permissions_json);

@endphp
    <div class="content-wrapper">

        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-user"></i> {{ $user->first_name.' '.$user->last_name }}
                    </h1>
                </div>
                <div class="col-md-6 text-left">
                    <a href="{{ route('dashboard.users.edit', $user->id) }}" class="btn btn-warning"><i class="fa fa-edit"></i> تعديل البروفايل</a>

                </div>
            </div>

        </section>

        <section class="content">

            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading"><i class="fa fa-user"></i> المستخدم</div>
                                <div class="panel-body">
                                    <div class="form-group" style="display: none">
                                        <label>كود المستخدم</label>
                                        <input type="text" name="code" readonly class="form-control" value="{{ $user->code }}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>الإسم الأول</label>
                                        <input type="text" name="first_name" class="form-control" value="{{ $user->first_name }}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>الإسم الأخير</label>
                                        <input type="text" name="last_name" class="form-control" value="{{ $user->last_name }}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>رقم جوال 1</label>
                                        <input type="text" name="mobile_1" class="form-control" value="{{ $user->mobile_1 }}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>رقم جوال 2</label>
                                        <input type="text" name="mobile_2" class="form-control" value="{{ $user->mobile_2 }}" readonly>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <label>الإيميل</label>
                                        <input type="text" name="email" class="form-control" value="{{ $user->email }}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>كلمة المرور</label>
                                        <input type="text" name="password_visible" class="form-control" value="{{ $user->password_visible }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            @if(auth()->user()->id <= 2)
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
                                                                <label><input type="checkbox" name="permissions[]" {{ in_array($index.'_read', $permissions)? 'checked' : null }} value="{{ $index }}_read" disabled>@lang('site.read')</label>
                                                            @endif
                                                            @if(in_array('c', explode(',', $model)))
                                                                <label><input type="checkbox" name="permissions[]" {{ in_array($index.'_create', $permissions)? 'checked' : null }} value="{{ $index }}_create" disabled>@lang('site.create')</label>
                                                            @endif
                                                            @if(in_array('u', explode(',', $model)))
                                                                <label><input type="checkbox" name="permissions[]" {{ in_array($index.'_update', $permissions)? 'checked' : null }} value="{{ $index }}_update" disabled>@lang('site.update')</label>
                                                            @endif
                                                            @if(in_array('d', explode(',', $model)))
                                                                <label><input type="checkbox" name="permissions[]" {{ in_array($index.'_delete', $permissions)? 'checked' : null }} value="{{ $index }}_delete" disabled>@lang('site.delete')</label>
                                                            @endif
                                                            @if(in_array('yes', explode(',', $model)))
                                                                <label><input type="checkbox" name="permissions[]" {{ in_array($index.'_yes', $permissions)? 'checked' : null }} value="{{ $index }}_yes" disabled> نعم</label>
                                                            @endif
                                                            @if(in_array('all', explode(',', $model)))
                                                                <label><input type="checkbox" name="permissions[]" {{ in_array($index.'_all', $permissions)? 'checked' : null }} value="{{ $index }}_all" disabled> الكل</label>
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
                    </div>

                </div>
            </div>

        </section>
    </div>

@endsection
