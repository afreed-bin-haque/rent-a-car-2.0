@extends('layouts.app_layouts.backend.backend-master')
<style>
    .qtySelector{
	border: 1px solid #ddd;
	width: 107px;
	height: 35px;
	margin: 10px auto 0;
}
.qtySelector .fa{
	padding: 10px 5px;
	width: 35px;
	height: 100%;
	float: left;
	cursor: pointer;
}
.qtySelector .fa.clicked{
	font-size: 12px;
	padding: 12px 5px;
}
.qtySelector .fa-minus{
	border-right: 1px solid #ddd;
}
.qtySelector .fa-plus{
	border-left: 1px solid #ddd;
}
.qtySelector .qtyValue{
	border: none;
	padding: 5px;
	width: 35px;
	height: 100%;
	float: left;
	text-align: center
}
</style>
@section('content')
                <!-- Content Row -->
                <div class="col-12">
                    @foreach($post_details as $p_details)
                    <div class="card">
                        <div class="card-body bg-success text-white">
                          <div class="row">
                             <div class="col-sm-6 col-lg-6">
                                  <h4 class="card-text">Trip Details</h4>
                                <div class="card">
                                <div class="card-body text-dark">
                                    <p class="text-succss">From: <span class="badge bg-success">{{ $p_details->from_loc }}</span> &rarr; To: <span class="badge bg-success">{{ $p_details->to_loc }}</span></p>
                                    <p>Journey date: <span class="text-success"> {{ $p_details->jurney_date }}</span></p>
                                    <p>Seats: <span class="text-success"> {{ $p_details->seat }}</span></p>
                                    <p>Price per seat: <span class="text-success"> {{ $p_details->price_per_seat }} BDT</span></p>
                                    <p>Total fare: <span class="text-success"> {{ $p_details->total_fare }} BDT</span></p>
                                </div>
                                </div>
                                 @endforeach
                           </div>
                           {{--2---}}
                           <div class="col-sm-6 col-lg-6">
                              <h4 class="card-text">Vehcle Details</h4>
                              @foreach($vehicle_details as $vehicle_details_ini)
                            <div class="card mb-3 text-dark">
                                <div class="row g-0">
                                    <div class="col-md-4">
                                    <img src="{{ asset($vehicle_details_ini->main_image ) }}" class="img-fluid rounded-start" alt="{{ $vehicle_details_ini->model }}">
                                    </div>
                                    <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $vehicle_details_ini->model }}</h5>
                                        <p>Vehicle number plate: <span class="text-success"> {{ $vehicle_details_ini->plate }}</span></p>
                                        <div class="card" style="border-style: dashed; border-color: #198754">
                                            <div class="card-body p-2">
                                                 <h5 class="card-text">Vehcle Condtion:</h5>
                                                <p class="text-success"> {{ $vehicle_details_ini->condition }}</p>
                                            </div>
                                        </div>
                                        <p>Mileage: <span class="text-success"> {{ $vehicle_details_ini->mileage }}</span></p>
                                        <p>Type: <span class="text-success"> {{ $vehicle_details_ini->type }}</span></p>
                                        <p>Total seats: <span class="text-success"> {{ $vehicle_details_ini->seats }}</span></p>
                                        <p>Color: <div style="border:1px dashed #04AA6D;height: 50px;width: 50px;background-color: {{ $vehicle_details_ini->color }};"></div></p>
                                        <p>Driver rating: <span class="text-success"> {{ $vehicle_details_ini->rating }} &#11088;</span></p>
                                        @endforeach
                                    </div>
                                    </div>
                                </div>
                                </div>
                          </div>
                          {{--------------------------------------}}
                           </div>
                           <div class="row">
                            <div class="col-12 text-dark">
                                <div class="card p-2">
                                    <div class="card-body">
                                        <h5 class="card-title">Book your trip</h5>
                                        <form class="row g-3" action="{{ route('set.booking') }}" method="POST">
                                            @csrf
                                            <div class="col-12" style="display: none">
                                                @foreach($post_details as $p_details)
                                                    <input type="text" class="form-control" id="post_id" name="post_id" value="{{ $p_details->post_id }}">
                                                @endforeach
                                            </div>
                                        <div class="col-md-6">
                                           <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="book_all" name="book_all">
                                            <label class="form-check-label" for="book_all">
                                                Book all seats
                                            </label>
                                           </div>
                                        </div>
                                        <div class="col-md-6">
                                             <div class="row mb-3">
                                                <label for="inputEmail3" class="col-sm-2 col-form-label">Book individual</label>
                                                <div class="col-sm-10">
                                                    <div class="qtySelector text-center">
                                                    <i class="fa fa-minus decreaseQty"></i>
                                                    <input type="text" class="qtyValue" name="qtyValue" id="qtyValue" value="1" />
                                                    <i class="fa fa-plus increaseQty"></i>
                                                    </div>
                                                    </div>
                                                     @error('qtyValue')
                                                        <span class="bg-danger text-white text-center">{{ $message }}</span>
                                                    @enderror
                                            </div>
                                        </div>
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-success rounded-pill">Book</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 pt-5">
                            <div class="card">
                                <div class="card-body p-2">
                                    <h4 style="color:#198754">Reviews:</h4>
                                    @forelse($view_review as $reviews)
                                    <div class="card pt-2">
                                        <div class="card-body text-dark">
                                            @if($reviews->comment === null)
                                            @else
                                            <p>{{ $reviews->comment }}</p>
                                            @endif
                                            <p>Rate: {{ $reviews->rate }} ⭐</p>
                                            <p>Author: {{ $reviews->author }}</p>
                                        </div>
                                    </div>
                                    @empty
                                    <p class="text-success">NO Review</p>
                                    @endforelse
                                    {{ $view_review->links() }}
                                </div>
                            </div>
                           </div>

                           </div>
                           {{--------------------------------------}}
                        </div>

                    </div>
                      </div>

                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->
<script>
    @foreach($post_details as $p_details)
    var minVal = 1, maxVal = {{ $p_details->seat }}; // Set Max and Min values
// Increase product quantity on cart page
$(".increaseQty").on('click', function(){
		var $parentElm = $(this).parents(".qtySelector");
		$(this).addClass("clicked");
		setTimeout(function(){
			$(".clicked").removeClass("clicked");
		},100);
		var value = $parentElm.find(".qtyValue").val();
		if (value < maxVal) {
			value++;
		}
		$parentElm.find(".qtyValue").val(value);
});
// Decrease product quantity on cart page
$(".decreaseQty").on('click', function(){
		var $parentElm = $(this).parents(".qtySelector");
		$(this).addClass("clicked");
		setTimeout(function(){
			$(".clicked").removeClass("clicked");
		},100);
		var value = $parentElm.find(".qtyValue").val();
		if (value > 1) {
			value--;
		}
		$parentElm.find(".qtyValue").val(value);
	});
    @endforeach
</script>
@endsection
