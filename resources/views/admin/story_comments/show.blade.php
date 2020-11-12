@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Story Coments</div>
                    <div class="panel-body">

                        <a href="{{ url('/admin/story_comments') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/admin/story_comments/' . $story_comments->id . '/edit') }}" title="Edit Feedback"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                        <form method="POST" action="{{url('admin/story_comments', $story_comments->id)}}" style="display: inline;">
                            <button type="submit" class="btn btn-danger btn-xs" title="Delete Story Comments" onclick="return confirm('Confirm delete?')"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                            <input type="hidden" name="_method" value="DELETE" />
                            {{csrf_field()}}
                        </form>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>ID</th><th>Comment</th><th>User ID</th><th>Post ID</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $story_comments->id }}</td> <td> {{ $story_comments->description }} </td><td> {{ $story_comments->user_id }} </td><td> {{ $story_comments->story_id }} </td>
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
