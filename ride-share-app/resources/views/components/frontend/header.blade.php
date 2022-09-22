<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>.:Rideshare APP:. || {{ str_replace( "/", " ",request()->path()) }}</title>

    <!-- Custom fonts -->
    <link href="{{ asset('app_assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles -->
    <link href="{{ asset('app_assets/css/sb-admin-2.min.css') }}"  rel="stylesheet">
    <link href="{{ asset('app_assets/css/status.css') }}"  rel="stylesheet">
     <link href="{{ asset('app_assets/css/sweetalert2.min.css') }}"" rel="stylesheet">
      <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!--Ajax connection-->
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   <script rel="stylesheet" href="{{ asset('app_assets/js/jquery.min.js') }}"></script>
   <script rel="stylesheet" href="{{ asset('app_assets/js/prefixfree.min.js') }}"></script>


</head>
<style>
    .placeholder {
 margin: 0 auto;
 max-width: auto;
 min-height: 100px;
 background-color: #eee;
}

@keyframes placeHolderShimmer{
    0%{
        background-position: -468px 0
    }
    100%{
        background-position: 468px 0
    }
}

.animated-background {
    animation-duration: 1.25s;
    animation-fill-mode: forwards;
    animation-iteration-count: infinite;
    animation-name: placeHolderShimmer;
    animation-timing-function: linear;
    background: darkgray;
    background: linear-gradient(to right, #eeeeee 10%, #dddddd 18%, #eeeeee 33%);
    background-size: 800px 104px;
    height: 100px;
    position: relative;
}
</style>
