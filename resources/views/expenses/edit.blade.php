@extends('layouts.app')

@section('content')

    <form action="/expenses/{{ $expense->id }}" method="post">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <input type="text" name="title" value="{{ $expense->title }}">
        <input type="submit" value="OK">
    </form>

@endsection