@extends('layouts.app')

@section('content')

    <a href="/expenses/"> < BACK </a>

    <br>

    {{ $expense->title }}
    <br>
    <a href="/expenses/{{ $expense->id }}/edit/">Edit</a>
    <form action="/expenses/{{ $expense->id }}" method="post">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
        <input type="submit" value="Delete">
    </form>

@endsection