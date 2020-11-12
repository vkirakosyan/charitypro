@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Jobs</div>
                    <div class="panel-body">

                        <a href="{{ url('/admin/events') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/admin/events/' . $events->id . '/edit') }}" title="Edit Feedback"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                        <form method="POST" action="{{url('admin/events', $events->id)}}" style="display: inline;" >
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
                                         <th>Name</th>
                                         <th>Description</th>
                                         <th>Location</th>
                                          <th>City Id</th>
                                          <th>User Id</th>
                                          <th>Time</th>
                                        <th>Image</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $events->id }}</td> 
                                       <td>{{ $events->title }}</td>
                                        <td> {{ $events->description }} </td>
                                         <td>{{ $events->details_location }}</td>
                                         <td>{{$events->city_id}}</td>
                                         <td>{{$events->user_id}}</td>
                                         <td>{{$events->time}}</td>
                                        <td> <img src='{{URL("images/Events/$events->img")}}'> </td>
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
