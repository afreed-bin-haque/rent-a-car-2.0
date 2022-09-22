<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ride share (User)</title>
</head>
<style>
body {
  font-family: 'Trebuchet MS', sans-serif;
}
.card {
  box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
  transition: 0.3s;
  width: 100%;
  padding: 2px 16px;
}

.container {
  padding: 2px 16px;
}
</style>
<body>
    <div class="card">
<h2>Ride share (Mail from User)</h2>
  <div class="container">
    <h4><b>{{ $author }} request for a trip on : <span style="color:green">{{ $jurney_date }}</span></b></h4>
    <p>Route: <span style="color: green">{{ $from_loc }}</span> &rarr; <span style="color: green">{{ $to_loc }}</span></p>
    <p>Journey Date: <span style="color: green">{{ $jurney_date }}</span></p>
    <p>Plate: <span style="color: green">{{ $plate }}</span></p>
    <p>Model: <span style="color: green">{{ $car_model }}</span></p>
    <p>Seat Booked: <span style="color: green">{{ $seat_booked }}</span></p>
    <p>Request: <span style="color: green">{{ $req_date }}</span></p>
    @if($status === 'Active')
    <p>Status: <span style="background-color:green;color: white;padding-20px">{{ $status }}</span></p>
    @else
    <p>Status: <span style="background-color:red;color: white;padding-20px">{{ $status }}</span></p>
    <hr>
    @endif
    <h5>Please go to the dashboard and accept trip request</h5>
  </div>
</div>
</body>
</html>
