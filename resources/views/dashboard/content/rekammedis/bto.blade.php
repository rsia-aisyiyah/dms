@extends('dashboard.layouts.main')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-4">
            <x-bed-turn-over.bor spc="anak"></x-bed-turn-over.bor>
        </div>
        <div class="col-md-12 col-sm-12 col-lg-4">
            <x-bed-turn-over.bor spc="kandungan"></x-bed-turn-over.bor>
        </div>
        <div class="col-md-12 col-sm-12 col-lg-4">
            <x-bed-turn-over.bor spc="all"></x-bed-turn-over.bor>
        </div>
    </div>
@endsection

@push('scripts')
    <script></script>
@endpush
