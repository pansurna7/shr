<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ asset('storage/' .$setting->logo) }}" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">{{$setting->name}}</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        {{-- <li>
            <a href="{{route('dashboard')}}">
                <div class="parent-icon">
                    <i class="bx bx-tachometer"></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li> --}}
        <x-backend-sidebar  />
    </ul>
    <!--end navigation-->
</div>
