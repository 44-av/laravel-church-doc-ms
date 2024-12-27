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
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Donations List</h3>
                        <button class="btn bg-blue-700 text-white hover:bg-blue-800" onclick="addModal.showModal()">
                            Donate Now
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 table-auto">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Donor Name</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amount</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($donations as $donation)
                                    <tr class="hover:bg-gray-50" onclick="viewModal{{ $donation->id }}.showModal()">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $donation->donor_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $donation->amount }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $donation->donation_date }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $donation->status }}</td>
                                    </tr>

                                    <!-- View Modal -->
                                    <dialog id="viewModal{{ $donation->id }}" class="modal">
                                        <div class="modal-box rounded-lg shadow-lg">
                                            <div class="flex items-center gap-2">
                                                <button class="btn text-black hover:bg-green-700 hover:text-white me-2"
                                                    type="button" onclick="viewModal{{ $donation->id }}.close()">
                                                    <i class='bx bx-left-arrow-alt'></i>
                                                </button>
                                                <h3 class="text-lg font-bold">View Donation</h3>
                                            </div>
                                            <hr class="my-4">
                                            <div class="mb-4">
                                                <label class="input input-bordered flex items-center gap-2">
                                                    Full Name
                                                    <input type="text" name="donor_name"
                                                        class="grow border-none focus:ring-0 focus:border-none"
                                                        value="{{ $donation->donor_name }}" readonly />
                                                </label>
                                            </div>
                                            <div class="mb-4">
                                                <label class="input input-bordered flex items-center gap-2">
                                                    Email Address
                                                    <input type="text" name="donor_email"
                                                        class="grow border-none focus:ring-0 focus:border-none"
                                                        value="{{ $donation->donor_email }}" readonly />
                                                </label>
                                            </div>
                                            <div class="mb-4">
                                                <label class="input input-bordered flex items-center gap-2">
                                                    Phone Number
                                                    <input type="text" name="donor_phone"
                                                        class="grow border-none focus:ring-0 focus:border-none"
                                                        value="{{ $donation->donor_phone }}" readonly />
                                                </label>
                                            </div>
                                            <div class="mb-4">
                                                <label class="input input-bordered flex items-center gap-2">
                                                    Amount
                                                    <input type="text" name="amount"
                                                        class="grow border-none focus:ring-0 focus:border-none"
                                                        value="{{ $donation->amount }}" readonly />
                                                </label>
                                            </div>
                                            <div class="mb-4">
                                                <label class="input input-bordered flex items-center gap-2">
                                                    Date
                                                    <input type="text" name="date"
                                                        class="grow border-none focus:ring-0 focus:border-none"
                                                        value="{{ $donation->donation_date }}" readonly />
                                                </label>
                                            </div>
                                            <div class="mb-4">
                                                <label class="input input-bordered flex items-center gap-2">
                                                    Transaction ID
                                                    <input type="text" name="transaction_id"
                                                        class="grow border-none focus:ring-0 focus:border-none"
                                                        value="{{ $donation->transaction_id }}" readonly />
                                                </label>
                                            </div>
                                            <div class="mb-4">
                                                <label class="input input-bordered flex items-center gap-2">
                                                    Note
                                                    <input type="text" name="note"
                                                        class="grow border-none focus:ring-0 focus:border-none"
                                                        value="{{ $donation->note }}" readonly />
                                                </label>
                                            </div>
                                            <hr class="my-4">
                                            <div class="flex justify-end">
                                                <button class="btn text-black hover:bg-red-700 hover:text-white"
                                                    type="button"
                                                    onclick="viewModal{{ $donation->id }}.close()">Close</button>
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
    </div>

    <!-- Add Modal -->
    <dialog id="addModal" class="modal">
        <div class="modal-box rounded-lg shadow-lg w-11/12 max-w-3xl">
            <div class="flex items-center gap-2">
                <button class="btn text-black hover:bg-green-700 hover:text-white me-2" type="button"
                    onclick="addModal.close()">
                    <i class='bx bx-left-arrow-alt'></i>
                </button>
                <h3 class="text-lg font-bold">Add Donation</h3>
            </div>
            <hr class="my-4">
            <p>Make an online donation to help our organization provide treatment, care and support services to
                vulnerable children. Please submit your donation through our secure and easy online donation form
                below.
            </p>
            <hr class="my-4">
            <form action="{{ route('donation.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="flex items-center justify-center">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="St. Michael the Archangel Parish Logo"
                        class="w-36 mb-4">
                    <h3 class="text-xl font-bold mb-2 uppercase ms-4">Diocese of Calbayog<br>Parokya ni San Miguel
                        Archangel<br>Basey, Samar</h3>
                </div>
                <h1 class="text-lg font-bold mb-4">Payment Details</h1>
                <p>Mobile Number: 09563782778</p>
                <hr class="my-4">
                <div class="mb-4">
                    <label class="input input-bordered flex items-center gap-2">
                        Full Name
                        <input type="text" name="donor_name"
                            class="grow border-none focus:ring-0 focus:border-none" placeholder="Enter full name" />
                    </label>
                </div>
                <div class="mb-4">
                    <label class="input input-bordered flex items-center gap-2">
                        Email Address
                        <input type="email" name="donor_email"
                            class="grow border-none focus:ring-0 focus:border-none"
                            placeholder="Enter email address" />
                    </label>
                </div>
                <div class="mb-4">
                    <label class="input input-bordered flex items-center gap-2">
                        Phone Number
                        <input type="text" name="donor_phone"
                            class="grow border-none focus:ring-0 focus:border-none"
                            placeholder="Enter phone number" />
                    </label>
                </div>
                <div class="mb-4">
                    <label class="input input-bordered flex items-center gap-2">
                        Donation Amount (₱)
                        <input type="number" name="amount" class="grow border-none focus:ring-0 focus:border-none"
                            placeholder="Enter donation amount" required>
                    </label>
                </div>
                <div class="mb-4">
                    <label class="input input-bordered flex items-center gap-2">
                        Donation Comment
                        <input type="text" name="note" class="grow border-none focus:ring-0 focus:border-none"
                            placeholder="Enter donation comment" />
                    </label>
                </div>
                <div class="mb-4">
                    <label class="input input-bordered flex items-center gap-2">
                        Transaction ID
                        <input type="text" name="transaction_id"
                            class="grow border-none focus:ring-0 focus:border-none" placeholder="Enter transaction ID"
                            required />
                    </label>
                    <label for="transaction_id" class="text-gray-500 text-sm italic text-green-600">
                        This is the transaction ID from your GCash.
                    </label>
                </div>
                <div class="mb-4">
                    <img src="{{ asset('assets/img/qr_code.jpg') }}" alt="QR Code" class="w-52 mx-auto">
                </div>
                <hr class="my-4">
                <div class="flex justify-end">
                    <button type="submit" class="btn bg-blue-700 text-white hover:bg-blue-800">Donate</button>
                    <button class="btn text-black hover:bg-red-700 hover:text-white" type="button"
                        onclick="addModal.close()">Close</button>
                </div>
            </form>
        </div>
    </dialog>
</x-app-layout>
