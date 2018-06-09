@extends('layouts.app')

@section('content')
    <form action="/expenses/" method="post">
        {{ csrf_field() }}
        <input type="text" name="title">
        <input type="submit" value="Add">
    </form>
@endsection