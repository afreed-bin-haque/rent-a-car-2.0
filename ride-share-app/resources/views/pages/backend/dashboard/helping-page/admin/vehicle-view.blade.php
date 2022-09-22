@extends('layouts.app_layouts.backend.backend-master')
@section('content')
<!-- Content Row -->
 <div class="row">
    <div class="col-xl-12 col-md-12 mb-4">
        @foreach($fetch_cars_details as $car_details)
        <div class="card mb-3">
        <div class="row g-0">
            <div class="col-md-4 p-5">
            <img src="{{ asset($car_details->main_image) }}" class="img-fluid" alt="{{ $car_details->model }}">
            </div>
            <div class="col-md-8">
            <div class="card-body">
                <h5 class="card-title">{{ $car_details->model }}</h5>
                <p class="card-text">Author: <span class="text-success">{{ $car_details->author }}</span></p>
                <p class="card-text">Address: <span class="text-success">{{ $car_details->address }}</span></p>
                <p class="card-text">Contact: <span class="text-success">{{ $car_details->contact }}</span></p>
                 <p class="card-text">Condition: <span class="text-success">{{ $car_details->condition }}</span></p>
                 <div class="card">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Type: <span class="text-success">{{ $car_details->type }}</span></li>
                        <li class="list-group-item">Plate: <span class="text-success">{{ $car_details->plate }}</span></li>
                        <li class="list-group-item">Mileage: <span class="text-success">{{ $car_details->mileage }}</span></li>
                        <li class="list-group-item">Seats: <span class="text-success">{{ $car_details->seats }}</span></li>
                        <li class="list-group-item">Color: <span class="text-success"><div style="border:1px dashed #04AA6D;height: 50px;width: 50px;background-color: {{ $car_details->color }};"></div></span></li>
                    </ul>
                </div>
                <div class="pt-2">
                     <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        @foreach($multi_images as $image)
                                       <th scope="col">
                                        <img src="{{ asset($image->img_path) }}" class="img-thumbnail" style="max-width: 200px;max-height: 200px" alt="{{ asset($image->img_description) }}">
                                       </th>
                                        @endforeach
                                    </tr>
                                </thead>

                            </table>
                        </div>
                    </div>
                </div>
                </div>
                <p class="card-text"><small class="text-muted">Registered at: <span class="text-success">{{ $car_details->registered_at }}</span></small></p>
            </div>
              <div class="card-footer bg-transparent border-success">
                @if($car_details->status === 'Inactive')
                <a href="{{ route('vehicle.statusChange',['vehicle_id' => $car_details->vehicle_id,'current_status'=> $car_details->status ]) }}" class="btn btn-success">Make Active</a>
                @else
                <a href="{{ route('vehicle.statusChange',['vehicle_id' => $car_details->vehicle_id,'current_status'=> $car_details->status ]) }}" class="btn btn-warning">Make Inctive</a>
                @endif
              </div>
            </div>
        </div>
        </div>
        @endforeach
            {{-- <div class="card">


                                                        <td>{{ $car_details->vehicle_id }}</td>
                                                        <td>{{ $car_details->model }}</td>
                                                        <td>{{ $car_details->plate }}</td>
                                                        <td>{{ $car_details->condition }}</td>
                                                        <td>{{ $car_details->type }}</td>
                                                        <td>{{ $car_details->mileage }}</td>
                                                        <td>{{ $car_details->author }}</td>
                                                        <td>{{ $car_details->contact }}</td>
                                                        <td>{{ $car_details->address }}</td>
                                                        @if($car_details->status === 'Inactive')
                                                        <td><span class="btn btn-warning">{{ $car_details->status }}</span></td>
                                                        @else
                                                        <td><span class="btn btn-success">{{ $car_details->status }}</span></td>
                                                        @endif
                                                        <td>{{ $car_details->registered_at }}</td>
                                                         @if($car_details->approved_at != null)
                                                        <td><p style="border-style: dotted;border-color:#4caf57;"><span class="p-2">{{ $car_details->approved_at }}</span></p></td>
                                                        @else
                                                        <td> <p class="text-warning">Still not approved</p></td>
                                                        @endif
                                                        <td>
                                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                                <a href="tel:{{ $car_details->contact }}" type="button" class="btn btn-primary">Call</a>
                                                                <a href="{{ route('vehicle.viewDetail',['vehicle_id' => $car_details->vehicle_id]) }} " type="button" class="btn btn-info">View</a>
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
            <!-- End of Main Content --> --}}
    </div>
</div>
@endsection
