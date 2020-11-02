{{-- Extends Layout --}}
@extends('layouts.admin.master')

<?php
$_pageTitle = (isset($addVarsForView['_pageTitle']) && !empty($addVarsForView['_pageTitle']) ? $addVarsForView['_pageTitle'] : ucwords($resourceTitle));
$_pageSubtitle = (isset($addVarsForView['_pageSubtitle']) && !empty($addVarsForView['_pageSubtitle']) ? $addVarsForView['_pageSubtitle'] : __('pages.list'));
$_listLink = route($addVarsForView['_searchRoute'] ?? ($resourceRoutesAlias . '.index'));
$_createLink = '';

$tableCounter = 0;
$total = 0;
if (isset($records)) {
    $_createLink = route($resourceRoutesAlias . '.create');
    if ($records->count() > 0) {
        $total = $records->total();
        $tableCounter = ($records->currentPage() - 1) * $records->perPage();
        $tableCounter = $tableCounter > 0 ? $tableCounter : 0;
    }
}
?>

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    {!! Breadcrumbs::render($resourceRoutesAlias) !!}
@endsection

{{-- Page Title --}}
@section('page-title', $_pageTitle)

{{-- Page Subtitle --}}
@section('page-subtitle', $_pageSubtitle)

{{-- Header Extras to be Included --}}
@section('head-extras')
    @parent
@endsection

@section('content')
    <!-- Default box -->
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $_pageSubtitle }}</h3>

            <!-- Search -->
            <div class="box-tools pull-right">
                <form class="form" role="form" method="GET" action="{{ $_listLink }}">
                    @if(View::hasSection('search-area'))
                        @yield('search-area')
                    @else
                        <div class="input-group input-group-sm margin-r-5 pull-left" style="width: 200px;">
                            <input type="text" name="search" class="form-control" value="{{ $search }}"
                                   placeholder="{{__('pages.search')}}...">
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                        <a href="{{ $_createLink }}" class="btn btn-sm btn-info pull-right">
                            <i class="fa fa-plus"></i> <span>{{__('pages.add')}}</span>
                        </a>
                    @endif
                </form>
            </div>
            <!-- END Search -->
        </div>

        <div class="box-body no-padding">
            @if (!isset($records))
                @yield('resource_index')
            @elseif(count($records) > 0)
                <div class="padding-5">
                    <div class="row">
                        <div class="col-sm-6">
                        </div>
                        <div class="col-sm-6 text-right lblTotal">
                            <span class="text-green padding-l-5">{{__('pagination.total', ['total' => $total])}}</span>&nbsp;
                        </div>
                    </div>
                </div>
                @yield('resource_index')
                @include('layouts.admin.paginate', ['records' => $records])
            @else
                <p class="margin-l-5 lead text-green">{{__('pagination.empty')}}</p>
            @endif
        </div>
    </div>
    @include('layouts.admin.resources._list-footer-extras')
@endsection

