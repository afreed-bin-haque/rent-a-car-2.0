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
    <h4><b>Vehicle Approval email for: <span style="color:green">{{ $plate }}</span></b></h4>
    <p>Vehicle Name: <span style="color: green">{{ $model }}</span></p>
    <p>Author: <span style="color: green">{{ $author }}</span></p>
    <p>Registered Date: <span style="color: green">{{ $registered_at }}</span></p>
    <p>Approval Date: <span style="color: green">{{ $approved_at }}</span></p>
    @if($status === 'Active')
    <p>Status: <span style="background-color:green;color: white;padding-20px">{{ $status }}</span></p>
    @else
    <p>Status: <span style="background-color:red;color: white;padding-20px">{{ $status }}</span></p>
    <hr>
    @endif
    <h5>Please go to the dashboard and make trip post</h5>
  </div>
</div>
</body>
</html>
