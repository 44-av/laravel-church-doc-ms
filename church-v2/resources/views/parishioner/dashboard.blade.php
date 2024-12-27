<x-app-layout>
    <div class="py-2 h-full mt-4 rounded-2xl">
        <div class="max-w-7xl mx-auto space-y-6">
            <div class="bg-gray-200 overflow-hidden">
                <div class="p-4 text-gray-900">
                    @if (session('success'))
                        <div class="toast" id="success">
                            <div class="alert alert-info bg-green-500 text-white">
                                <span>{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="toast" id="error">
                            <div class="alert alert-danger bg-red-500 text-white">
                                <span>{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Donation Requests Card -->
                        <a href="{{ route('donations') }}"
                            class="card bg-blue-100  hover:bg-blue-200 transition duration-200 w-full">
                            <div class="card-body">
                                <h2 class="card-title text-black">Total Donation as of This Month</h2>
                                <p class="text-2xl font-bold">
                                    {{ $requests->whereBetween('donation_date', [now()->startOfMonth(), now()->endOfMonth()])->sum('amount') ?? 'No donations yet.' }}
                                </p>
                            </div>
                        </a>
                        <!-- Approved Requests Card -->
                        <a href="{{ route('request', ['status' => 'Approved']) }}"
                            class="card bg-blue-100  hover:bg-blue-200 transition duration-200 w-full">
                            <div class="card-body">
                                <h2 class="card-title text-black">Approved Requests</h2>
                                <p class="text-2xl font-bold">
                                    {{ $requests->where('status', 'Approved')->where('requested_by', Auth::user()->id)->count() ?? 'No Approved Requests' }}
                                </p>
                            </div>
                        </a>

                        <!-- Decline Requests Card -->
                        <a href="{{ route('request', ['status' => 'Decline']) }}"
                            class="card bg-gray-100  hover:bg-gray-200 transition duration-200 w-full">
                            <div class="card-body">
                                <h2 class="card-title text-black">Declined Requests</h2>
                                <p class="text-2xl font-bold">
                                    {{ $requests->where('status', 'Decline')->where('requested_by', Auth::user()->id)->count() ?? 'No Declined Requests' }}
                                </p>
                            </div>
                        </a>

                        <!-- Pending Requests Card -->
                        <a href="{{ route('request', ['status' => 'Pending']) }}"
                            class="card bg-gray-300  hover:bg-gray-400 transition duration-200 w-full">
                            <div class="card-body">
                                <h2 class="card-title text-black">Pending Requests</h2>
                                <p class="text-2xl font-bold">
                                    {{ $requests->where('status', 'Pending')->where('requested_by', Auth::user()->id)->count() ?? 'No Pending Requests' }}
                                </p>
                            </div>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
