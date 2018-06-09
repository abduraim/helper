@extends('layouts.app')

@section('content')

    <a href="/expenses/create/">Add</a>

    <br>

    @foreach($expenses as $expense)
        <a href="/expenses/{{ $expense->id }}">{{ $expense->title }}</a>
    @endforeach

@endsection
