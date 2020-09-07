@extends('layouts.layout')

@section('content')
    <div class="container">
        @include('flash::message')
        <div class="row ">
            <div class="col-12">
                <div class="caption">
                    <span>Site: {{ $domain->name }}</span>
                </div>
                <table class="table table-dark table-striped table-bordered">
                    <tbody>
                    <tr>
                        <td>id</td>
                        <td>{{ $domain->id }}</td>
                    </tr>
                    <tr>
                        <td>name</td>
                        <td>{{ $domain->name }}</td>
                    </tr>
                    <tr>
                        <td>created_at</td>
                        <td>{{ $domain->created_at }}</td>
                    </tr>
                    <tr>
                        <td>updated_at</td>
                        <td>{{ $domain->updated_at }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('additional-content')

    <div class="container">
        <div class="row ">
            <div class="col-12">
                <div class="title_additional-content">
                    <h3>Checks</h3>
                </div>

                <form action="{{ route('check', [$domain->id]) }}" method="post">
                    @csrf
                    <div class="btn-block">
                        <button type="submit" class="btn btn-primary">Run check</button>
                    </div>
                </form>
                <table class="table table-dark table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Status Code</th>
                            <th>h1</th>
                            <th>Keywords</th>
                            <th>Description</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($domain_checks as $domain_check)
                        <tr>
                            <td>{{ $domain_check->id }}</td>
                            <td>{{ $domain_check->status_code }}</td>
                            <td>{{ $domain_check->h1 }}</td>
                            <td>{{ $domain_check->keywords }}</td>
                            <td>{{ $domain_check->description }}</td>
                            <td>{{ $domain_check->created_at }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection