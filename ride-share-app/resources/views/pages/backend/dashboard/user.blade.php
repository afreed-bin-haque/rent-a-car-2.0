 {{--Verify Ride Request--}}
 @php $today = date('Y-m-d');
 $verify_ride_request = DB::table('bookings')
 ->where([
    ['booked_by','=', Auth::user()->email],
    ['status','!=','Paid']
    ])
 ->count();
@endphp
@extends('layouts.app_layouts.backend.backend-master')
@section('content')
@if($verify_ride_request >= 1)
 <!-- Content Row -->
  {{----------Tracking-------------}}
 <div class="row">
    <div class="col-xl-6 col-md-6 mb-4">
         <div id="status"></div>
         <div id="rating" style="display:none">
            <!-- partial:index.partial.html -->
      <div class="card">
        <div class="card-body">
            <form class="row g-3" action="{{ route('start.ratting') }}" method="POST">
                @csrf
                <input type="text" class="form-control" id="rider_email" name="rider_email" hidden>
                <input type="text" class="form-control" id="booking_id" name="booking_id" hidden>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Write a comment</label>
                    <input type="text" class="form-control" id="comment" name="comment">
                    <div id="emailHelp" class="form-text">Please write a comment about your trip(Optional)</div>
                </div>
                <div class=" mb-3">
                    <label for="exampleInputEmail1" class="form-label">Give the rider a rating:</label>
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="rating" name="rating" value="1">
                    <label class="form-check-label" for="rating">1</label>
                    </div>
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="rating" name="rating" value="2">
                    <label class="form-check-label" for="rating">2</label>
                    </div>
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="rating" name="rating" value="3">
                    <label class="form-check-label" for="rating">3</label>
                    </div>
                     <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="rating" name="rating" value="4">
                    <label class="form-check-label" for="rating">4</label>
                    </div>
                     <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="rating" name="rating" value="5">
                    <label class="form-check-label" for="rating">5</label>
                    </div>
                </div>
                <div class="col-12 pb-5">
                     <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill">Submit</button>
                     </div>

                </div>

            </form>
             </div>
             <div class="col-12 pb-5">
                 <form class="row g-3" action="{{ route('no.ratting') }}" method="POST">
                    @csrf
                    <input type="text" class="form-control" id="bokking_no_rating" name="bokking_no_rating" hidden>
                       <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning rounded-pill">No thank you</button>
                        </div>
                 </form>
                </div>

        </div>
<!-- partial -->

        </div>
    </div>
      {{-------End Tracking------------}}
    <div class="col-xl-6 col-md-6 mb-4">
          @foreach($booking_details as $booking)
    <div class="card">
        <div class="card-header">
            Journey date : {{ $booking->jurney_date }}
            </div>
            <div class="card-body">
                <h4 class="text-succss">Destination: <span class="badge bg-success">{{ $booking->from_loc }}</span> &rarr; <span class="badge bg-success">{{ $booking->to_loc }}</span></h4>
                <p>Vehicle plate: <span class="text-success"> {{ $booking->plate }}</span></p>
                <p>Seat booked: <span class="text-success"> {{ $booking->seat_booked }}</span></p>
                <p>Total Cost: <span class="text-success"> {{ $booking->total_fare_cost }} BDT</span></p>
                <a href="{{ route('delete.tripRequest',['booking_id' => $booking->booking_id,'status' => $booking->status]) }}" class="btn btn-danger rounded-pill">Delete request</a>
            </div>
    </div>
    @endforeach

    <div id="refresh_rider" style="display: none">
    <div class="card">
            <div class="card-header">
                Rider Details
            </div>
                <div class="card-body">
                    <p>Rider Name: <span class="text-success" id="rider_name"></span></p>
                    <p>Rider Contact: <span class="text-success" id="rider_contact"></span></p>
                    <span id="rider_call"></span>

                </div>
            </div>

    </div>

    </div>
 </div>
