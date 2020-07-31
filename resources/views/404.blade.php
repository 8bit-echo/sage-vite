@extends('layouts.app')

@section('content')
  @include('partials.page-header')

  @if (!have_posts())
    <div class="alert alert-warning">
      404
       {{ __('Sorry, but the page you were trying to view was not found.', 'sage') }}
    </div> {!! get_search_form(false) !!}
  @endif
@endsection
