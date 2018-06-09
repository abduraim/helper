@extends('layouts.app')

@section('content')
    <form action="/spendings/" method="post">
        {{ csrf_field() }}
        <input type="number" name="sum">
        <br>
            @foreach($expenses as $expense)
                <button type="submit" name="expense_id" value="{{ $expense->id }}">{{ $expense->title }}</button>
                <br>
            @endforeach
    </form>
@endsection