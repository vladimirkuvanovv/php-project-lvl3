@extends('layouts.layout')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="caption">
                <h2>Domains</h2>
            </div>
            <table class="table table-dark table-striped table-bordered">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Last check</th>
                    <th scope="col">Status Code</th>
                </tr>
                </thead>
                <tbody>
                @if(isset($domains))
                    @foreach($domains as $domain)
                        <tr>
                            <td>{{ $domain->id }}</td>
                            <td>{{ $domain->name }}</td>
                            <td>{{ $domain->updated_at }}</td>
                            <td>{{ $domain->status_code }}</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection