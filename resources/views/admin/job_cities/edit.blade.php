@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Edit Job Cities</div>
                    <div class="panel-body">
                        <a href="{{ url('/admin/job_cities') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <br />
                        <br />

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <form method="POST" action="{{url('admin/job_cities', $job_cities->id)}}" class="form-horizontal">
                            @include ('admin.job_cities.form', ['submitButtonText' => 'Update'])
                            <input type="hidden" name="_method" value="PATCH" />
                            {{csrf_field()}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