</div>
    <!-- /.container-fluid -->
                @else
                <!-- Content Row -->

                        <!-- From -->
                        <form class="row g-3" action ="{{ route('dashboard') }}" method="GET" >
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                                <div class="form-floating mb-3">
                                                     <select class="form-select text-success" id="from_city" name="from_city" aria-label="from_city">
                                                        @if(!empty(request()->input('from_city')))
                                                        <option  value ="{{ request()->input('from_city') }}">{{ request()->input('from_city') }}</option>
                                                        @else
                                                        <option selected value="">Select a city</option>
                                                        @foreach($fetch_city as $city)
                                                            <option  value ="{{ $city->name }}">{{ $city->name }}</option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                     <label for="from_city">Select your starting point</label>
                                                        @error('from_city')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- To -->
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-secondary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="form-floating mb-3">
                                            <select class="form-select text-success" id="to_city" name="to_city" aria-label="to_city">
                                            @if(!empty(request()->input('to_city')))
                                                <option  value ="{{ request()->input('to_city') }}">{{ request()->input('to_city') }}</option>
                                            @else
                                                <option selected value="">Select starting point first</option>
                                            @endif
                                                </select>
                                                <label for="to_city">Select your destination</label>
                                                <span id="wait_to_city"></span>
                                                @error('to_city')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                             <!-- Collapsable Card Example -->
                            <div class="card  border-left-success shadow h-100 py-2">
                                <!-- Card Header - Accordion -->
                                <span class="d-block card-header py-3"
                                    role="button" aria-expanded="true" aria-controls="collapseCardExample">
                                    <h4 class="m-0 font-weight-bold text-success">Select your desired vehicle type</h4>
                                </span>
                                 @if(!empty(request()->input('vehicle_type')))
                                <!-- Card Content - show -->
                                <div class="collapse show" id="collapseCardExample">
                                    <div class="card-body">

                                        <fieldset class="row mb-3">
                                        <legend class="col-form-label col-sm-2 pt-0">Selected vehicle type:</legend>
                                        <div class="col-sm-10">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="vehicle_type" id="vehicle_type" value="{{ request()->input('vehicle_type') }}" checked>
                                            <label class="form-check-label" for="vehicle_type">
                                            {{ request()->input('vehicle_type') }}
                                            </label>
                                        </div>
                                        </div>
                                    </fieldset>

                                    </div>
                                </div>
                                @else
                                <!-- Card Content - Collapse -->
                                <div class="collapse hide" id="collapseCardExample">
                                    <div class="card-body">

                                        <fieldset class="row mb-3">
                                        <legend class="col-form-label col-sm-2 pt-0">Select any type:</legend>
                                        <div class="col-sm-10">
                                        @foreach($fetch_vehicle_type as $vehicle_type)
                                         <div class="form-check">
                                            <input class="form-check-input" type="radio" name="vehicle_type" id="vehicle_type" value="{{ $vehicle_type->type }}">
                                            <label class="form-check-label" for="vehicle_type">
                                            {{ $vehicle_type->type }}
                                            </label>
                                        </div>
                                        @endforeach
                                        <hr>
                                         <div class="form-check">
                                            <input class="form-check-input" type="radio" name="vehicle_type" id="vehicle_type" value="all">
                                            <label class="form-check-label" for="vehicle_type">
                                            All vehicle
                                                </label>
                                         </div>
                                        </div>
                                    </fieldset>

                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <button type="submit"  class="btn btn-primary">
                                        <span class="text">Search Vehicle</span>
                                    </button>
                        </div>
                          <div class="col-xl-6">
                            <button type="reset"  class="btn btn-info" onclick="reloadpage()">
                                        <span class="text">Reset Search Vehicle</span>
                                    </button>
                        </div>
                        </form>
                        <hr>
                        <div class="row pt-2">
                            @forelse ($fetch_trip as $trip)
                            <div class="col-xl-6 col-md-6 mb-4">
                                <div class="card mb-3">
                                <div class="row g-0">
                                    <div class="col-md-4">
                                    <img src="{{ asset($trip->main_image) }}" class="img-fluid rounded-start" alt="$trip->model">
                                    <div class="d-grid gap-2 pt-3 ps-2 pe-2 pb-2">
                                        <a href="{{ route('view.postDetails',['post_id'=> $trip->post_id,'vehicle_id' => $trip->vehicle_id]) }}" class="btn btn-success rounded-pill">View details</a>
                                    </div>

                                    </div>
                                    <div class="col-md-8 bg-success text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $trip->model }}</h5>
                                        <p class="card-text">{{ $trip->condition }}</p>
                                         <ul class="list-group list-group-flush">
                                            <li class="list-group-item">From: <span class="badge bg-success">{{ $trip->from_loc }}</span> &rarr; To: <span class="badge bg-success">{{ $trip->to_loc }}</span></li>
                                            <li class="list-group-item">Journey Date : <span class="text-primary">{{ $trip->jurney_date }}</span></li>
                                            <li class="list-group-item">Seat: <span class="text-primary">{{ $trip->seat }}</span></li>
                                            <li class="list-group-item">Price per seat: <span class="text-primary">{{ $trip->price_per_seat }} BDT</span></li>
                                            <li class="list-group-item">Total Fare: <span class="text-primary">{{ $trip->total_fare }} BDT</span></li>
                                            <li class="list-group-item">Total Ratings: <span class="text-primary">{{ $trip->rating }} &#11088;</span></li>
                                        </ul>
                                    </div>
                                    </div>
                                </div>
                                </div>

                            </div>
                            @endforeach

                        </div>
                </div>
                <!-- /.container-fluid -->
                @endif
            </div>
            <!-- End of Main Content -->
