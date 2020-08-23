@extends('layouts.layout')

@section('content')
    <div class="container">
        @include('flash::message')
        <div class="row ">
            <div class="col-12">
                <div class="caption">
                    <span>Site: {{ $domain['name'] }}</span>
                </div>
                <table class="table table-dark table-striped table-bordered">
                    <tbody>
                    <tr>
                        <td>id</td>
                        <td>{{ $domain['id'] }}</td>
                    </tr>
                    <tr>
                        <td>name</td>
                        <td>{{ $domain['name'] }}</td>
                    </tr>
                    <tr>
                        <td>created_at</td>
                        <td>{{ $domain['created_at'] }}</td>
                    </tr>
                    <tr>
                        <td>updated_at</td>
                        <td>{{ $domain['updated_at'] }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection