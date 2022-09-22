@extends('layouts.app_layouts.backend.backend-master')
@section('content')
<!-- Content Row -->
 <div class="row">
    <div class="col-xl-12 col-md-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <form action="{{ route('post.approvalPanel') }}" method="GET">
                            <div class="col ">
                                <input type="search" id="author_query" name="author_query" value="{{ request()->query('author_query') }}" class="form-control" style="border-color: #A5CE8B;" placeholder="Search by car model / author / journey date / status" autocomplete="off"/>
                            </div>

                                <div class="col pt-2">
                                <button type="submit" class="btn" style="background-color:#A5CE8B;color:white">
                                    Search
                                </button>
                                </div>
                        </form>
                    </div>
                    <div class="row pt-2">
                        <div class="table-responsive">
                                        <table class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th scope="col">Vehicle Image</th>
                                                    <th scope="col">Vehcle Model</th>
                                                    <th scope="col">Plate No</th>
                                                    <th scope="col">Journey Date</th>
                                                    <th scope="col">Seats</th>
                                                    <th scope="col">Travel Route</th>
                                                    <th scope="col">Fare (per seat)</th>
                                                    <th scope="col">Total Fare</th>
                                                    <th scope="col">Author</th>
                                                    <th scope="col">Email</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Registerd at</th>
                                                    <th scope="col">Changed permission at</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                   @forelse ($fetch_post as $post)
                                                    <tr>
                                                        <td>
                                                             <img src="{{ asset($post->main_image) }}" class="img-fluid rounded-start" alt="{{ $post->model }}" style="width: 100px;height:100px">
                                                        </td>
                                                        <td>{{ $post->model }}</td>
                                                        <td>{{ $post->plate }}</td>
                                                        <td>{{ $post->jurney_date }}</td>
                                                        <td>{{ $post->seat }}</td>
                                                        <td><span style="background-color: #47aff5ea;"> <span class="p-2" style="padding: 5px;color:white">{{ $post->from_loc }} &rarr; {{ $post->to_loc }}</span></span></td>
                                                        <td>{{ $post->price_per_seat }}</td>
                                                        <td>{{ $post->total_fare }}</td>
                                                        <td>{{ $post->name }}</td>
                                                        <td>{{ $post->email }}</td>
                                                        @if($post->status === 'Pending')
                                                        <td>
                                                            <span class="btn-warning p-2">{{ $post->status }}</span>
                                                        </td>
                                                        @else
                                                        <td>
                                                            <span class="btn-success p-2">{{ $post->status }}</span>
                                                        </td>
                                                        @endif
                                                        <td>{{ $post->registered_at }}</td>
                                                         @if($post->approved_at != null)
                                                        <td><p style="border-style: dotted;border-color:#4caf57;"><span class="p-2">{{ $post->approved_at }}</span></p></td>
                                                        @else
                                                        <td> <p class="text-warning">Still not approved</p></td>
                                                        @endif
                                                        <td>
                                                            @if($post->status === 'Pending')
                                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                                <a href="tel:{{ $post->contact }}" class="btn btn-primary btn-sm">Call Author</a>
                                                                <a href="{{ route('change.postStatus',['post_id' => $post->post_id,'current_status' => $post->status]) }}" class="btn btn-success btn-sm">Make Active</a>
                                                                 <a href="{{ route('delete.post',['post_id' => $post->post_id]) }}" class="btn btn-danger btn-sm">Delete Post</a>
                                                            </div>
                                                            @else
                                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                                <a href="tel:{{ $post->contact }}" class="btn btn-primary btn-sm">Call Author</a>
                                                                 <a href="{{ route('change.postStatus',['post_id' => $post->post_id,'current_status' => $post->status]) }}" class="btn btn-warning btn-sm">Make Pending</a>
                                                                <a href="{{ route('delete.post',['post_id' => $post->post_id]) }}" class="btn btn-danger btn-sm">Delete Post</a>
                                                            </div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                     @endforeach
                                                </tbody>
                                            </table>
                                    </div>
                    </div>
                    <div class="row">
                        {{ $fetch_post->withQueryString()->links() }}
                    </div>
                        </div>
                    </div>

                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->
    </div>
</div>
@endsection
