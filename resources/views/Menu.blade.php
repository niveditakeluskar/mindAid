@extends('app')

@section('contents')

{!! Menu::render() !!}

  @endsection  

   //You Must Have Jquery Loaded Before menu scripts

      @push('scripts')

      {!! Menu::scripts() !!}

  @endpush