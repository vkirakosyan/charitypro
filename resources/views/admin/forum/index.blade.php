@extends('layouts.backend')
@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Post</div>
                    <div class="panel-body">
                        <a href="{{ url('/admin/forum/create') }}" class="btn btn-success btn-sm" title="Post">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a>
                        <form method="GET" action="{{url('admin/forum')}}" class="navbar-form navbar-right" stories="search">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" value="{{isset($keyword) ? $keyword : ''}}" placeholder="Search...">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </form>
                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>User ID</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($post as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->title }}</td>
                                        <th>{{ strlen($item->description) > 50 ? substr($item->description,0, 50) . '...' : $item->description }}</th>
                                        <td>{{ $item->user_id }}</td>
                                        <td>
                                            <a href="{{ url('/admin/forum/' . $item->id) }}" title="View Posts"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>
                                            <a href="{{ url('/admin/forum/' . $item->id . '/edit') }}" title="Edit Posts"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                                            <form method="POST" action="{{url('admin/forum', $item->id)}}" style="display: inline;">
                                                <button type="submit" class="btn btn-danger btn-xs" title="Delete Post" onclick="return confirm('Confirm delete?')"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                                                <input type="hidden" name="_method" value="DELETE" />
                                                {{csrf_field()}}
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination"> {!! $post->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
