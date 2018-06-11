@extends('layouts.app')

@section('content')
    <form action="/spendings/" method="post">
        {{ csrf_field() }}
        <input type="number" name="sum">
        <input type="hidden" name="timestamp" value="{{ $timestamp }}">
        <input type="hidden" name="order" value="{{ $order }}">
        <br>
            @foreach($expenses as $key => $expense)
                <button type="submit" name="expense[{{$key}}/{{ $expense['dayAgo'] }}]" value="{{ $expense['id'] }}">{{ $expense['title'] }}</button>
                <br>
            @endforeach
    </form>
@endsection