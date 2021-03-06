@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Jobs</div>
                    <div class="panel-body">

                        <a href="{{ url('/admin/jobs') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/admin/jobs/' . $jobs->id . '/edit') }}" title="Edit Feedback"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                        <form method="POST" action="{{url('admin/jobs', $jobs->id)}}" style="display: inline;" >
                            <button type="submit" class="btn btn-danger btn-xs" title="Delete Jobs" onclick="return confirm('Confirm delete?')" ><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
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
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Image</th>
                                        <th>Number</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>

                                                 <td>{{ $jobs->id }}</td>
                                        <td>{{ $jobs->title }}</td>
                                        <td>{{ $jobs->description}}</td>
                                        <th><img src='{{URL("images/Jobs/$jobs->img")}}'></th>
                                        <th>{{ $jobs->number }}</th>
                                        <th>{{ $jobs->email }}</th>
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
