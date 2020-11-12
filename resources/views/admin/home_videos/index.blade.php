@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Home Videos</div>
                    <div class="panel-body">
                        <a href="{{ url('/admin/home_videos/create') }}" class="btn btn-success btn-sm" title="Post Comments">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>Youtube ID</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($videos as $key => $item)
                                    <tr>
                                        <td>
                                            <img src="http://img.youtube.com/vi/{{$item}}/default.jpg" />
                                        </td>
                                        <td>
                                            <a href="{{ url('/admin/home_videos', $key) }}" title="View Services"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>
                                            <a href="{{ url('/admin/home_videos/' . $key . '/edit') }}" title="Edit Services"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                                            <form method="POST" action="{{url('admin/home_videos', $key)}}" style="display:inline;">
                                                <button type="submit" class="btn btn-danger btn-xs" title="Delete Services" onclick="return confirm('Confirm delete?')" ><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                                                <input type="hidden" name="_method" value="DELETE">
                                                {{csrf_field()}}
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
