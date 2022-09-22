<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ride share Rider Mail</title>
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
<h2>Ride share (Mail from Admin)</h2>
  <div class="container">
    <h4><b>Journey Post Approval  For: <span style="color:green">{{ $plate }}</span></b> on <span style="color:green">{{ $jurney_date }}</span></b></h4>
    <p>Hi {{ $author }},<br> Admin has approved your journey post for vahicle <b>{{ $plate }}</b></p>
    <p>Vehicle Plate: <span style="color: green">{{ $plate }}</span></p>
    <p>Journey Date: <span style="color: green">{{ $jurney_date }}</span></p>
    <p>Route: <span style="color: green">{{ $from_city }}</span>&rarr;<span style="color: green">{{ $to_city }}</span></p>
    <p>Total Seat: <span style="color: green">{{ $total_seat }}</span></p>
    <p>Fare (per seat): <span style="color: green">{{ $single_seat_fare }}</span></p>
    <p>Total Fare: <span style="color: green">{{ $total_fare }}</span></p>
    <p>Author: <span style="color: green">{{ $author }}</span></p>
    <p>Request Date: <span style="color: green">{{ $date }}</span></p>

    <hr>
    @if($status === 'Approved')
    <p>Status: <span style="background-color:green;color: white;padding-20px">{{ $status }}</span></p>
    @else
    <p>Status: <span style="background-color:yellow;color: black ;padding-20px">{{ $status }}</span></p>
    <hr>
    @endif
  </div>
</div>
</body>
</html>
