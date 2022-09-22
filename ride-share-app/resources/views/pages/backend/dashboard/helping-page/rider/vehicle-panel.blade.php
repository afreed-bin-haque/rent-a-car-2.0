@extends('layouts.app_layouts.backend.backend-master')
 <style>
  .carousel-inner > .item > img,
  .carousel-inner > .item > a > img {
    width: 70%;
    margin: auto;
  }
  </style>
@section('content')
<!-- Content Row -->

                    <div class="row">
                        <div class="col-xl-8 col-md-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                     <div class="row">
                        <div class="pb-2">
                            <h4 class="text-title text-success">Vehicle List</h4>
                        </div>
                        </div>
                        <div class="row pb-2">
                        <form action="{{ route('vehicle.registrationPanel') }}" method="GET">
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
                <div class="row">
                @forelse($vehicle_list as $vehicle)
                        <!-- Cars -->
                        <div class="col-xl-6 col-md-6 mb-4" >
                            <div class="card border-left-primary shadow h-100" >

                                    <div class="row">
                                         <div class="col-4">
                                            <img src="{{ asset($vehicle->main_image) }}" class="img-fluid rounded-start" alt="{{ $vehicle->model }}">
                                        </div>
                                        <div class="col-8" style="background-color: #04AA6D;color:white">
                                             <div class="card-body px-2">
                                                <h5 class="card-title">{{ $vehicle->model }}
                                                    @if ( $vehicle->status === 'Inactive')
                                                    <span class="btn btn-warning btn-sm">{{ $vehicle->status }}</span>
                                                    @elseif($vehicle->status === 'Active')
                                                    <span class="btn btn-success btn-sm">{{ $vehicle->status }}</span>
                                                    @endif
                                          </h5>
                                             <p class="card-text">{{ $vehicle->condition }}</p>
                                             <ul class="list-group list-group-flush">
                                                <li class="list-group-item">Plate No: {{ $vehicle->plate }}</li>
                                                <li class="list-group-item">Mileage: {{ $vehicle->mileage }}</li>
                                                <li class="list-group-item">Color:
                                                <div style="border:1px dashed #04AA6D;height: 50px;width: 50px;background-color: {{ $vehicle->color }};"></div>
                                                </li>
                                            </ul>
                                                <p class="card-text"><small>Added At: {{ date('d-m-Y', strtotime($vehicle->created_at)) }}</small></p>
                                              </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <!-- Cars end-->
                         @empty
                        <h4 class="text-center text-danger">No match found for <strong>{{ request()->query('car_query') }}</strong></h4>
                        @endforelse
                        {{ $vehicle_list->withQueryString()->links() }}

                    <!-- Content Row -->


                        </div>




                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="pb-2">
                                        <h4 class="text-title text-success">Vehicle Registration </h4>
                                    </div>
                                    <form action="{{ route('store.vehicle') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                    <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" id="mileage" name="mileage" value="{{ old('mileage') }}" placeholder="Enter total Mileage of your vehicle" >
                                      @error('mileage')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" id="plate" name="plate" value="{{ old('plate') }}" placeholder="Plate No" >
                                 @error('plate')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
                                    <div class="form-group">
                                    <textarea type="text" class="form-control form-control-sm" id="condition" name="condition" placeholder="Condition" >{{ old('condition') }}</textarea>
                                @error('condition')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
                                    <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" id="model" name="model" value="{{ old('model') }}" placeholder="Enter Model">
                                @error('model')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
                                 <div class="form-floating mb-3">
                                         <select class="form-select text-success" id="type" name="type" aria-label="type">
                                                <option selected value="">Select Vehicle Type</option>
                                                        @foreach($vehicle_type_list as $type_list)
                                                            <option  value ="{{ $type_list->type }}">{{ $type_list->type }}</option>
                                                        @endforeach
                                                </select>
                                                <label for="type">Vehicle Type</label>
                                                 @error('type')
                                                            <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                <div class="form-group">
                                    <input type="number" class="form-control form-control-sm" id="seats" name="seats" value="{{ old('seats') }}" placeholder="Enter total seats">
                                @error('seats')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
                                    <div class="form-group">
                                    <label for="exampleColorInput" class="form-label">Choose your vehicle color</label>
                                    <input type="color" class="form-control form-control-color" id="color" name="color" value="#563d7c" title="Choose your color">
                                @error('color')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
                                    <div class="form-group">
                                    <label for="main_image" class="form-label">Upload an image of your vehicle as main image:</label>
                                    <input type="file" class="form-control" id="main_image" name="main_image">
                                     @error('main_image')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                     <div class="row">
                                         <div class="form-group">
                                            <label for="main_image" class="form-label">Upload multiple images of your vehicle's interior as reference:</label>
                                            <input type="file" class="form-control" id="multi_image" name="multi_image[]">
                                            @error('multi_image.0')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                    </div>
                                        <table class="table  table-hover" id="dynamic_field" >
                                            <tbody>

                                            </tbody>
                                        </table>
                                        <div class="d-grid gap-2 d-md-block pb-5">
                                            <button type="button" name="add" id="add" class="btn btn-primary btn-sm ">+ Add another image of car's interior</button>
                                        </div>
                                    </div>
                                    <div class="d-grid gap-2">
                                    <button type="submit" name="vehiclereg" id="vehiclereg" class="btn btn-success">Register your vehicle</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>



                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->
<script>
    //add more
$(document).ready(function(){
    var url = "{{ url('add-remove-input-fields') }}";
    var i=1;
    $('#add').click(function(){
        i++;
        $('#dynamic_field').append('<tr id="row'+i+'"  class="dynamic-added"><td><input type="file" class="form-control" id="multi_image" name="multi_image[]"></td><td><button type="button" name="remove"  id="'+i+'" class="btn_remove btn btn-danger rounded-pill">X</button></td></tr>');
        $('#buttonHelper_1').css("display", "none");
        });
    $(document).on('click', '.btn_remove', function(){
        var button_id = $(this).attr("id");
        $('#row'+button_id+'').remove();
    });
});
</script>
@endsection
