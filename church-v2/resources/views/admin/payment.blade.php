<x-app-layout>
    <div class="py-12 bg-white h-full mt-4 rounded-2xl">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden">
                <div class="p-1 text-gray-900">
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
                    <div class="mb-4">
                        <form action="{{ route('payment') }}" method="GET" class="flex items-center" id="searchForm">
                            <input type="text" name="search" placeholder="Search Payments..."
                                class="input input-bordered w-full max-w-xs" />
                            <button type="submit"
                                class="ml-2 bg-green-500 text-white rounded-md px-4 py-3 hover:bg-green-600">
                                <i class='bx bx-search'></i>
                            </button>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 table-auto">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Full Name
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amount
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Payment Date & Time
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Payment Status
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Transaction ID
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($payments as $payment)
                                    <tr class="cursor-pointer" onclick="viewModal{{ $payment->id }}.showModal()">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $payment->request->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $payment->amount }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $payment->payment_date }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $payment->payment_status }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $payment->transaction_id }}</td>
                                    </tr>
                                    <!-- View Modal -->
                                    <dialog id="viewModal{{ $payment->id }}" class="modal">
                                        <div class="modal-box rounded-lg shadow-lg w-11/12 max-w-5xl">
                                            <div class="flex items-center gap-2">
                                                <button class="btn text-black hover:bg-green-700 hover:text-white me-2"
                                                    type="button" onclick="viewModal{{ $payment->id }}.close()">
                                                    <i class='bx bx-left-arrow-alt'></i>
                                                </button>
                                                <h3 class="text-lg font-bold">View Payment</h3>
                                            </div>
                                            <hr class="my-4">
                                            <h2 class="text-lg font-bold mb-4">Payment Information</h2>
                                            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                <div class="sm:col-span-3">
                                                    <label class="input input-bordered flex items-center gap-2">
                                                        Full Name
                                                        <input type="text" name="amount"
                                                            class="grow border-none focus:ring-0 focus:border-none"
                                                            value="{{ $payment->request->user->name }}" readonly />
                                                    </label>
                                                </div>
                                                <div class="sm:col-span-3">
                                                    <label class="input input-bordered flex items-center gap-2">
                                                        Amount
                                                        <input type="text" name="amount"
                                                            class="grow border-none focus:ring-0 focus:border-none"
                                                            value="{{ $payment->amount }}" readonly />
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                <div class="sm:col-span-3">
                                                    <label class="input input-bordered flex items-center gap-2">
                                                        Payment Date & Time
                                                        <input type="text" name="payment_date"
                                                            class="grow border-none focus:ring-0 focus:border-none"
                                                            value="{{ $payment->payment_date }}" readonly />
                                                    </label>
                                                </div>
                                                <div class="sm:col-span-3">
                                                    <label class="input input-bordered flex items-center gap-2">
                                                        Payment Method
                                                        <input type="text" name="payment_method"
                                                            class="grow border-none focus:ring-0 focus:border-none"
                                                            value="{{ $payment->payment_method }}" readonly />
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                <div class="sm:col-span-3">
                                                    <label class="input input-bordered flex items-center gap-2">
                                                        Payment Status
                                                        <input type="text" name="payment_status"
                                                            class="grow border-none focus:ring-0 focus:border-none"
                                                            value="{{ $payment->payment_status }}" readonly />
                                                    </label>
                                                </div>
                                                <div class="sm:col-span-3">
                                                    <label class="input input-bordered flex items-center gap-2">
                                                        Transaction ID
                                                        <input type="text" name="transaction_id"
                                                            class="grow border-none focus:ring-0 focus:border-none"
                                                            value="{{ $payment->transaction_id }}" readonly />
                                                    </label>
                                                </div>
                                            </div>
                                            <hr class="my-4">
                                            <div class="flex justify-end">
                                                <button class="btn text-black hover:bg-red-700 hover:text-white"
                                                    type="button"
                                                    onclick="event.stopPropagation(); viewModal{{ $payment->id }}.close()">
                                                    Close
                                                </button>
                                            </div>
                                        </div>
                                    </dialog>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>
