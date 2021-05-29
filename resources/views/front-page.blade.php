@extends('layouts.app')

@section('content')
  @while(have_posts()) @php the_post() @endphp
    @include('partials.content-page')
    <div id="vue">Vue</div>
    <div id="react">React</div>
  @endwhile
@endsection
