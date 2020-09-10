@extends('layouts.layout')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="caption">
                    <h2>Page Analyzer</h2>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <span class="form-label">Check web pages for free</span>
                <form action="{{ route('domains.index') }}" class="form" method="post">
                    @csrf
                    <input type="text" class="form-control" id="check" name="domain" value="{{ old('domain') }}" placeholder="https://www.example.com">
                    <button type="submit" class="btn btn-primary">Check</button>
                </form>
            </div>
        </div>
    </div>
@endsection