<x-app-layout>
    <div class="py-2 bg-gray-100 h-full mt-4 rounded-2xl">
        <div class="max-w-7xl mx-auto space-y-6">
            <div class="bg-white overflow-hidden">
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
                    <div class="mb-4">
                        <form action="{{ route('request') }}" method="GET" class="flex items-center" id="searchForm">
                            <input type="text" name="search" placeholder="Search Requests..."
                                class="input input-bordered w-full max-w-xs" />
                            <button type="submit"
                                class="ml-2 bg-green-500 text-white rounded-md px-4 py-3 hover:bg-green-600">
                                <i class='bx bx-search'></i>
                            </button>
                        </form>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Requests List</h3>
                        <button class="btn bg-blue-700 text-white hover:bg-blue-800"
                            onclick="document.getElementById('addModal').showModal()">
                            Request For Certificate
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 table-auto">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Document Type</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Approved By</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Paid</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Notes</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($requests as $request)
                                    <tr class="cursor-pointer"
                                        onclick="document.getElementById('viewModal{{ $request->id }}').showModal()">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $request->document_type }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $request->request_approved->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $request->status }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $request->is_paid }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ strlen($request->notes) > 2 ? substr($request->notes, 0, 2) . '...' : $request->notes }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($request->is_paid == 'Unpaid')
                                                <button class="btn bg-blue-700 text-white hover:bg-blue-800"
                                                    onclick="event.stopPropagation(); editModal{{ $request->id }}.showModal()">
                                                    Pay Now
                                                </button>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <dialog id="editModal{{ $request->id }}" class="modal">
                                        <div class="modal-box rounded-lg shadow-lg w-11/12 max-w-5xl">
                                            <div class="flex items-center gap-2">
                                                <button class="btn text-black hover:bg-green-700 hover:text-white me-2"
                                                    type="button" onclick="editModal{{ $request->id }}.close()">
                                                    <i class='bx bx-left-arrow-alt'></i>
                                                </button>
                                                <h2 class="text-lg font-bold">Payment Information</h2>
                                            </div>
                                            <hr class="my-4">
                                            <form action="{{ route('payment.update', $request->id) }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                    <div class="sm:col-span-3">
                                                        <label for="amount"
                                                            class="block text-sm/6 font-medium text-gray-900">Amount</label>
                                                        <div class="mt-2">
                                                            <input type="number" name="amount"
                                                                class="input input-bordered w-full"
                                                                value="{{ $request->certificate_type->amount ?? '' }}"
                                                                readonly />
                                                        </div>
                                                    </div>
                                                    <div class="sm:col-span-3">
                                                        <label for="transaction_id"
                                                            class="block text-sm/6 font-medium text-gray-900">Transaction
                                                            ID</label>
                                                        <div class="mt-2">
                                                            <input type="text" name="transaction_id"
                                                                class="input input-bordered w-full"
                                                                value="{{ $request->payment->transaction_id ?? '' }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                    <div class="sm:col-span-8">
                                                        <label for="payment_method"
                                                            class="block text-sm/6 font-medium text-gray-900">Payment
                                                            Method</label>
                                                        <div class="mt-2">
                                                            <select name="payment_method"
                                                                class="input input-bordered w-full">
                                                                <option value="">Select Payment Method</option>
                                                                <option value="GCash">GCash</option>
                                                                <option value="Walk-in">Walk-in</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="sm:col-span-3" id="GCashQR" style="display: none;">
                                                        <label class="block text-sm/6 font-medium text-gray-900">GCash
                                                            QR Code</label>
                                                        @if ($request->document_type == 'Baptismal Certificate')
                                                            <div class="mt-2">
                                                                <img src="{{ asset('assets/img/baptismal_certificate.png') }}"
                                                                    alt="GCash QR Code" class="w-full h-full mx-auto">
                                                            </div>
                                                        @endif
                                                        @if ($request->document_type == 'Marriage Certificate')
                                                            <div class="mt-2">
                                                                <img src="{{ asset('assets/img/marriage_certificate.png') }}"
                                                                    alt="GCash QR Code" class="w-full h-full mx-auto">
                                                            </div>
                                                        @endif
                                                        @if ($request->document_type == 'Death Certificate')
                                                            <div class="mt-2">
                                                                <img src="{{ asset('assets/img/death_certificate.png') }}"
                                                                    alt="GCash QR Code" class="w-full h-full mx-auto">
                                                            </div>
                                                        @endif
                                                        @if ($request->document_type == 'Confirmation Certificate')
                                                            <div class="mt-2">
                                                                <img src="{{ asset('assets/img/confirmation_certificate.png') }}"
                                                                    alt="GCash QR Code" class="w-full h-full mx-auto">
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <hr class="my-4">
                                                <div class="flex justify-end">
                                                    <button name="submit"
                                                        class="btn bg-blue-700 text-white hover:bg-blue-800"
                                                        type="submit">Save</button>
                                                    <button
                                                        class="btn text-black hover:bg-red-700 hover:text-white ms-2"
                                                        type="button"
                                                        onclick="editModal{{ $request->id }}.close()">Close</button>
                                                </div>
                                            </form>
                                        </div>
                                    </dialog>

                                    <!-- View Modal -->
                                    <dialog id="viewModal{{ $request->id }}" class="modal">
                                        <div class="modal-box rounded-lg shadow-lg w-11/12 max-w-5xl">
                                            <button class="btn text-black hover:bg-green-700 hover:text-white me-2"
                                                type="button" onclick="viewModal{{ $request->id }}.close()">
                                                <i class='bx bx-left-arrow-alt'></i>
                                            </button>
                                            <hr class="my-4">
                                            @if ($request->document_type == 'Baptismal Certificate' && $request->id)
                                                <h2 class="text-lg font-bold mb-4">Baptismal Certificate Details
                                                </h2>
                                                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                    <div class="sm:col-span-3">
                                                        <label class="input input-bordered flex items-center gap-2">
                                                            Certificate Type
                                                            <input type="text" name="certificate_type"
                                                                class="grow border-none focus:ring-0 focus:border-none"
                                                                value="{{ $request->certificate_detail->certificate_type }}"
                                                                readonly />
                                                        </label>
                                                    </div>
                                                    <div class="sm:col-span-3">
                                                        <label class="input input-bordered flex items-center gap-2">
                                                            Name of Child
                                                            <input type="text" name="name_of_child"
                                                                class="grow border-none focus:ring-0 focus:border-none"
                                                                value="{{ $request->certificate_detail->name_of_child }}"
                                                                readonly />
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                    <div class="sm:col-span-3">
                                                        <label class="input input-bordered flex items-center gap-2">
                                                            Date of Birth
                                                            <input type="text" name="date_of_birth"
                                                                class="grow border-none focus:ring-0 focus:border-none"
                                                                value="{{ $request->certificate_detail->date_of_birth }}"
                                                                readonly />
                                                        </label>
                                                    </div>
                                                    <div class="sm:col-span-3">
                                                        <label class="input input-bordered flex items-center gap-2">
                                                            Place of Birth
                                                            <input type="text" name="place_of_birth"
                                                                class="grow border-none focus:ring-0 focus:border-none"
                                                                value="{{ $request->certificate_detail->place_of_birth }}"
                                                                readonly />
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                    <div class="sm:col-span-6">
                                                        <label class="input input-bordered flex items-center gap-2">
                                                            Date of Baptism
                                                            <input type="text" name="baptism_schedule"
                                                                class="grow border-none focus:ring-0 focus:border-none"
                                                                value="{{ $request->certificate_detail->baptism_schedule }}"
                                                                readonly />
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                    <div class="sm:col-span-3">
                                                        <label class="input input-bordered flex items-center gap-2">
                                                            Name of Father
                                                            <input type="text" name="name_of_father"
                                                                class="grow border-none focus:ring-0 focus:border-none"
                                                                value="{{ $request->certificate_detail->name_of_father }}"
                                                                readonly />
                                                        </label>
                                                    </div>
                                                    <div class="sm:col-span-3">
                                                        <label class="input input-bordered flex items-center gap-2">
                                                            Name of Mother
                                                            <input type="text" name="name_of_mother"
                                                                class="grow border-none focus:ring-0 focus:border-none"
                                                                value="{{ $request->certificate_detail->name_of_mother }}"
                                                                readonly />
                                                        </label>
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($request->document_type == 'Marriage Certificate' && $request->id)
                                                <h2 class="text-lg font-bold mb-4">Marriage Certificate Details
                                                </h2>
                                                <div class="flex flex-col gap-4">
                                                    <h3 class="text-md font-bold mb-4">Bride Information</h3>
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                Bride Name
                                                                <input type="text" name="bride_name"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->bride_name }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                Birthdate of Bride
                                                                <input type="text" name="birthdate_bride"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->birthdate_bride }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                Age of Bride
                                                                <input type="text" name="age_bride"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->age_bride }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                Birthplace of Bride
                                                                <input type="text" name="birthplace_bride"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->birthplace_bride }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                Citizenship of Bride
                                                                <input type="text" name="citizenship_bride"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->citizenship_bride }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                Religion of Bride
                                                                <input type="text" name="religion_bride"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->religion_bride }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                Residence of Bride
                                                                <input type="text" name="residence_bride"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->residence_bride }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                Civil Status of Bride
                                                                <input type="text" name="civil_status_bride"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->civil_status_bride }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                Name of Father of Bride
                                                                <input type="text" name="name_of_father_bride"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->name_of_father_bride }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                Name of Mother of Bride
                                                                <input type="text" name="name_of_mother_bride"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->name_of_mother_bride }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <h3 class="text-md font-bold mb-4">Groom Information</h3>
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                Name of Groom
                                                                <input type="text" name="name_of_groom"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->name_of_groom }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                Birthdate of Groom
                                                                <input type="text" name="birthdate_groom"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->birthdate_groom }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                Age of Groom
                                                                <input type="text" name="age_groom"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->age_groom }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                Birthplace of Groom
                                                                <input type="text" name="birthplace_groom"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->birthplace_groom }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                Citizenship of Groom
                                                                <input type="text" name="citizenship_groom"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->citizenship_groom }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                Religion of Groom
                                                                <input type="text" name="religion_groom"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->religion_groom }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                Residence of Groom
                                                                <input type="text" name="residence_groom"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->residence_groom }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                Civil Status of Groom
                                                                <input type="text" name="civil_status_groom"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->civil_status_groom }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                Name of Father of Groom
                                                                <input type="text" name="name_of_father_groom"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->name_of_father_groom }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                Name of Mother of Groom
                                                                <input type="text" name="name_of_mother_groom"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->name_of_mother_groom }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($request->document_type == 'Death Certificate' && $request->id)
                                                <h2 class="text-lg font-bold mb-4">Death Certificate Details</h2>
                                                <div class="flex flex-col gap-4">
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                First Name of Deceased
                                                                <input type="text" name="first_name_death"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->first_name_death ?? '' }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                Middle Name of Deceased
                                                                <input type="text" name="middle_name_death"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->middle_name_death ?? '' }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                Last Name of Deceased
                                                                <input type="text" name="last_name_death"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->last_name_death ?? '' }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                Date of Birth of Deceased
                                                                <input type="text" name="date_of_birth_death"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->date_of_birth_death ?? '' }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                Date of Death of Deceased
                                                                <input type="text" name="date_of_death_death"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->date_of_death ?? '' }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                Minister of Deceased
                                                                <input type="text" name="minister_death"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->minister_death ?? '' }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                File of Deceased
                                                                <input type="text" name="file_death"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->file_death ?? '' }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($request->document_type == 'Confirmation Certificate' && $request->id)
                                                <h2 class="text-lg font-bold mb-4">Confirmation Certificate Details
                                                </h2>
                                                <div class="flex flex-col gap-4">
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                First Name
                                                                <input type="text" name="confirmation_first_name"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->confirmation_first_name ?? '' }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                Middle Name
                                                                <input type="text" name="confirmation_middle_name"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->confirmation_middle_name ?? '' }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                Last Name
                                                                <input type="text" name="confirmation_last_name"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->confirmation_last_name ?? '' }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                Place of Birth
                                                                <input type="text"
                                                                    name="confirmation_place_of_birth"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->confirmation_place_of_birth ?? '' }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                Date of Baptism
                                                                <input type="date"
                                                                    name="confirmation_date_of_baptism"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->confirmation_date_of_baptism ?? '' }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                Fathers Name
                                                                <input type="text" name="confirmation_fathers_name"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->confirmation_fathers_name ?? '' }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                Mothers Name
                                                                <input type="text" name="confirmation_mothers_name"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->confirmation_mothers_name ?? '' }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                Date of Confirmation
                                                                <input type="date"
                                                                    name="confirmation_date_of_confirmation"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->confirmation_date_of_confirmation ?? '' }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label
                                                                class="input input-bordered flex items-center gap-2">
                                                                Sponsors Name
                                                                <input type="text"
                                                                    name="confirmation_sponsors_name"
                                                                    class="grow border-none focus:ring-0 focus:border-none"
                                                                    value="{{ $request->certificate_detail->confirmation_sponsors_name ?? '' }}"
                                                                    readonly />
                                                            </label>
                                                        </div>
                                                    </div>
                                            @endif
                                            <hr class="my-4">
                                            <div class="flex justify-end">
                                                <button class="btn text-black hover:bg-red-700 hover:text-white"
                                                    type="button"
                                                    onclick="document.getElementById('viewModal{{ $request->id }}').close()">Close</button>
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



</x-app-layout>
