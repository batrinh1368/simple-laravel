@if($extends)
    @extends($extends)
@endif

@if($sections)
    @foreach($sections as $section)
        @section($section)
        @endsection
    @endforeach
@endif
