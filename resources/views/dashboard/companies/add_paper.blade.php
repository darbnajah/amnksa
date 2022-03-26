@php
    $edit_mode = isset($paper)? true : false;

@endphp

@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">
            <div class="row">
                <div class="col-md-8">
                    <h1><i class="fa fa-university"></i> @lang('site.companies.title') - {{ $company->company_name_ar }} -  إضافة ورق رسمي</h1>
                </div>
                <div class="col-md-4 text-left">
                    <a href="{{ route('dashboard.companies.show', $company->id) }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> @lang('site.back')</a>
                </div>
            </div>

        </section>

        <section class="content">
            @include('partials._errors')

            <form action="{{ ($edit_mode)?
                                route('dashboard.update_paper', $paper->id) :
                                route('dashboard.store_paper', $company->id) }}"
                  method="post"
                  enctype="multipart/form-data">

                {{ csrf_field() }}
                {{ method_field(($edit_mode)? 'put' : 'post') }}

                <input type="hidden" name="company_id" class="form-control" value="{{ $company->company_id }}">
                <div class="row">
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-body with-border">
                            <div class="form-group">
                                <label>تسمية الورق الرسمي</label>
                                <input type="text" name="paper_name" class="form-control" value="{{ ($edit_mode)? $paper->paper_name : old('paper_name') }}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="box box-primary box_file">
                        <div class="box-header with-border">
                            <label>صورة رأس الصفحة</label>
                            <button type="button" class="btn btn-primary" onclick="triggerInputFile('header_img')"><i class="fa fa-search"></i></button>
                        </div>
                        <div class="box-body with-border">
                            <input type="file"
                                   id="header_img"
                                   name="header_img"
                                   class="form-control"
                                   onchange="readUrl(this)"
                                   value="{{ ($edit_mode)? $paper->header_img : old('header_img') }}"
                                   >

                            @if($edit_mode && $paper->header_img)
                                <img src="{{ asset('storage/'.$paper->header_img) }}" class="img-responsive thumb_preview">
                            @else
                                <img src="{{ asset('img/default_paper_header.jpg') }}" class="img-responsive thumb_preview">
                            @endif
                        </div>
                    </div>
                    <div class="box box-primary box_file">
                        <div class="box-header with-border">
                            <label>صورة أسفل الصفحة</label>
                            <button type="button" class="btn btn-primary" onclick="triggerInputFile('footer_img')"><i class="fa fa-search"></i></button>
                        </div>
                        <div class="box-body with-border">
                            <input type="file"
                                   id="footer_img"
                                   name="footer_img"
                                   class="form-control"
                                   onchange="readUrl(this)"
                                   value="{{ ($edit_mode)? $paper->footer_img : old('footer_img') }}"
                                   >

                            @if($edit_mode && $paper->footer_img)
                                <img src="{{ asset('storage/'.$paper->footer_img) }}" class="img-responsive thumb_preview">
                            @else
                                <img src="{{ asset('img/default_paper_footer.jpg') }}" class="img-responsive thumb_preview">
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                        <div class="box box-primary">
                            <div class="box-body with-border">
                                <div class="form-group text-center">
                                    <br>
                                    <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-save"></i> @lang('site.save') </button>
                                </div>
                            </div>
                        </div>
                    </div>
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
