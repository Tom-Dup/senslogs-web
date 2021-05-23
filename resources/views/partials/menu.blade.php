<!--**********************************
        Sidebar start
    ***********************************-->
<div class="deznav">
    <div class="deznav-scroll">
        <ul class="metismenu" id="menu">
            <li><a href="{{ route("sessions") }}" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-381-list"></i>
                    <span class="nav-text">Sessions</span>
                </a>
            </li>
            <li>
                <a href="{{ route("dashboard") }}" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-381-networking"></i>
                    <span class="nav-text">Overview</span>
                </a>
            </li>
            <li>
                <a href="{{ route("map") }}" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-381-map-2"></i>
                    <span class="nav-text">Map</span>
                </a>
            </li>
            <li>
                <a href="{{ route("charts") }}" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-381-controls-3"></i>
                    <span class="nav-text">Charts</span>
                </a>
            </li>
            <li>
                <a href="{{ route("files") }}" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-381-folder"></i>
                    <span class="nav-text">Files</span>
                </a>
            </li>
            <li><a href="{{ route("settings") }}" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-381-settings-2"></i>
                    <span class="nav-text">Settings</span>
                </a>
            </li>
            <li><a href="{{ route("logout") }}" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-381-exit"></i>
                    <span class="nav-text">Logout</span>
                </a>
            </li>
        </ul>
        <div class="copyright">
            <p><strong>{{ env('APP_NAME') }}</strong> © 2021 No Rights Reserved</p>
        </div>
    </div>
</div>
<!--**********************************
    Sidebar end
***********************************-->
