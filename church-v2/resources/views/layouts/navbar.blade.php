<div class="navbar bg-base-100 flex justify-between items-center rounded-2xl p-4">
    @if (Auth::user()->role == 'Admin')
        @if (request()->routeIs('admin_dashboard'))
            <a href="{{ route('admin_dashboard') }}" class="btn btn-ghost text-xl">{{ __('Dashboard') }}</a>
        @endif
        @if (request()->routeIs('scan'))
            <a href="{{ route('scan') }}" class="btn btn-ghost text-xl">{{ __('Scan') }}</a>
        @endif
        @if (request()->routeIs('documents'))
            <a href="{{ route('documents') }}" class="btn btn-ghost text-xl">{{ __('Documents') }}</a>
        @endif
        @if (request()->routeIs('priests'))
            <a href="{{ route('priests') }}" class="btn btn-ghost text-xl">{{ __('Priests') }}</a>
        @endif
        @if (request()->routeIs('donations'))
            <a href="{{ route('donations') }}" class="btn btn-ghost text-xl">{{ __('Donations') }}</a>
        @endif
        @if (request()->routeIs('mails'))
            <a href="{{ route('mails') }}" class="btn btn-ghost text-xl">{{ __('Mails') }}</a>
        @endif
        @if (request()->routeIs('approval_request'))
            <a href="{{ route('approval_request') }}" class="btn btn-ghost text-xl">{{ __('Approval Requests') }}</a>
        @endif
        @if (request()->routeIs('payment'))
            <a href="{{ route('payment') }}" class="btn btn-ghost text-xl">{{ __('Payments') }}</a>
        @endif
        @if (request()->routeIs('announcement'))
            <a href="{{ route('announcement') }}" class="btn btn-ghost text-xl">{{ __('Announcements') }}</a>
        @endif
        @if (request()->routeIs('profile.edit'))
            <a href="{{ route('profile.edit') }}" class="btn btn-ghost text-xl">{{ __('Profile') }}</a>
        @endif
    @endif
    @if (Auth::user()->role == 'Parishioner')
        @if (request()->routeIs('parishioner_dashboard'))
            <a href="{{ route('parishioner_dashboard') }}" class="btn btn-ghost text-xl">{{ __('Dashboard') }}</a>
        @endif
        @if (request()->routeIs('request'))
            <a href="{{ route('request') }}" class="btn btn-ghost text-xl">{{ __('Request Documents') }}</a>
        @endif
        @if (request()->routeIs('parishioner_donation'))
            <a href="{{ route('parishioner_donation') }}" class="btn btn-ghost text-xl">{{ __('Donation') }}</a>
        @endif
        @if (request()->routeIs('profile.edit'))
            <a href="{{ route('profile.edit') }}" class="btn btn-ghost text-xl">{{ __('Profile') }}</a>
        @endif
    @endif
    <div class="flex items-center">
        <div class="dropdown">
            <button class="btn btn-ghost text-xl" tabindex="0">
                <i class='bx bxs-bell'></i>
                @if (Auth::user()->role == 'Admin' && $notifications->count() > 0)
                    <span class="absolute top-0 right-0 inline-block w-3 h-3 bg-red-600 rounded-full"></span>
                @elseif (Auth::user()->role == 'Parishioner' &&
                        $notifications->whereIn('type', ['request', 'donation', 'announcement'])->count() > 0)
                    <span class="absolute top-0 right-0 inline-block w-3 h-3 bg-red-600 rounded-full"></span>
                @endif
            </button>
            <ul class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-64 -ml-12">
                <li class="p-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Notifications</h3>
                </li>
                @if (Auth::user()->role == 'Admin')
                    @forelse ($notifications as $notification)
                        <li class="border-b last:border-none">
                            <a href="{{ getNotificationLink($notification) }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="bx bx-info-circle text-blue-500"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium">{{ $notification->message }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @empty
                        <li>
                            <span class="block px-4 py-2 text-sm text-gray-700">No new notifications</span>
                        </li>
                    @endforelse
                @elseif (Auth::user()->role == 'Parishioner')
                    @forelse ($notifications->whereIn('type', ['Payment', 'Request', 'Donation', 'Announcement']) as $notification)
                        <li class="border-b last:border-none">
                            <a href="{{ getNotificationLink($notification) }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="bx bx-info-circle text-blue-500"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium">{{ $notification->message }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @empty
                        <li>
                            <span class="block px-4 py-2 text-sm text-gray-700">No new notifications</span>
                        </li>
                    @endforelse
                @endif
                <li class="p-2 text-center border-t">
                    <a href="#" class="text-sm text-blue-500 hover:underline">View all notifications</a>
                </li>
            </ul>
        </div>
        <div class="dropdown">
            <button class="btn btn-ghost text-xl" tabindex="0">
                <div class="avatar flex items-center me-2">
                    <div class="w-10 rounded-full">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="User Avatar" />
                    </div>
                    <p class="ms-2 text-sm">{{ Auth::user()->name }}</p>
                </div>
                <i class='bx bx-chevron-down ms-1 text-lg'></i>
            </button>
            <ul class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-48">
                <li>
                    <a href="{{ route('profile.edit') }}"
                        class="block w-full text-left text-sm text-gray-700">{{ __('Profile') }}</a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left text-sm text-gray-700">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Modal for Viewing All Notifications -->
<input type="checkbox" id="viewing_all_notifications" class="modal-toggle" />
<div class="modal">
    <div class="modal-box w-11/12 max-w-5xl">
        <h3 class="text-lg font-bold">All Notifications</h3>
        <ul class="py-4 max-h-96 overflow-y-auto">
            @forelse ($notifications as $notification)
                <li class="border-b last:border-none py-2">
                    <a href="{{ getNotificationLink($notification) }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="bx bx-info-circle text-blue-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium">{{ $notification->message }}</p>
                                <p class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </a>
                </li>
            @empty
                <li>
                    <span class="block px-4 py-2 text-sm text-gray-700">No notifications available</span>
                </li>
            @endforelse
        </ul>
        <div class="modal-action">
            <label for="viewing_all_notifications" class="btn">Close</label>
        </div>
    </div>
</div>

<!-- Modal for Individual Notification Details -->
<input type="checkbox" id="notification_details_modal" class="modal-toggle" />
<div class="modal">
    <div class="modal-box w-11/12 max-w-2xl">
        <h3 class="text-lg font-bold">Notification Details</h3>
        <div class="py-4">
            <p id="notificationMessage" class="text-sm text-gray-700"></p>
            <p id="notificationTime" class="text-xs text-gray-500"></p>
        </div>
        <div class="modal-action">
            <label for="notification_details_modal" class="btn">Close</label>
        </div>
    </div>
</div>
