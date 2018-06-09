@extends('layouts.app')

@section('content')

    <a href="/spendings/"> < BACK </a>

    <br>

    {{ $spending->sum }} <br>
    {{ $spending->created_at }} <br>
    {{ $expense->title }} <br>


{{--    <br>
    <a href="/expenses/{{ $expense->id }}/edit/">Edit</a>
    <form action="/expenses/{{ $expense->id }}" method="post">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
        <input type="submit" value="Delete">
    </form>--}}

@endsection