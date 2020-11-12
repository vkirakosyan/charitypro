@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Donations</div>
                    <div class="panel-body">

                        <a href="{{ url('/admin/donations') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/admin/donations/' . $donations->id . '/edit') }}" title="Edit Feedback"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                        <form method="POST" action="{{url('admin/donations', $donations->id)}}" style="display: inline;" >
                            <button type="submit" class="btn btn-danger btn-xs" title="Delete Donations" onclick="return confirm('Confirm delete?')" ><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
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
                                        <th>Phone</th>
                                        <th>Is_blocked</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $donations->id }}</td>
                                         <td> {{ $donations->name }} </td>
                                         <td> {{ $donations->description }} </td>
                                        <td>{{ $donations->phone }}</td>
                                        <td>{{ $donations->is_blocked }}</td>
                                       
                                    </tr>

                                </tbody>

                            </table>
                               <?php
                                            $img=json_decode($donations->images);
                                        ?>
                                        @for($i=0 ; $i<count($img) ; $i++)
                                        <td> <img src='{{url("images/Donations/$img[$i]")}}'></td>
                                        @endfor
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
