@extends('layouts.app_layouts.backend.backend-master')
@section('content')
<!-- Content Row -->

                    <div class="row">
                        <div class="col-xl-8 col-md-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                <div class="pb-2">
                                        <h4 class="text-title text-success"> Travel Request </h4>
                                 </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th scope="col">Travel Route</th>
                                                    <th scope="col">Vehicle Plate</th>
                                                    <th scope="col">Journey Date</th>
                                                    <th scope="col">Seat Dooked</th>
                                                    <th scope="col">Fare</th>
                                                    <th scope="col">Booked By</th>
                                                    <th scope="col">Current status</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($travel_details as $t_details)
                                                    <tr>
                                                         <td><span style="background-color: #47aff5ea;"> <span class="p-2" style="padding: 5px;color:white">{{ $t_details->from_loc }} &rarr; {{ $t_details->to_loc }}</span></span></td>
                                                         <td>{{ $t_details->plate }}</td>
                                                         <td>{{ $t_details->jurney_date }}</td>
                                                         <td>{{ $t_details->seat_booked }}</td>
                                                         <td>{{ $t_details->total_fare_cost }}</td>
                                                         <td>{{ $t_details->name }}</td>
                                                        <td>
                                                            @if($t_details->status === 'Pending')
                                                            <span class="badge badge-warning rounded-pill">{{ $t_details->status }}</span>
                                                            @elseif($t_details->status === 'Complete')
                                                            <span class="badge badge-success rounded-pill">{{ $t_details->status }}</span>
                                                             @else
                                                            <span class="badge badge-info rounded-pill">{{ $t_details->status }}</span>
                                                            @endif
                                                        </td>
                                                        @if($t_details->status === 'Pending')
                                                         <td>
                                                            <div class="btn-group" role="group" >
                                                                <a href="tel:{{ $t_details->contact }}" type="button" class="btn btn-primary">Call Client</a>
                                                                <a href="{{ route('change.status',['booking_id' => $t_details->booking_id,'status' => $t_details->status]) }}" type="button" class="btn btn-info">Change Status</a>
                                                                <a href="{{ route('delete.tripRequestRider',['booking_id' => $t_details->booking_id,'status' => $t_details->status]) }}" type="button" class="btn btn-danger">Delete</a>
                                                            </div>
                                                         </td>
                                                         @elseif(($t_details->status === 'Complete') || ($t_details->status === 'Paid'))
                                                         @else
                                                         <td>
                                                            <div class="btn-group" role="group" >
                                                                <a href="tel:{{ $t_details->contact }}" type="button" class="btn btn-primary">Call Client</a>
                                                                <a href="{{ route('change.status',['booking_id' => $t_details->booking_id,'status' => $t_details->status]) }}" type="button" class="btn btn-info">Change Status</a>
                                                            </div>
                                                         </td>
                                                         @endif
                                                    </tr>
                                                    @empty
                                                    @endforelse
                                                </tbody>
                                            </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="pb-2">
                                        <h4 class="text-title text-success">Trip Registration </h4>

                                        <form class="row g-3" action="{{ route('post.trip') }}" method="POST">
                                            @csrf
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <select class="form-select text-success" id="from_city" name="from_city" aria-label="from_city">
                                                <option selected value="">Select a city</option>
                                                @foreach($fetch_city as $city)
                                                <option  value ="{{ $city->name }}">{{ $city->name }}</option>
                                                @endforeach
                                                </select>
                                                <label for="from_city">Select your starting point</label>
                                                @error('from_city')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                 <div class="form-floating mb-3">
                                                    <select class="form-select text-success" id="to_city" name="to_city" aria-label="to_city">
                                                        <option selected value="">Select starting point first</option>
                                                        </select>
                                                        <label for="to_city">Select your destination</label>
                                                        <span id="wait_to_city"></span>
                                                        @error('to_city')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-floating mb-3">
                                                    <select class="form-select text-success" id="plate" name="plate" aria-label="Vehicle Number">
                                                        <option selected value="">Select a vehicle number</option>
                                                        @forelse($vehicle_details as $vehicle)
                                                        <option  value ="{{ $vehicle->vehicle_id }}">{{ $vehicle->plate }}</option>
                                                        @empty
                                                        <option selected value="">No vehicle found</option>
                                                        @endforelse
                                                        </select>
                                                        <label for="plate">Vehicle Number</label>
                                                        @error('plate')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                        </div>
                                            </div>

                                            <div class="col">
                                        <input type="number" class="form-control" id="year" name="year" placeholder="Year">
                                        <small id="yearhelper" class="form-text text-muted">Type year like this: {{ date('Y') }}</small>
                                         @error('year')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col">
                                        <input type="number" class="form-control" id="month" name="month" placeholder="Month">
                                        <small id="yearhelper" class="form-text text-muted">Type month like this: {{ date('m') }}</small>
                                          @error('month')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col">
                                        <input type="number" class="form-control" id="date" name="date" placeholder="Date">
                                        <small id="yearhelper" class="form-text text-muted">Type date like this: {{ date('d') }}</small>
                                         @error('date')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                            <div class="col-12">
                                                <input type="number" class="form-control" id="single_seat_fare" name="single_seat_fare" placeholder="Per seat fare">
                                             @error('single_seat_fare')
                                                <span class="text-danger">{{ $message }}</span>
                                             @enderror
                                            </div>
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-success">Register</button>
                                            </div>
                                            </form>

                                        <div class="card-image-holder pt-2"></div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


 <div class="col-xl-8 col-md-6 mb-4">
                            <div class="card">
                                <div class="card-body">
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
                                                    <th scope="col">Status</th>
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
                                                        @if($post->status === 'Pending')
                                                        <td>
                                                            <span class="btn-warning p-2">{{ $post->status }}</span>
                                                        </td>
                                                        @else
                                                        <td>
                                                            <span class="btn-success p-2">{{ $post->status }}</span>
                                                        </td>
                                                        @endif
                                                    </tr>
                                                     @endforeach
                                                </tbody>
                                            </table>
                                    </div>
                                </div>
                            </div>
                         </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->
