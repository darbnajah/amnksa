@php
    $edit_mode = false;

    if(isset($company)){
        $edit_mode = true;
        $company_id = $company->id;
    }
@endphp

@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> @lang('site.companies.title') -
                        @if($edit_mode)
                        @lang('site.edit')
                        @else
                        @lang('site.create')
                        @endif
                    </h1>
                </div>
                <div class="col-md-6 text-left">
                    @if(auth()->user()->id == 1)
                    <a href="{{ route('dashboard.companies.index') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> @lang('site.back')</a>
                    @endif
                </div>
            </div>

        </section>

        <section class="content">
            @include('partials._errors')
            <form action="{{ ($edit_mode)? route('dashboard.companies.update', $company->id) : route('dashboard.companies.store') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field(($edit_mode)? 'put' : 'post') }}

                <div class="row">
                    <div class="col-md-5">
                        <div class="box box-primary">
                            <div class="box-body with-border">
                                <div class="form-group">
                                    <label>@lang('site.companies.company_id')</label>
                                    <input type="text" name="company_id" readonly class="form-control" value="{{ ($edit_mode)? $company->company_id : (old('company_id')? old('company_id') : $company_id) }}">
                                </div>
                                <div class="form-group">
                                    <label>@lang('site.companies.company_name_ar')</label>
                                    <input type="text" name="company_name_ar" class="form-control" value="{{ ($edit_mode)? $company->company_name_ar : old('company_name_ar') }}">
                                </div>
                                <div class="form-group">
                                    <label>@lang('site.companies.company_name_en')</label>
                                    <input type="text" name="company_name_en" class="form-control" value="{{ ($edit_mode)? $company->company_name_en : old('company_name_en') }}">
                                </div>
                                <div class="form-group">
                                    <label>@lang('site.companies.address_ar')</label>
                                    <input type="text" name="address_ar" class="form-control" value="{{ ($edit_mode)? $company->address_ar : old('address_ar') }}">
                                </div>
                                <div class="form-group">
                                    <label>@lang('site.companies.address_en')</label>
                                    <input type="text" name="address_en" class="form-control" value="{{ ($edit_mode)? $company->address_en : old('address_en') }}">
                                </div>
                                <div class="form-group">
                                    <label>@lang('site.companies.vat_number')</label>
                                    <input type="text" name="vat_number" class="form-control" value="{{ ($edit_mode)? $company->vat_number : old('vat_number') }}">
                                </div>
                                <div class="form-group">
                                    <label>@lang('site.companies.license_number')</label>
                                    <input type="text" name="license_number" class="form-control" value="{{ ($edit_mode)? $company->license_number : old('license_number') }}">
                                </div>
                                <div class="form-group">
                                    <label>@lang('site.companies.commercial_record_date')</label>
                                    <input type="date" name="commercial_record_date" class="form-control" value="{{ ($edit_mode)? $company->commercial_record_date : old('commercial_record_date') }}">
                                </div>
                                <div class="form-group">
                                    <label>@lang('site.companies.license_date')</label>
                                    <input type="date" name="license_date" class="form-control" value="{{ ($edit_mode)? $company->license_date : old('license_date') }}">
                                </div>

                                <div class="form-group">
                                    <label>@lang('site.companies.notes')</label>
                                    <textarea name="notes" class="form-control">{{ ($edit_mode)? $company->notes : old('notes') }}</textarea>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="box box-primary" style="display: {{ ( auth()->user()->id == 1)? 'block' : 'none' }}">
                            <div class="box-header with-border">
                                <label style="font-size: 16px"><i class="fa fa-database"></i>  قاعدة البيانات</label>
                            </div>
                            <div class="box-body with-border">
                                <div class="form-group">
                                    <label>
                                        <span>اسم القاعدة </span>
                                        <i class="fa fa-question-circle"
                                           data-toggle="tooltip"
                                           data-html="true"
                                           data-placement="top"
                                           style="font-size: 18px"
                                           title="<ul dir='ltr' style='text-align: left'>
                                                <li>English lowercase characters (a – z)</li>
                                                <li>Underscore ( _ )</li>
                                            </ul>"></i>
                                    </label>
                                    <input type="text" name="company_db_name" id="company_db_name" class="form-control" value="{{ ($edit_mode)? $company->company_db_name : old('company_db_name') }}">
                                </div>
                                <div class="form-group">
                                    <label>اسم المستخدم للقاعدة</label>
                                    <input type="text" name="company_db_user_name" class="form-control" value="{{ ($edit_mode)? $company->company_db_user_name : old('company_db_user_name') }}" required>
                                </div>
                            </div>
                            <div class="box-header with-border">
                                <label style="font-size: 16px"><i class="fa fa-user"></i> المشرف </label>
                            </div>
                            <div class="box-body with-border">
                            <div class="form-group">
                                    <label>اسم المشرف الأول</label>
                                    <input type="text" name="company_db_user_first_name" class="form-control" value="{{ ($edit_mode)? $company->company_db_user_first_name : old('company_db_user_first_name') }}">
                                </div>
                                <div class="form-group">
                                    <label>اسم المشرف الأخير</label>
                                    <input type="text" name="company_db_user_last_name" class="form-control" value="{{ ($edit_mode)? $company->company_db_user_last_name : old('company_db_user_last_name') }}">
                                </div>
                                <div class="form-group">
                                    <label>إيميل المشرف </label>
                                    <input type="text" name="company_db_user_email" class="form-control" value="{{ ($edit_mode)? $company->company_db_user_email : old('company_db_user_email') }}">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <span> كلمة المرور للمشرف</span>
                                        <i class="fa fa-question-circle"
                                           data-toggle="tooltip"
                                           data-html="true"
                                           data-placement="top"
                                           style="font-size: 18px"
                                           title="<ul dir='ltr' style='text-align: left'>
                                                <li>English uppercase characters (A – Z)</li>
                                                <li>English lowercase characters (a – z)</li>
                                                <li>Base 10 digits (0 – 9)</li>
                                                <li>Non-alphanumeric (For example: !, $, #, or %)</li>
                                                <li>Unicode characters</li>
                                            </ul>"></i>
                                    </label>
                                    <input type="text" name="company_db_user_password" class="form-control" value="{{ ($edit_mode)? $company->company_db_user_password : old('company_db_user_password') }}" dir="ltr">
                                </div>
                            </div>
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <label>تاريخ انتهاء الصلاحية</label>
                                </div>
                                <div class="box-body with-border">
                                    <input type="date" name="expiration_dt" class="form-control" value="{{ ($edit_mode)? $company->expiration_dt : old('expiration_dt') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="box box-primary" style="display: {{ ( auth()->user()->id == 1)? 'block' : 'none' }}">
                            <div class="box-header with-border">
                                <label>طبيعة المستخدم: </label>
                            </div>
                            <div class="box-body with-border factor">
                                @if(!$edit_mode)
                                    <p>
                                        <input type="radio" name="factor[]" value="0" checked><span>مالك الشركة</span> &nbsp;
                                        <input type="radio" name="factor[]" value="1"><span>وسيط</span>

                                    </p>
                                @else
                                    <p>
                                        <input type="radio" name="factor[]" value="0" {{ ($company->factor === 0)? 'checked' : null }}><span>مالك الشركة</span>
                                        &nbsp;
                                        <input type="radio" name="factor[]" value="1" {{ ($company->factor === 1)? 'checked' : null }}><span>وسيط</span>

                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="box box-primary box_file">
                            <div class="box-header with-border">
                                <label>اللوغو</label>
                                <button type="button" class="btn btn-primary" onclick="triggerInputFile('logo')"><i class="fa fa-search"></i></button>
                            </div>
                            <div class="box-body with-border">
                                    <input type="file"
                                           id="logo"
                                           name="logo"
                                           class="form-control"
                                           onchange="readUrl(this)"
                                           value="{{ ($edit_mode)? $company->logo : old('logo') }}">

                                    @if($edit_mode && $company->logo)
                                        <img src="{{ asset('storage/'.$company->logo) }}" class="img-responsive thumb_preview">
                                    @else
                                        <img src="{{ asset('img/0.jpg') }}" class="img-responsive thumb_preview">
                                    @endif
                                </div>
                        </div>
                        <div class="box box-primary box_file">
                            <div class="box-header with-border">
                                <label>الختم</label>
                                <button type="button" class="btn btn-primary" onclick="triggerInputFile('cachet')"><i class="fa fa-search"></i></button>
                            </div>
                            <div class="box-body with-border">
                                <input type="file"
                                       id="cachet"
                                       name="cachet"
                                       class="form-control"
                                       onchange="readUrl(this)"
                                       value="{{ ($edit_mode)? $company->cachet : old('cachet') }}">

                                @if($edit_mode && $company->cachet)
                                    <img src="{{ asset('storage/'.$company->cachet) }}" class="img-responsive thumb_preview">
                                @else
                                    <img src="{{ asset('img/0.jpg') }}" class="img-responsive thumb_preview">
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <h3><b>التوقيعات</b></h3>
                        <br>
                    </div>
                    <div class="col-sm-3">
                        <div class="box box-primary box_file">
                            <div class="box-header with-border">
                                <label>التوقيع 1</label>
                                <button type="button" class="btn btn-primary" onclick="triggerInputFile('sign_accountant')"><i class="fa fa-search"></i></button>
                            </div>
                            <div class="box-body with-border">
                                <input type="text" name="sign_accountant_label" class="form-control" value="{{ ($edit_mode)? $company->sign_accountant_label : old('sign_accountant_label') }}">
                                <br>
                                <input type="file"
                                       id="sign_accountant"
                                       name="sign_accountant"
                                       class="form-control"
                                       onchange="readUrl(this)"
                                       value="{{ ($edit_mode)? $company->sign_accountant : old('sign_accountant') }}">

                                @if($edit_mode && $company->sign_accountant)
                                    <img src="{{ asset('storage/'.$company->sign_accountant) }}" class="img-responsive thumb_preview">
                                @else
                                    <img src="{{ asset('img/0.jpg') }}" class="img-responsive thumb_preview">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="box box-primary box_file">
                            <div class="box-header with-border">
                                <label>التوقيع 2</label>
                                <button type="button" class="btn btn-primary" onclick="triggerInputFile('sign_operational_director')"><i class="fa fa-search"></i></button>
                            </div>
                            <div class="box-body with-border">
                                <input type="text" name="sign_operational_director_label" class="form-control" value="{{ ($edit_mode)? $company->sign_operational_director_label : old('sign_operational_director_label') }}">
                                <br>
                                <input type="file"
                                       id="sign_operational_director"
                                       name="sign_operational_director"
                                       class="form-control"
                                       onchange="readUrl(this)"
                                       value="{{ ($edit_mode)? $company->sign_operational_director : old('sign_operational_director') }}">
                                @if($edit_mode && $company->sign_operational_director)
                                    <img src="{{ asset('storage/'.$company->sign_operational_director) }}" class="img-responsive thumb_preview">
                                @else
                                    <img src="{{ asset('img/0.jpg') }}" class="img-responsive thumb_preview">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="box box-primary box_file">
                            <div class="box-header with-border">
                                <label>التوقيع 3</label>
                                <button type="button" class="btn btn-primary" onclick="triggerInputFile('sign_financial_director')"><i class="fa fa-search"></i></button>
                            </div>
                            <div class="box-body with-border">
                                <input type="text" name="sign_financial_director_label" class="form-control" value="{{ ($edit_mode)? $company->sign_financial_director_label : old('sign_financial_director_label') }}">
                                <br>
                                <input type="file"
                                   id="sign_financial_director"
                                   name="sign_financial_director"
                                   class="form-control"
                                   onchange="readUrl(this)"
                                   value="{{ ($edit_mode)? $company->sign_financial_director : old('sign_financial_director') }}">

                            @if($edit_mode && $company->sign_financial_director)
                                <img src="{{ asset('storage/'.$company->sign_financial_director) }}" class="img-responsive thumb_preview">
                            @else
                                <img src="{{ asset('img/0.jpg') }}" class="img-responsive thumb_preview">
                            @endif
                        </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="box box-primary box_file">
                            <div class="box-header with-border">
                                <label>التوقيع على عرض السعر</label>
                                <button type="button" class="btn btn-primary" onclick="triggerInputFile('sign_price_offer')"><i class="fa fa-search"></i></button>
                            </div>
                            <div class="box-body with-border">
                                <input type="text" name="sign_price_offer_label" class="form-control" value="{{ ($edit_mode)? $company->sign_price_offer_label : old('sign_price_offer_label') }}">
                                <br>
                                <input type="file"
                                   id="sign_price_offer"
                                   name="sign_price_offer"
                                   class="form-control"
                                   onchange="readUrl(this)"
                                   value="{{ ($edit_mode)? $company->sign_price_offer : old('sign_price_offer') }}">

                            @if($edit_mode && $company->sign_price_offer)
                                <img src="{{ asset('storage/'.$company->sign_price_offer) }}" class="img-responsive thumb_preview">
                            @else
                                <img src="{{ asset('img/0.jpg') }}" class="img-responsive thumb_preview">
                            @endif
                        </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <hr style="border-color: #ddd">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-save"></i> @lang('site.save') </button>
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
