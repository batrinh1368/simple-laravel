@extends('layouts.admin.resources.index')

@section('search-area', '')

@section('resource_index')
    <div class="container">
        <form class="form" role="form" method="POST"
              action="{{ route('admin::config.store') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-7">

                    <div class="form-group margin-b-5 margin-t-5{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name">{{__a('config.name')}} *</label>
                        <input type="text" class="form-control" name="name" placeholder="Simple Laravel"
                               value="{{ old('name', $config->name) }}">
                        @if ($errors->has('name'))
                            <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                        @endif
                    </div>
                    <div
                        class="form-group margin-b-5 margin-t-5{{ $errors->has('introduce_html') ? ' has-error' : '' }}">
                        <label for="acronym_name">{{__a('config.introduce')}}</label>
                        <textarea class="form-control" id="introduce_html" name="introduce_html"
                                  rows="5"
                                  placeholder="Giới thiệu nhanh">{{ old('introduce_html', $config->introduce_html) }}</textarea>
                        @if ($errors->has('introduce_html'))
                            <span class="help-block">
                    <strong>{{ $errors->first('introduce_html') }}</strong>
                </span>
                        @endif
                    </div>

                </div>
                <div class="col-md-5">
                    <div id="img-preview" class="frame-preview bg-fit"
                         style="background-image: url('{{$config->logo_url_src}}')">
                    </div>
                    <div id="img-light" class="frame-preview bg-fit"
                         style="background-image: url('{{$config->logo_light_url_src}}')">
                    </div>
                </div>
            </div>
            @push('footer-scripts')
                <script>
                    $(function () {
                        $('#image_file').change(function (e) {
                            var fileName = e.target.files[0].name;
                            $('#logo_url').val(fileName);
                        });
                        myEditor($("#introduce_html"));

                    })
                </script>
            @endpush
        </form>
    </div>
@endsection
