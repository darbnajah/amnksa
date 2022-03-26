@extends('layouts.dashboard.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="row">
                <div class="col-sm-12">
                    <h1 style="margin-bottom: 10px"><i class="fa fa-university"></i> الرئيسية </h1>

                </div>
            </div>

        </section>

        <section class="content">

            @if($company->logo)
                <img style="opacity: 0.8; margin-left: auto; margin-right: auto; width: auto" class="img-responsive home_img" src="{{ asset('storage/'.$company->logo) }}" width="100%">
            @endif

        </section>
    </div>

@endsection