<script>
$(document).ready(function(){
        var spinner = '<div class="d-flex align-items-center"><span class="text-success">Please wait</span><div class="spinner-border text-success ms-auto spinner-border-sm" role="status" aria-hidden="true"></div></div>';
        var wait ='<div class="text-white"><i class="fas fa-spinner fa-pulse"></i> Please wait....</div>';
        var placeholder_loader ='<div class="placeholder"><div class="animated-background"></div></div>'
        /** Fetch to city */
        $(document).on('change','#from_city', function (e) {
            e.preventDefault();
            var selected_from_city = $(this).val();
            $.ajax({
                    type:"GET",
                    url:"{{ route('fetch.toCity') }}",
                    data:{'selected_from_city':selected_from_city},
                    dataType: "json",
                    beforeSend:function(){
                        $('#wait_to_city').html(spinner);
                    },
                    success:function(response){
                        $('#wait_to_city').html('');
                        $('#to_city').html('');
                        if(response.fetched_to_cities == 'Select your starting point first'){
                            var dropdownData = $("<option value=''>Select your starting point first</option>");
                            $('#to_city').append(dropdownData);
                        }else{
                             $.each(response.fetched_to_cities,function(key,ddata){
                               var dropdownData = $("<option value='" + ddata.name  + "'>" + ddata.name  +"</option>");
                            $('#to_city').append(dropdownData);
                           });
                        }
                    }
                });
        });
        /**Fetch car image*/
         $(document).on('change','#plate', function (e) {
            e.preventDefault();
            var vehicle_id = $(this).val();
            $.ajax({
                    type:"GET",
                    url:"{{ route('fetch.carMainImage') }}",
                    data:{'vehicle_id':vehicle_id},
                    dataType: "json",
                    beforeSend:function(){
                        $('.card-image-holder').html(placeholder_loader);
                    },
                    success:function(response){
                        $('.card-image-holder').html('');
                        if(response.image == 'Select vehicle plate no first'){
                             $('.card-image-holder').html('<div class="alert alert-danger" role="alert">Select vehicle plate no first</div>');
                        }else{
                              $.each(response.image,function(key,image){
                            $('.card-image-holder').html('<img src ="./' + image.main_image + '" alt="'+ image.model +'">');
                         });
                        }
                    }
                });
        });
});
</script>
@endsection
