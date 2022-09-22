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
<h2>Ride share (Mail from Rider)</h2>
  <div class="container">
    <h4><b>Vehicle Approval Request From: <span style="color:green">{{ $author }}</span></b></h4>
    <p>Vehicle Name: <span style="color: green">{{ $car_model }}</span></p>
    <p>Vehicle Number Plate: <span style="color: green">{{ $plate }}</span></p>
    <p>Request Date: <span style="color: green">{{ $date }}</span></p>
    <hr>
    <h5>Please go to the admin dashboard and take action</h5>
  </div>
</div>
</body>
</html>
