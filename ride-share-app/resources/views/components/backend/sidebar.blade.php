 {{--Vehicle registration count--}}
 @php $count_total_pending_vehicle_reg_approval = DB::table('vehicle_details')
 ->where('status','=','Inactive')
 ->count();

 $count_total_pending_trip_pos_approval = DB::table('posts')
 ->where('status','=','Pending')
 ->count();
 @endphp
 <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('/') }}">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">{{ config('appConfig.app.name') }}  <sup>2</sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('/') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item active">
                <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true"
                    aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Pages</span>
                </a>
                <div id="collapsePages" class="collapse show" aria-labelledby="headingPages"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        @if( $user_status === 'Admin')
                         <h6 class="collapse-header">Vehicle Approval:</h6>
                        <a class="collapse-item" href="{{ route('vehicle.approvalPanel') }}">Vehicle Approval <span class="badge bg-danger" id="vehicle_approval_count">{{ $count_total_pending_vehicle_reg_approval }}</span></a>
                        <a class="collapse-item" href="{{ route('post.approvalPanel') }}">Trip Post Approval <span class="badge bg-danger" id="tip_approval_count">{{ $count_total_pending_trip_pos_approval }}</span></a>
                        @elseif($user_status === 'Rider')
                         <h6 class="collapse-header">Vehicle:</h6>
                        <a class="collapse-item" href="{{ route('vehicle.registrationPanel') }}">Vehicle Registration</a>
                        @endif
                </div>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <script>
             $(document).ready(function(){
                RefreshVehicleApprovalPendingDiv();
                function RefreshVehicleApprovalPendingDiv(){
                    $("#vehicle_approval_count").load(location.href + " #vehicle_approval_count");
                }
                setInterval(RefreshVehicleApprovalPendingDiv, 10000);
             });
        </script>
        <!-- End of Sidebar -->
