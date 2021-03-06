@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Jobs Categories</div>
                    <div class="panel-body">

                        <a href="{{ url('/admin/jobs_categories') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/admin/jobs_categories/' . $jobs_categories->id . '/edit') }}" title="Edit Feedback"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                        <form method="POST" action="{{url('admin/jobs_categories', $jobs_categories->id)}}">
                            <button type="submit" class="btn btn-danger btn-xs" title="Delete Jobs Categories" onclick="return confirm('Confirm delete?')"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                            <input type="hidden" name="_method" value="DELETE" />
                            {{csrf_field()}}
                        </form>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>ID.</th>
                                        <th>Name</th>
                                        <th>Image</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $jobs_categories->id }}</td>
                                        <td>{{ $jobs_categories->name }}</td>
                                        <td><img src='{{url("images/Jobs/$jobs_categories->img")  }}' </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
