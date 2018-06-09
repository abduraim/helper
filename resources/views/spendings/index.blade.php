@extends('layouts.app')

@section('content')

    <a href="/expenses/create/">Add Expense</a><br>
    <a href="/spendings/create/">Add Spending</a><br>

    <br>

    @foreach($spendings as $spending)
        <a href="/spendings/{{ $spending->id }}">{{ $spending->created_at }} - {{ $spending->expense['title'] }}</a><br>
    @endforeach

@endsection