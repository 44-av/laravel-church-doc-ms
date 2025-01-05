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
                        <form action="{{ route('approval_request') }}" method="GET" class="flex items-center"
                            id="searchForm">
                            <input type="text" name="search" placeholder="Search Requests..."
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
                                        Document Type</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Requested By</th>
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
                                        Date & Time</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($requests as $request)
                                    <tr class="cursor-pointer hover:bg-gray-100"
                                        onclick="viewModal{{ $request->id }}.showModal()">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $request->document_type }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $request->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $request->status }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $request->is_paid }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ strlen($request->notes) > 1 ? substr($request->notes, 0, 1) . '...' : $request->notes }}
                                        </td>
                                        <td class="px-6 py-6 whitespace-nowrap">
                                            {{ strlen($request->created_at) > 5 ? substr($request->created_at, 0, 5) . '...' : $request->created_at }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button class="btn bg-green-700 hover:bg-green-800 text-white"
                                                onclick="event.stopPropagation(); approvalModal{{ $request->id }}.showModal()">
                                                Change Status
                                            </button>
                                            <button class="btn bg-red-700 hover:bg-red-800 text-white"
                                                onclick="event.stopPropagation(); destroyModal{{ $request->id }}.showModal()">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                    <!-- Approval Modal -->
                                    <dialog id="approvalModal{{ $request->id }}" class="modal">
                                        <div class="modal-box rounded-lg shadow-lg">
                                            <div class="flex items-center">
                                                <button class="btn text-black hover:bg-green-700 hover:text-white me-2"
                                                    type="button" onclick="approvalModal{{ $request->id }}.close()">
                                                    <i class='bx bx-left-arrow-alt'></i>
                                                </button>
                                                <h3 class="text-lg font-bold">Approval Request</h3>
                                            </div>
                                            <hr class="my-4">
                                            <form action="{{ route('approval_request.update', $request->id) }}"
                                                method="POST" enctype="multipart/form-data" id="approvalForm">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="approved_by"
                                                    value="{{ $request->user->name }}">
                                                <input type="hidden" name="requested_by"
                                                    value="{{ $request->user->name }}">
                                                <input type="hidden" name="document_type"
                                                    value="{{ $request->document_type }}">
                                                <input type="hidden" name="amount"
                                                    value="{{ $request->payment->amount ?? '' }}">
                                                <input type="hidden" name="transaction_id"
                                                    value="{{ $request->payment->transaction_id ?? '' }}">
                                                <input type="hidden" name="number_of_copies"
                                                    value="{{ $request->certificate_detail->number_of_copies }}">
                                                <div class="mb-4">
                                                    <label class="block text-gray-700 font-medium">Status</label>
                                                    <select name="status" id="status{{ $request->id }}"
                                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 p-3 transition duration-150 ease-in-out"
                                                        required>
                                                        <option value="Pending"
                                                            {{ $request->status == 'Pending' ? 'selected' : '' }}>
                                                            Pending
                                                        </option>
                                                        <option value="Decline"
                                                            {{ $request->status == 'Decline' ? 'selected' : '' }}>
                                                            Decline
                                                        </option>
                                                        <option value="Approved"
                                                            {{ $request->status == 'Approved' ? 'selected' : '' }}>
                                                            Approved
                                                        </option>
                                                        <option value="On Process"
                                                            {{ $request->status == 'On Process' ? 'selected' : '' }}>On
                                                            Process</option>
                                                        <option value="Checking"
                                                            {{ $request->status == 'Checking' ? 'selected' : '' }}>
                                                            Checking
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="mb-4" id="notes{{ $request->id }}"
                                                    style="display: none;">
                                                    <label class="block text-gray-700 font-medium">Notes</label>
                                                    <textarea name="notes"
                                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 p-3 transition duration-150 ease-in-out">{{ $request->notes }}</textarea>
                                                </div>
                                                <hr class="my-4">
                                                <div class="flex justify-end">
                                                    <button class="btn text-black hover:bg-red-700 hover:text-white"
                                                        type="button"
                                                        onclick="approvalModal{{ $request->id }}.close()">Close</button>
                                                    <button class="btn bg-blue-700 hover:bg-blue-800 text-white"
                                                        type="submit">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </dialog>

                                    <!-- View Modal -->
                                    <dialog id="viewModal{{ $request->id }}" class="modal">
                                        <div class="modal-box rounded-lg shadow-lg w-11/12 ">
                                            <div class="flex items-center">
                                                <button class="btn text-black hover:bg-green-700 hover:text-white me-2"
                                                    type="button" onclick="viewModal{{ $request->id }}.close()">
                                                    <i class='bx bx-left-arrow-alt'></i>
                                                </button>
                                                <h3 class="text-lg font-bold">View Request</h3>
                                            </div>
                                            <hr class="my-4">
                                            @if ($request->document_type == 'Baptismal Certificate')
                                                <h2 class="text-lg font-bold mb-4">Baptismal Certificate Details</h2>
                                                <div class="flex flex-col gap-4">
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label for="certificate_type"
                                                                class="block text-sm/6 font-medium text-gray-900">Certificate
                                                                Type</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="certificate_type"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->certificate_type }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label for="name_of_child"
                                                                class="block text-sm/6 font-medium text-gray-900">Name
                                                                of Child</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="name_of_child"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->name_of_child }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label for="date_of_birth"
                                                                class="block text-sm/6 font-medium text-gray-900">Date
                                                                of Birth</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="date_of_birth"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->date_of_birth }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label for="place_of_birth"
                                                                class="block text-sm/6 font-medium text-gray-900">Place
                                                                of Birth</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="place_of_birth"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->place_of_birth }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label for="baptism_schedule"
                                                                class="block text-sm/6 font-medium text-gray-900">Date
                                                                of Baptism</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="baptism_schedule"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->baptism_schedule }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label for="name_of_father"
                                                                class="block text-sm/6 font-medium text-gray-900">Name
                                                                of Father</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="name_of_father"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->name_of_father }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label for="name_of_mother"
                                                                class="block text-sm/6 font-medium text-gray-900">Name
                                                                of Mother</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="name_of_mother"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->name_of_mother }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($request->document_type == 'Marriage Certificate')
                                                <h2 class="text-lg font-bold mb-4">Marriage Certificate Details</h2>
                                                <div class="flex flex-col gap-4">
                                                    <h3 class="text-md font-bold mb-4">Bride Information</h3>
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label for="bride_name"
                                                                class="block text-sm/6 font-medium text-gray-900">Bride
                                                                Name</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="bride_name"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->bride_name }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label for="age_bride"
                                                                class="block text-sm/6 font-medium text-gray-900">Age
                                                                of Bride</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="age_bride"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->age_bride }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label for="birthdate_bride"
                                                                class="block text-sm/6 font-medium text-gray-900">Birthdate
                                                                of Bride</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="birthdate_bride"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->birthdate_bride }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label for="birthplace_bride"
                                                                class="block text-sm/6 font-medium text-gray-900">Birthplace
                                                                of Bride</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="birthplace_bride"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->birthplace_bride }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label for="religion_bride"
                                                                class="block text-sm/6 font-medium text-gray-900">Religion
                                                                of Bride</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="religion_bride"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->religion_bride }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label for="residence_bride"
                                                                class="block text-sm/6 font-medium text-gray-900">Residence
                                                                of Bride</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="residence_bride"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->residence_bride }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label for="civil_status_bride"
                                                                class="block text-sm/6 font-medium text-gray-900">Civil
                                                                Status of Bride</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="civil_status_bride"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->civil_status_bride }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label for="name_of_father_bride"
                                                                class="block text-sm/6 font-medium text-gray-900">Name
                                                                of Father of Bride</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="name_of_father_bride"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->name_of_father_bride }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label for="name_of_mother_bride"
                                                                class="block text-sm/6 font-medium text-gray-900">Name
                                                                of Mother of Bride</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="name_of_mother_bride"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->name_of_mother_bride }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <hr class="my-4">
                                                    <h3 class="text-md font-bold mb-4">Groom Information</h3>
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label for="name_of_groom"
                                                                class="block text-sm/6 font-medium text-gray-900">Groom
                                                                Name</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="name_of_groom"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->name_of_groom }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label for="age_groom"
                                                                class="block text-sm/6 font-medium text-gray-900">Age
                                                                of Groom</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="age_groom"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->age_groom }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label for="birthdate_groom"
                                                                class="block text-sm/6 font-medium text-gray-900">Birthdate
                                                                of Groom</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="birthdate_groom"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->birthdate_groom }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label for="birthplace_groom"
                                                                class="block text-sm/6 font-medium text-gray-900">Birthplace
                                                                of Groom</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="birthplace_groom"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->birthplace_groom }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label for="citizenship_groom"
                                                                class="block text-sm/6 font-medium text-gray-900">Citizenship
                                                                of Groom</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="citizenship_groom"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->citizenship_groom }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label for="religion_groom"
                                                                class="block text-sm/6 font-medium text-gray-900">Religion
                                                                of Groom</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="religion_groom"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->religion_groom }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label for="civil_status_groom"
                                                                class="block text-sm/6 font-medium text-gray-900">Civil
                                                                Status of Groom</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="civil_status_groom"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->civil_status_groom }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label for="name_of_father_groom"
                                                                class="block text-sm/6 font-medium text-gray-900">Name
                                                                of Father of Groom</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="name_of_father_groom"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->name_of_father_groom }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label for="name_of_mother_groom"
                                                                class="block text-sm/6 font-medium text-gray-900">Name
                                                                of Mother of Groom</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="name_of_mother_groom"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->name_of_mother_groom }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($request->document_type == 'Death Certificate')
                                                <h2 class="text-lg font-bold mb-4">Death Certificate Details</h2>
                                                <div class="flex flex-col gap-4">
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label for="first_name_burial"
                                                                class="block text-sm/6 font-medium text-gray-900">First
                                                                Name of Deceased</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="first_name_death"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->first_name_death }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label for="middle_name_burial"
                                                                class="block text-sm/6 font-medium text-gray-900">Middle
                                                                Name of Deceased</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="middle_name_death"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->middle_name_death }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label for="last_name_burial"
                                                                class="block text-sm/6 font-medium text-gray-900">Last
                                                                Name of Deceased</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="last_name_death"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->last_name_death }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label for="date_of_birth_death"
                                                                class="block text-sm/6 font-medium text-gray-900">Date
                                                                of Birth of Deceased</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="date_of_birth_death"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->date_of_birth_death }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label for="date_of_death"
                                                                class="block text-sm/6 font-medium text-gray-900">Date
                                                                of Death</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="date_of_death"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->date_of_death }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label for="file_death"
                                                                class="block text-sm/6 font-medium text-gray-900">File
                                                                of Death</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="file_death"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->file_death }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($request->document_type == 'Confirmation Certificate')
                                                <h2 class="text-lg font-bold mb-4">Confirmation Certificate Details
                                                </h2>
                                                <div class="flex flex-col gap-4">
                                                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label for="confirmation_first_name"
                                                                class="block text-sm/6 font-medium text-gray-900">First
                                                                Name</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="confirmation_first_name"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->confirmation_first_name }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label for="confirmation_middle_name"
                                                                class="block text-sm/6 font-medium text-gray-900">Middle
                                                                Name</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="confirmation_middle_name"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->confirmation_middle_name }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label for="confirmation_last_name"
                                                                class="block text-sm/6 font-medium text-gray-900">Last
                                                                Name</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="confirmation_last_name"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->confirmation_last_name }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label for="confirmation_place_of_birth"
                                                                class="block text-sm/6 font-medium text-gray-900">Place
                                                                of Birth</label>
                                                            <div class="mt-2">
                                                                <input type="text"
                                                                    name="confirmation_place_of_birth"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->confirmation_place_of_birth }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label for="confirmation_date_of_baptism"
                                                                class="block text-sm/6 font-medium text-gray-900">Date
                                                                of Baptism</label>
                                                            <div class="mt-2">
                                                                <input type="text"
                                                                    name="confirmation_date_of_baptism"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->confirmation_date_of_baptism }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label for="confirmation_fathers_name"
                                                                class="block text-sm/6 font-medium text-gray-900">Fathers
                                                                Name</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="confirmation_fathers_name"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->confirmation_fathers_name }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label for="confirmation_mothers_name"
                                                                class="block text-sm/6 font-medium text-gray-900">Mothers
                                                                Name</label>
                                                            <div class="mt-2">
                                                                <input type="text" name="confirmation_mothers_name"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->confirmation_mothers_name }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                        <div class="sm:col-span-3">
                                                            <label for="confirmation_date_of_confirmation"
                                                                class="block text-sm/6 font-medium text-gray-900">Date
                                                                of Confirmation</label>
                                                            <div class="mt-2">
                                                                <input type="text"
                                                                    name="confirmation_date_of_confirmation"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->confirmation_date_of_confirmation }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                        <div class="sm:col-span-3">
                                                            <label for="confirmation_sponsors_name"
                                                                class="block text-sm/6 font-medium text-gray-900">Sponsors
                                                                Name</label>
                                                            <div class="mt-2">
                                                                <input type="text"
                                                                    name="confirmation_sponsors_name"
                                                                    class="input input-bordered w-full max-w-xs"
                                                                    value="{{ $request->certificate_detail->confirmation_sponsors_name }}"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <hr class="my-4">
                                            <h2 class="text-lg font-bold mb-4 mt-4">Payment Information</h2>
                                            <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                <div class="sm:col-span-3">
                                                    <label for="amount"
                                                        class="block text-sm/6 font-medium text-gray-900">Amount</label>
                                                    <div class="mt-2">
                                                        <input type="text" name="amount"
                                                            class="input input-bordered w-full max-w-xs"
                                                            value="{{ $request->payment->amount ?? '' }}" readonly />
                                                    </div>
                                                </div>
                                                <div class="sm:col-span-3">
                                                    <label for="transaction_id"
                                                        class="block text-sm/6 font-medium text-gray-900">Transaction
                                                        ID</label>
                                                    <div class="mt-2">
                                                        <input type="text" name="transaction_id"
                                                            class="input input-bordered w-full max-w-xs"
                                                            value="{{ $request->payment->transaction_id ?? '' }}"
                                                            readonly />
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class="my-4">
                                            <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                <div class="sm:col-span-3">
                                                    <label for="payment_method"
                                                        class="block text-sm/6 font-medium text-gray-900">Payment
                                                        Method</label>
                                                    <div class="mt-2">
                                                        <input type="text" name="payment_method"
                                                            class="input input-bordered w-full max-w-xs"
                                                            value="{{ $request->payment->payment_method ?? '' }}"
                                                            readonly />
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class="my-4">
                                            <div class="flex justify-end">
                                                <button class="btn text-black hover:bg-red-700 hover:text-white"
                                                    type="button"
                                                    onclick="viewModal{{ $request->id }}.close()">Close</button>
                                            </div>
                                        </div>
                                    </dialog>

                                    <!-- Destroy Modal -->
                                    <dialog id="destroyModal{{ $request->id }}" class="modal">
                                        <div class="modal-box rounded-lg shadow-lg">
                                            <h3 class="text-lg font-bold mb-4">Delete Request</h3>
                                            <p>Are you sure you want to delete this request?</p>
                                            <div class="flex justify-end">
                                                <form action="{{ route('approval_request.destroy', $request->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <hr class="my-4">
                                                    <button class="btn text-black hover:bg-red-700 hover:text-white"
                                                        type="button"
                                                        onclick="destroyModal{{ $request->id }}.close()">Close</button>
                                                    <button class="btn bg-red-700 hover:bg-red-800 text-white"
                                                        type="submit">Delete</button>
                                                </form>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            @foreach ($requests as $request)
                $('#status{{ $request->id }}').on('change', function() {
                    if ($(this).val() === 'Decline' && !'{{ $request->notes }}') {
                        $('#notes{{ $request->id }}').show();
                    } else {
                        $('#notes{{ $request->id }}').hide();
                    }
                });

                $('#status{{ $request->id }}').trigger('change');
            @endforeach
        });
    </script>
</x-app-layout>
