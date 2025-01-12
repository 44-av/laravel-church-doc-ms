<div class="drawer lg:drawer-open bg-white rounded-2xl h-full">
    <input id="my-drawer" type="checkbox" class="drawer-toggle" />
    <div class="drawer-side">
        <label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
        <aside class="menu bg-sky-800 text-base-content min-h-full w-80 p-4">
            <a href="{{ route('admin_dashboard') }}" class="flex items-center">
                <img src="{{ asset('assets/img/logo.png') }}" alt="logo" class="w-10 h-10 rounded-full">
                <p class="text-white text-lg font-bold ms-2">St. Michael the Archangel Parish Church</p>
            </a>
            <hr class="my-4">
            <ul class="menu-inner py-1">
                @if (Auth::user()->role == 'Admin')
                    <!-- Dashboard -->
                    <li class="menu-item">
                        <a href="{{ route('admin_dashboard') }}"
                            class="menu-link {{ request()->routeIs('admin_dashboard') ? 'bg-[#6DC5E9] text-white' : 'text-white hover:text-white' }} rounded">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div data-i18n="Dashboard">Dashboard</div>
                        </a>
                    </li>

                    <!-- Documents -->
                    <li class="menu-item">
                        <a href="{{ route('documents') }}"
                            class="menu-link {{ request()->routeIs('documents') ? 'bg-[#6DC5E9] text-white' : 'text-white hover:text-white' }}">
                            <i class="menu-icon tf-icons bx bx-file"></i>
                            <div data-i18n="Documents">Documents</div>
                        </a>
                    </li>

                    <!-- Priests -->
                    <li class="menu-item">
                        <a href="{{ route('priests') }}"
                            class="menu-link {{ request()->routeIs('priests') ? 'bg-[#6DC5E9] text-white' : 'text-white hover:text-white' }}">
                            <i class="menu-icon tf-icons bx bx-user"></i>
                            <div data-i18n="Priests">Priests</div>
                        </a>
                    </li>

                    <!-- Donation -->
                    <li class="menu-item">
                        <a href="{{ route('donations') }}"
                            class="menu-link {{ request()->routeIs('donations') ? 'bg-[#6DC5E9] text-white' : 'text-white hover:text-white' }}">
                            <i class="menu-icon tf-icons bx bx-donate-heart"></i>
                            <div data-i18n="Donation">Donation</div>
                        </a>
                    </li>

                    <!-- Mail -->
                    <li class="menu-item">
                        <a href="{{ route('mails') }}"
                            class="menu-link {{ request()->routeIs('mails') ? 'bg-[#6DC5E9] text-white' : 'text-white hover:text-white' }}">
                            <i class="menu-icon tf-icons bx bx-mail-send"></i>
                            <div data-i18n="Mail">Mail</div>
                        </a>
                    </li>

                    <!-- Approval Request -->
                    <li class="menu-item">
                        <a href="{{ route('approval_request') }}"
                            class="menu-link {{ request()->routeIs('approval_request') ? 'bg-[#6DC5E9] text-white' : 'text-white hover:text-white' }}">
                            <i class="menu-icon tf-icons bx bx-check-circle"></i>
                            <div data-i18n="Approval Request">Approval Request</div>
                        </a>
                    </li>

                    <!-- Payment -->
                    <li class="menu-item">
                        <a href="{{ route('payment') }}"
                            class="menu-link {{ request()->routeIs('payment') ? 'bg-[#6DC5E9] text-white' : 'text-white hover:text-white' }}">
                            <i class="menu-icon tf-icons bx bx-credit-card"></i>
                            <div data-i18n="Payment">Transaction History</div>
                        </a>
                    </li>

                    <!-- Announcement -->
                    <li class="menu-item">
                        <a href="{{ route('announcement') }}"
                            class="menu-link {{ request()->routeIs('announcement') ? 'bg-[#6DC5E9] text-white' : 'text-white hover:text-white' }}">
                            <i class="menu-icon tf-icons bx bx-bell"></i>
                            <div data-i18n="Announcement">Announcement</div>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->role == 'Parishioner')
                    <!-- Dashboard -->
                    <li class="menu-item">
                        <a href="{{ route('parishioner_dashboard') }}"
                            class="menu-link {{ request()->routeIs('parishioner_dashboard') ? 'bg-[#6DC5E9] text-white' : 'text-white hover:text-white' }}">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div data-i18n="Dashboard">Dashboard</div>
                        </a>
                    </li>

                    <!-- Request -->
                    <li class="menu-item">
                        <a href="{{ route('request') }}"
                            class="menu-link {{ request()->routeIs('request') ? 'bg-[#6DC5E9] text-white' : 'text-white hover:text-white' }}">
                            <i class="menu-icon tf-icons bx bx-file"></i>
                            <div data-i18n="Request">Request</div>
                        </a>
                    </li>

                    <!-- Donation -->
                    <li class="menu-item">
                        <a href="{{ route('parishioner_donation') }}"
                            class="menu-link {{ request()->routeIs('parishioner_donation') ? 'bg-[#6DC5E9] text-white' : 'text-white hover:text-white' }}">
                            <i class="menu-icon tf-icons bx bx-donate-heart"></i>
                            <div data-i18n="Donation">Donation</div>
                        </a>
                    </li>
                @endif
            </ul>
        </aside>
    </div>
</div>
