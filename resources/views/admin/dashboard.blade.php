@extends('layouts.admin.master')
@section('page-title', 'Trang quản trị')

@section('breadcrumbs')
    {!! Breadcrumbs::render(ADMIN_PREFIX) !!}
@endsection

@section('content')
    <h2>Dashboard!</h2>
@endsection
