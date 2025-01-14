@extends('dashboard.layouts.main')
@push('scripts')
    <style>
        .table {
            font-size: 13px !important;
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-4">
            <x-indikator-ranap.bor spc="anak"></x-indikator-ranap.bor>
        </div>
        <div class="col-md-12 col-sm-12 col-lg-4">
            <x-indikator-ranap.bor spc="kandungan"></x-indikator-ranap.bor>
        </div>
        <div class="col-md-12 col-sm-12 col-lg-4">
            <x-indikator-ranap.bor spc="icu"></x-indikator-ranap.bor>
        </div>
        <div class="col-md-12 col-sm-12 col-lg-4">
            <x-indikator-ranap.bor spc="isolasi"></x-indikator-ranap.bor>
        </div>
        <div class="col-md-12 col-sm-12 col-lg-4">
            <x-indikator-ranap.bor spc="byc"></x-indikator-ranap.bor>
        </div>
        <div class="col-md-12 col-sm-12 col-lg-4">
            <x-indikator-ranap.bor spc="all"></x-indikator-ranap.bor>
        </div>
        <div class="col-md-12 col-sm-12 col-lg-4">
            <x-indikator-ranap.los spc="anak"></x-indikator-ranap.los>
        </div>
        <div class="col-md-12 col-sm-12 col-lg-4">
            <x-indikator-ranap.los spc="kandungan"></x-indikator-ranap.los>
        </div>
        <div class="col-md-12 col-sm-12 col-lg-4">
            <x-indikator-ranap.los spc="icu"></x-indikator-ranap.los>
        </div>
        <div class="col-md-12 col-sm-12 col-lg-4">
            <x-indikator-ranap.los spc="byc"></x-indikator-ranap.los>
        </div>
        <div class="col-md-12 col-sm-12 col-lg-4">
            <x-indikator-ranap.los spc="isolasi"></x-indikator-ranap.los>
        </div>
        <div class="col-md-12 col-sm-12 col-lg-4">
            <x-indikator-ranap.los spc="all"></x-indikator-ranap.los>
        </div>
        <div class="col-md-12 col-sm-12 col-lg-4">
            <x-indikator-ranap.toi spc="anak"></x-indikator-ranap.toi>
        </div>
        <div class="col-md-12 col-sm-12 col-lg-4">
            <x-indikator-ranap.toi spc="kandungan"></x-indikator-ranap.toi>
        </div>
        <div class="col-md-12 col-sm-12 col-lg-4">
            <x-indikator-ranap.toi spc="icu"></x-indikator-ranap.toi>
        </div>
        <div class="col-md-12 col-sm-12 col-lg-4">
            <x-indikator-ranap.toi spc="byc"></x-indikator-ranap.toi>
        </div>
        <div class="col-md-12 col-sm-12 col-lg-4">
            <x-indikator-ranap.toi spc="isolasi"></x-indikator-ranap.toi>
        </div>
        <div class="col-md-12 col-sm-12 col-lg-4">
            <x-indikator-ranap.toi spc="all"></x-indikator-ranap.toi>
        </div>
    </div>
@endsection

@push('scripts')
    <script></script>
@endpush
