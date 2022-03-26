@php
    $edit_mode = isset($company)? true : false;
@endphp

@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> @lang('site.companies.title') - {{ $company->company_name_ar }}</h1>
                </div>
                <div class="col-md-6 text-left">
                    <a href="{{ route('dashboard.companies.edit', $company) }}" class="btn btn-warning"><i class="fa fa-edit"></i> تعديل</a>
                    @if(auth()->user()->id == 1)
                    <a href="{{ route('dashboard.companies.index') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> @lang('site.back')</a>
                    @endif
                </div>
            </div>

        </section>

        <section class="content">

            <div class="row">
                <div class="col-md-4" style="display: none">
                    <div class="box box-primary">
                        <div class="box-body with-border">
                            <div class="form-group">
                                <label>@lang('site.companies.company_name_ar')</label>
                                <input type="text" name="company_name_ar" class="form-control" readonly value="{{ ($edit_mode)? $company->company_name_ar : old('company_name_ar') }}">
                            </div>
                            <div class="form-group">
                                <label>@lang('site.companies.company_id')</label>
                                <input type="text" name="company_id" class="form-control" readonly value="{{ ($edit_mode)? $company->company_id : old('company_id') }}">
                            </div>
                            <div class="form-group">
                                <label>@lang('site.companies.company_name_en')</label>
                                <input type="text" name="company_name_en" class="form-control" readonly value="{{ ($edit_mode)? $company->company_name_en : old('company_name_en') }}">
                            </div>
                            <div class="form-group">
                                <label>@lang('site.companies.address_ar')</label>
                                <input type="text" name="address_ar" class="form-control" readonly value="{{ ($edit_mode)? $company->address_ar : old('address_ar') }}">
                            </div>
                            <div class="form-group">
                                <label>@lang('site.companies.address_en')</label>
                                <input type="text" name="address_en" class="form-control" readonly value="{{ ($edit_mode)? $company->address_en : old('address_en') }}">
                            </div>
                            <div class="form-group">
                                <label>@lang('site.companies.vat_number')</label>
                                <input type="text" name="vat_number" class="form-control" readonly value="{{ ($edit_mode)? $company->vat_number : old('vat_number') }}">
                            </div>
                            <div class="form-group">
                                <label>@lang('site.companies.license_number')</label>
                                <input type="text" name="license_number" class="form-control" readonly value="{{ ($edit_mode)? $company->license_number : old('license_number') }}">
                            </div>
                            <div class="form-group">
                                <label>@lang('site.companies.commercial_record_date')</label>
                                <input type="text" name="commercial_record_date" class="form-control" readonly value="{{ ($edit_mode)? $company->commercial_record_date : old('commercial_record_date') }}">
                            </div>
                            <div class="form-group">
                                <label>@lang('site.companies.license_date')</label>
                                <input type="text" name="license_date" class="form-control" readonly value="{{ ($edit_mode)? $company->license_date : old('license_date') }}">
                            </div>
                            <div class="form-group">
                                <label>@lang('site.companies.notes')</label>
                                <textarea name="notes" class="form-control" readonly>{{ ($edit_mode)? $company->notes : old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <h1>{{ $company->company_name_ar }}</h1>
                    <br>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <label>البنك الإفتراضي</label>
                            <a href="{{ url()->to('dashboard/companies/'.$company->id.'/add_bank') }}" class="btn btn-success"><i class="fa fa-plus"></i> @lang('site.add_bank')</a>

                        </div>
                        <div class="box-body with-border">
                            @if($banks)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('site.companies.bank_name')</th>
                                            <th>المسمى</th>
                                            <th>IBAN</th>
                                            <th>الرقم الضريبي</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($banks as $bank)
                                            <tr>
                                                <td class="">
                                                    <a href="{{ route('dashboard.edit_bank', ['id' => $bank->company_id, 'bank_id' => $bank->id])  }}" class="btn btn-warning btn-block" style="margin-bottom: 5px"><i class="fa fa-edit"></i></a>
                                                    @if($bank->is_default)
                                                        <label class="badge" style="font-size: 22px"><i class="fa fa-check"></i> </label>
                                                    @else
                                                        <form
                                                            action="{{ route('dashboard.delete_bank', $bank->id) }}"
                                                            method="post" style="display: block">
                                                            {{ csrf_field() }}
                                                            {{ method_field('delete') }}
                                                            <button type="submit" class="delete btn btn-danger btn-block" style="margin-bottom: 5px"><i class="fa fa-times"></i></button>
                                                        </form>

                                                        <button class="btn btn-primary btn-block" data-id="{{ $bank->id }}" data-token="{{ csrf_token() }}" onclick="setDefault('{{ route('dashboard.set_default_bank', $bank->id) }}')" style="margin-bottom: 5px"><i class="fa fa-check-square-o"></i></button>
                                                    @endif
                                                </td>

                                                <td>{{ $bank->bank_name }}</td>
                                                <td>
                                                    <p>{{ $bank->company_name_at_bank }}</p>
                                                    <p dir="ltr" class="text-left">{{ $bank->company_name_at_bank_en }}</p>
                                                </td>
                                                <td>{{ $bank->iban }}</td>
                                                <td>{{ $bank->vat_number }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <label>تاريخ انتهاء الصلاحية</label>
                        </div>
                        <div class="box-body with-border">
                            <input type="text" name="expiration_dt" class="form-control" value="{{ $company->expiration_dt }}" readonly>
                        </div>
                    </div>

                @if(auth()->user()->id == 1)
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <label style="font-size: 16px"><i class="fa fa-database"></i>  قاعدة البيانات</label>
                        </div>
                        <div class="box-body with-border">
                            <div class="form-group">
                                <label>
                                    <span>اسم القاعدة </span>
                                </label>
                                <input type="text" name="company_db_name" id="company_db_name" class="form-control" value="{{ $company->company_db_name }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>اسم المستخدم للقاعدة</label>
                                <input type="text" name="company_db_user_name" class="form-control" value="{{ $company->company_db_user_name }}" readonly>
                            </div>
                        </div>
                        <div class="box-header with-border">
                            <label style="font-size: 16px"><i class="fa fa-user"></i> المشرف </label>
                        </div>
                        <div class="box-body with-border">
                            <div class="form-group">
                                <label>اسم المشرف الأول</label>
                                <input type="text" name="company_db_user_first_name" class="form-control" value="{{ $company->company_db_user_first_name }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>اسم المشرف الأخير</label>
                                <input type="text" name="company_db_user_last_name" class="form-control" value="{{ $company->company_db_user_last_name }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>إيميل المشرف </label>
                                <input type="text" name="company_db_user_email" class="form-control" value="{{ $company->company_db_user_email }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>
                                    <span> كلمة المرور للمشرف</span>
                                </label>
                                <input type="text" name="company_db_user_password" class="form-control" value="{{ $company->company_db_user_password }}" readonly>
                            </div>
                        </div>
                    </div>
                    @endif


                </div>
                <div class="col-md-4">
                    @if(auth()->user()->id == 1)
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <label>رابط الشركة: </label>
                            </div>
                            <div class="box-body with-border factor">
                                <a class="btn btn-primary" href="http://{{ $company->company_db_name }}.amnksa.com" target="_blank" style="font-size: 20px; display: block">http://{{ $company->company_db_name }}.amnksa.com</a>
                            </div>
                        </div>

                        <div class="box box-primary">
                        <div class="box-header with-border">
                            <label>طبيعة المستخدم: </label>
                        </div>
                        <div class="box-body with-border factor">
                            <p>
                            @if($company->factor == 0)
                                <label class="label label-success" style="font-size: 20px; display: block">مالك الشركة</label>
                            @else
                                <label class="label label-danger" style="font-size: 20px; display: block">وسيط</label>
                            @endif
                            </p>
                        </div>
                    </div>
                    @endif
                    <div class="box box-primary box_file">
                        <div class="box-header with-border">
                            <label>اللوغو</label>
                        </div>
                        <div class="box-body with-border">
                            @if($company->logo)
                            <img src="{{ url('storage/'.$company->logo) }}" class="img-responsive thumb_preview">
                            @else
                                <img src="{{ asset('img/0.jpg') }}" class="img-responsive thumb_preview">
                            @endif
                        </div>
                    </div>

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <label>الورق الرسمي</label>
                            <a href="{{ url()->to('dashboard/companies/'.$company->id.'/add_paper') }}" class="btn btn-success"><i class="fa fa-plus"></i> إضافة ورق رسمي</a>
                        </div>
                        <div class="box-body with-border">
                            @if($papers)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <tbody>
                                        @foreach($papers as $paper)
                                            <tr>
                                                <td style="border-bottom: 1px solid #ccc">
                                                    <img src="{{ asset('storage/'.$paper->header_img) }}" class="img-responsive">
                                                    <h4><b>{{ $paper->paper_name }}</b></h4>
                                                    <div class="text-center">
                                                        <a href="{{ route('dashboard.edit_paper', ['id' => $company->id, 'paper_id' => $paper->id])  }}" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                                                        @if($paper->is_default)
                                                            <p style="margin-top: 5px"><label class="label label-info"><i class="fa fa-check"></i> افتراضي</label></p>
                                                        @else
                                                            <form
                                                                action="{{ route('dashboard.delete_paper', $paper->id) }}"
                                                                method="post" style="display: inline-block">
                                                                {{ csrf_field() }}
                                                                {{ method_field('delete') }}
                                                                <button type="submit" class="delete btn btn-danger"><i class="fa fa-times"></i></button>
                                                            </form>

                                                            <button class="btn btn-sm btn-primary" data-id="{{ $paper->id }}" data-token="{{ csrf_token() }}" onclick="setDefault('{{ route('dashboard.set_default_paper', $paper->id) }}')"><i class="fa fa-check-square-o"></i> جعل افتراضي</button>
                                                        @endif
                                                    </div>
                                                </td>

                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="alert alert-warning">@lang('site.no_data_found')</p>
                            @endif
                        </div>
                    </div>

                    <div class="box box-primary box_file">
                        <div class="box-header with-border">
                            <label>الختم</label>
                        </div>
                        <div class="box-body with-border">
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
                            <label>{{ $company->sign_accountant_label }}</label>
                        </div>
                        <div class="box-body with-border">
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
                            <label>{{ $company->sign_operational_director_label }}</label>
                        </div>
                        <div class="box-body with-border">
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
                            <label>{{ $company->sign_financial_director_label }}</label>
                        </div>
                        <div class="box-body with-border">
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
                            <label>{{ $company->sign_price_offer_label }}</label>
                        </div>
                        <div class="box-body with-border">
                            @if($edit_mode && $company->sign_price_offer)
                                <img src="{{ asset('storage/'.$company->sign_price_offer) }}" class="img-responsive thumb_preview">
                            @else
                                <img src="{{ asset('img/0.jpg') }}" class="img-responsive thumb_preview">
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if(auth()->user()->id == 1)
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary box_file">
                        <div class="box-header with-border">
                            <label><i class="fa fa-database"></i>  كود إنشاء قاعدة البيانات</label>
                        </div>
                        <div class="box-body with-border">
                            <div class="form-group">
                                <textarea dir="ltr" style="height: 500px !important;" class="form-control" readonly>{{ $company_db_sql }}</textarea>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            @endif
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
