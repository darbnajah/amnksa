@php
    $edit_mode = false;

    if(isset($job)){
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
                    <a href="{{ route('dashboard.jobs.index') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> @lang('site.back')</a>
                </div>
            </div>

        </section>

        <section class="content">
            @include('partials._errors')
            <form action="{{ ($edit_mode)? route('dashboard.jobs.update', $job->id) : route('dashboard.jobs.store') }}" method="post">
                {{ csrf_field() }}
                {{ method_field(($edit_mode)? 'put' : 'post') }}
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="form-group">
                            <label>مسمى الوظيفة</label>
                            <input type="text" name="job_name" class="form-control" value="{{ ($edit_mode)? $job->job_name : old('job_name') }}">
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-save"></i> @lang('site.save') </button>
                        </div>
                    </div>
                </div>

            </form>

        </section>
    </div>

@endsection
