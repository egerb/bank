@extends('layouts.app')

@section('content')
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                        <transactions bearer="{{session()->get('accessToken')}}" host="{{env('APP_URL')}}"></transactions>
                </div>
            </div>

@endsection