<script>
       $(document).ready(function(){
        var spinner = '<div class="d-flex align-items-center"><span class="text-success">Please wait</span><div class="spinner-border text-success ms-auto spinner-border-sm" role="status" aria-hidden="true"></div></div>';
        var wait ='<div class="text-white"><i class="fas fa-spinner fa-pulse"></i> Please wait....</div>';
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
                        $('#collapseCardExample').addClass('collapse hide');
                    },
                    success:function(response){
                        $('#wait_to_city').html('');
                        $('#to_city').html('');
                        if(response.fetched_to_cities == 'Select your starting point first'){
                            var dropdownData = $("<option value=''>Select your starting point first</option>");
                            $('#to_city').append(dropdownData);
                            $('#collapseCardExample').addClass('collapse hide');
                        }else{
                             $.each(response.fetched_to_cities,function(key,ddata){
                               var dropdownData = $("<option value='" + ddata.name  + "'>" + ddata.name  +"</option>");
                            $('#to_city').append(dropdownData);
                           });
                        }
                    }
                });
        });
        /** Change to city */
        $(document).on('change','#to_city', function (e) {
            e.preventDefault();
            if( !$('#to_city').val() ) {
                $('#collapseCardExample').addClass('collapse show');
            }else{
                $('#collapseCardExample').removeClass('collapse hide');
            }
        });
       });
        function reloadpage() {
            window.location.href = "{{ route('dashboard') }}"
        }
/**Fetch Tracking Status*/
$(window).on('load',function() {
     function FetchRiderDetails(){
         var author = "{{ Auth::user()->email }}";
           $.ajax({
              type:"GET",
                    url:"{{ route('fetch.riderDetail') }}",
                    data:{'author':author},
                    dataType: "json",
                      success:function(response){
                         $('#rider_name').text('');
                         $('#rider_contact').text('');
                         $('#rider_call').html('');
                        if(response.rider_detail_existance == 'yes'){
                             $("#refresh_rider").css("display","block");
                             $(response.rider_detail).each(function(i,val){
                                $('#rider_name').text(val.name);
                                $('#rider_contact').text(val.contact);
                                $('#rider_call').append('<a href="tel:'+val.contact+'" class="btn btn-primary rounded-pill" id="rider_ca">Call rider</a>');
                            });
                        }else{
                            $("#refresh_rider").css("display","none");
                        }
                      }
           });
    }
    FetchStatus();
    function FetchStatus(){
         var author = "{{ Auth::user()->email }}";
           $.ajax({
              type:"GET",
                    url:"{{ route('fetch.userStatus') }}",
                    data:{'author':author},
                    dataType: "json",
                      success:function(response){
                        if(response.track_status == 'exist'){
                            FetchRiderDetails();
                            if(response.status_trip == 'Complete'){
                                $("#status").html('');
                                $("#rating").css("display","block");
                                $("#rider_email").val(response.rider_email);
                                $('#booking_id').val(response.booking_id);
                                $('#bokking_no_rating').val(response.booking_id);
                            }else{
                                $("#status").html(response.html);
                                $("#rating").css("display","none");
                            }
                        }
                      }
           });
    }
   setInterval(FetchStatus, 5000);
});
</script>
@endsection
