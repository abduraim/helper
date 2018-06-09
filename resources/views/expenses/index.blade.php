@extends('layouts.app')

@section('content')

    <a href="/expenses/create/">Add Expense</a><br>
    <a href="/spendings/create/">Add Spending</a><br>

    <br>

    <a href="/spendings/">Last Spending</a><br>

    <br>

    @foreach($expenses as $expense)
        <a href="/expenses/{{ $expense->id }}">{{ $expense->title }}</a>
    @endforeach

@endsection
