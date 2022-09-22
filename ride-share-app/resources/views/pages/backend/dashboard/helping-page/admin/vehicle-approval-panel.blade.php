@extends('layouts.app_layouts.backend.backend-master')
@section('content')
<!-- Content Row -->
 <div class="row">
    <div class="col-xl-12 col-md-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <form action="{{ route('vehicle.approvalPanel') }}" method="GET">
                            <div class="col ">
                                <input type="search" id="car_query" name="car_query" value="{{ request()->query('car_query') }}" class="form-control" style="border-color: #A5CE8B;" placeholder="Search car" autocomplete="off"/>
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
                                                    <th scope="col">Registretion ID</th>
                                                    <th scope="col">Vehcle Model</th>
                                                    <th scope="col">Plate No</th>
                                                    <th scope="col">Condition</th>
                                                    <th scope="col">Type</th>
                                                    <th scope="col">Mileage</th>
                                                    <th scope="col">Owner</th>
                                                    <th scope="col">Contact</th>
                                                    <th scope="col">Owner Address</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Registerd at</th>
                                                    <th scope="col">Changed permission at</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($fetch_cars_approval_panel as $approval_car)
                                                    <tr>
                                                        <td>
                                                            <img src="{{ asset($approval_car->main_image) }}" class="img-fluid rounded-start" alt="{{ $approval_car->model }}" style="width: 100px;height:100px">
                                                        </td>
                                                        <td>{{ $approval_car->vehicle_id }}</td>
                                                        <td>{{ $approval_car->model }}</td>
                                                        <td>{{ $approval_car->plate }}</td>
                                                        <td>{{ $approval_car->condition }}</td>
                                                        <td>{{ $approval_car->type }}</td>
                                                        <td>{{ $approval_car->mileage }}</td>
                                                        <td>{{ $approval_car->author }}</td>
                                                        <td>{{ $approval_car->contact }}</td>
                                                        <td>{{ $approval_car->address }}</td>
                                                        @if($approval_car->status === 'Inactive')
                                                        <td><span class="btn btn-warning">{{ $approval_car->status }}</span></td>
                                                        @else
                                                        <td><span class="btn btn-success">{{ $approval_car->status }}</span></td>
                                                        @endif
                                                        <td>{{ $approval_car->registered_at }}</td>
                                                         @if($approval_car->approved_at != null)
                                                        <td><p style="border-style: dotted;border-color:#4caf57;"><span class="p-2">{{ $approval_car->approved_at }}</span></p></td>
                                                        @else
                                                        <td> <p class="text-warning">Still not approved</p></td>
                                                        @endif
                                                        <td>
                                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                                <a href="tel:{{ $approval_car->contact }}" type="button" class="btn btn-primary">Call</a>
                                                                <a href="{{ route('vehicle.viewDetail',['vehicle_id' => $approval_car->vehicle_id]) }} " type="button" class="btn btn-info">View</a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td>
                                                            <p class="text-danger">No vehicle found {{ request()->query('car_query') }}</p>
                                                        </td>
                                                    </td>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                    </div>
                    </div>
                    <div class="row">
                        {{ $fetch_cars_approval_panel->withQueryString()->links() }}
                    </div>
                        </div>

                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->
    </div>
</div>
@endsection
