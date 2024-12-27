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
                        <form action="{{ route('priests') }}" method="GET" class="flex items-center" id="searchForm">
                            <input type="text" name="search" placeholder="Search Priest....."
                                class="input input-bordered w-full max-w-xs" value="{{ request('search') }}" />
                            <button type="submit"
                                class="ml-2 bg-green-500 text-white rounded-md px-4 py-3 hover:bg-green-600">
                                Search
                            </button>
                        </form>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Priests List</h3>
                        <button class="btn btn-primary" onclick="addModal.showModal()">
                            Add Priest
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 table-auto">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Full Name</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Title</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email Address</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ordination Date</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($priests as $priest)
                                    <tr class="cursor-pointer" onclick="viewModal{{ $priest->id }}.showModal()">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            REV. FR. {{ $priest->first_name }} {{ $priest->middle_name }}
                                            {{ $priest->last_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $priest->title }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $priest->email_address }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $priest->ordination_date }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button class="btn bg-blue-700 hover:bg-blue-800 text-white"
                                                onclick="event.stopPropagation(); editModal{{ $priest->id }}.showModal()">
                                                Edit
                                            </button>
                                            <button class="btn bg-red-700 hover:bg-red-800 text-white"
                                                onclick="event.stopPropagation(); destroyModal{{ $priest->id }}.showModal()">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <dialog id="editModal{{ $priest->id }}" class="modal">
                                        <div class="modal-box rounded-lg shadow-lg w-11/12 max-w-5xl">
                                            <h3 class="text-lg font-bold mb-4">Edit Priest</h3>
                                            <hr class="my-4">
                                            <form action="{{ route('priest.update', $priest->id) }}" method="POST"
                                                enctype="multipart/form-data" id="editForm">
                                                @csrf
                                                @method('PUT')
                                                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                    <div class="sm:col-span-3">
                                                        <label class="input input-bordered flex items-center gap-2">
                                                            First Name
                                                            <input type="text"
                                                                class="grow border-none focus:border-none focus:ring-0"
                                                                placeholder="First Name"
                                                                value="{{ $priest->first_name }}" />
                                                        </label>
                                                    </div>
                                                    <div class="sm:col-span-3">
                                                        <label class="input input-bordered flex items-center gap-2">
                                                            Middle Name
                                                            <input type="text" name="middle_name"
                                                                class="grow border-none focus:border-none focus:ring-0"
                                                                placeholder="Middle Name"
                                                                value="{{ $priest->middle_name }}" />
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                    <div class="sm:col-span-3">
                                                        <label class="input input-bordered flex items-center gap-2">
                                                            Last Name
                                                            <input type="text" name="last_name"
                                                                class="grow border-none focus:border-none focus:ring-0"
                                                                placeholder="Last Name"
                                                                value="{{ $priest->last_name }}" />
                                                        </label>
                                                    </div>
                                                    <div class="sm:col-span-3">
                                                        <label class="input input-bordered flex items-center gap-2">
                                                            Title
                                                            <input type="text" name="title"
                                                                class="grow border-none focus:border-none focus:ring-0"
                                                                placeholder="Enter Title (e.g., Father, Rev.)"
                                                                value="{{ $priest->title }}" />
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                    <div class="sm:col-span-3">
                                                        <label class="input input-bordered flex items-center gap-2">
                                                            Date of Birth
                                                            <input type="date" name="date_of_birth"
                                                                class="grow border-none focus:border-none focus:ring-0"
                                                                placeholder="Date of Birth"
                                                                value="{{ $priest->date_of_birth }}" />
                                                        </label>
                                                    </div>
                                                    <div class="sm:col-span-3">
                                                        <label class="input input-bordered flex items-center gap-2">
                                                            Phone Number
                                                            <input type="tel" name="phone_number"
                                                                class="grow border-none focus:border-none focus:ring-0"
                                                                placeholder="Enter Phone Number"
                                                                value="{{ $priest->phone_number }}" />
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                                    <div class="sm:col-span-3">
                                                        <label class="input input-bordered flex items-center gap-2">
                                                            Email Address
                                                            <input type="email" name="email_address"
                                                                class="grow border-none focus:border-none focus:ring-0"
                                                                placeholder="Enter Email Address"
                                                                value="{{ $priest->email_address }}" />
                                                        </label>
                                                    </div>
                                                    <div class="sm:col-span-3">
                                                        <label class="input input-bordered flex items-center gap-2">
                                                            Ordination Date
                                                            <input type="date" name="ordination_date"
                                                                class="grow border-none focus:border-none focus:ring-0"
                                                                placeholder="Enter Ordination Date"
                                                                value="{{ $priest->ordination_date }}" />
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="mt-4">
                                                    <label class="block text-gray-700 font-medium">Image</label>
                                                    <input type="file" name="image" accept="image/*"
                                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 p-3 transition duration-150 ease-in-out">
                                                </div>
                                                <hr class="my-4">
                                                <div class="flex justify-end">
                                                    <button class="btn bg-blue-700 hover:bg-blue-800 text-white me-2"
                                                        type="submit">Update</button>
                                                    <button class="btn text-black hover:bg-red-700 hover:text-white"
                                                        type="button"
                                                        onclick="editModal{{ $priest->id }}.close()">Close</button>
                                                </div>
                                            </form>
                                        </div>
                                    </dialog>

                                    <!-- Delete Modal -->
                                    <dialog id="destroyModal{{ $priest->id }}" class="modal">
                                        <div class="modal-box rounded-lg shadow-lg">
                                            <h3 class="text-lg font-bold mb-4">Delete Priest</h3>
                                            <p class="text-gray-500 mb-4">Are you sure you want to delete this priest?
                                            </p>
                                            <div class="flex justify-end">
                                                <form action="{{ route('priest.destroy', $priest->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn bg-red-700 hover:bg-red-800 text-white me-2"
                                                        type="submit">Delete</button>
                                                    <button class="btn text-black hover:bg-red-700 hover:text-white"
                                                        type="button"
                                                        onclick="destroyModal{{ $priest->id }}.close()">Close</button>
                                                </form>
                                            </div>
                                        </div>
                                    </dialog>

                                    <!-- View Modal -->
                                    <dialog id="viewModal{{ $priest->id }}" class="modal">
                                        <div class="modal-box rounded-lg shadow-lg w-11/12 max-w-3xl">
                                            <h3 class="text-lg font-semibold mb-4 font-bold">View Priest</h3>
                                            <hr class="my-4">
                                            <div
                                                class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-6">
                                                <img src="{{ asset('assets/priests/' . $priest->image) }}"
                                                    alt="Priest Image" class="w-1/3 rounded-3xl">
                                                <div class="flex flex-col space-y-2 w-full">
                                                    <div class="flex">
                                                        <label class="w-1/3 text-gray-700 font-medium">Full
                                                            Name:</label>
                                                        <input type="text" name="full_name"
                                                            class="w-2/3 text-gray-700 border rounded"
                                                            value="REV. FR. {{ $priest->last_name }} {{ $priest->first_name }} {{ $priest->middle_name }}"
                                                            readonly>
                                                    </div>
                                                    <div class="flex">
                                                        <label class="w-1/3 text-gray-700 font-medium">Title:</label>
                                                        <input type="text" name="title"
                                                            class="w-2/3 text-gray-700 border rounded"
                                                            value="{{ $priest->title }}" readonly>
                                                    </div>
                                                    <div class="flex">
                                                        <label class="w-1/3 text-gray-700 font-medium">Email
                                                            Address:</label>
                                                        <input type="email" name="email_address"
                                                            class="w-2/3 text-gray-700 border rounded"
                                                            value="{{ $priest->email_address }}" readonly>
                                                    </div>
                                                    <div class="flex">
                                                        <label class="w-1/3 text-gray-700 font-medium">Ordination
                                                            Date:</label>
                                                        <input type="date" name="ordination_date"
                                                            class="w-2/3 text-gray-700 border rounded"
                                                            value="{{ $priest->ordination_date }}" readonly>
                                                    </div>
                                                    <div class="flex">
                                                        <label class="w-1/3 text-gray-700 font-medium">Phone
                                                            Number:</label>
                                                        <input type="tel" name="phone_number"
                                                            class="w-2/3 text-gray-700 border rounded"
                                                            value="{{ $priest->phone_number }}" readonly>
                                                    </div>
                                                    <div class="flex">
                                                        <label class="w-1/3 text-gray-700 font-medium">Date of
                                                            Birth:</label>
                                                        <input type="date" name="date_of_birth"
                                                            class="w-2/3 text-gray-700 border rounded"
                                                            value="{{ $priest->date_of_birth }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class="my-4">
                                            <div class="flex justify-end">
                                                <button class="btn text-black hover:bg-red-700 hover:text-white"
                                                    type="button"
                                                    onclick="viewModal{{ $priest->id }}.close()">Close</button>
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

        <!-- Add Modal -->
        <dialog id="addModal" class="modal">
            <div class="modal-box rounded-lg shadow-lg w-11/12 max-w-5xl">
                <h3 class="text-lg font-bold mb-4">Add Priest</h3>
                <hr class="my-4">
                <form action="{{ route('priest.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label class="input input-bordered flex items-center gap-2">
                                First Name
                                <input type="text" name="first_name"
                                    class="grow border-none focus:border-none focus:ring-0"
                                    placeholder="First Name" />
                            </label>
                        </div>
                        <div class="sm:col-span-3">
                            <label class="input input-bordered flex items-center gap-2">
                                Middle Name
                                <input type="text" name="middle_name"
                                    class="grow border-none focus:border-none focus:ring-0"
                                    placeholder="Middle Name" />
                            </label>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label class="input input-bordered flex items-center gap-2">
                                Last Name
                                <input type="text" name="last_name"
                                    class="grow border-none focus:border-none focus:ring-0" placeholder="Last Name" />
                            </label>
                        </div>
                        <div class="sm:col-span-3">
                            <label class="input input-bordered flex items-center gap-2">
                                Title
                                <input type="text" name="title"
                                    class="grow border-none focus:border-none focus:ring-0"
                                    placeholder="Enter Title (e.g., Father, Rev.)" />
                            </label>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label class="input input-bordered flex items-center gap-2">
                                Date of Birth
                                <input type="date" name="date_of_birth"
                                    class="grow border-none focus:border-none focus:ring-0"
                                    placeholder="Date of Birth" />
                            </label>
                        </div>
                        <div class="sm:col-span-3">
                            <label class="input input-bordered flex items-center gap-2">
                                Phone Number
                                <input type="tel" name="phone_number"
                                    class="grow border-none focus:border-none focus:ring-0"
                                    placeholder="Enter Phone Number" />
                            </label>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label class="input input-bordered flex items-center gap-2">
                                Email Address
                                <input type="email" name="email_address"
                                    class="grow border-none focus:border-none focus:ring-0"
                                    placeholder="Enter Email Address" />
                            </label>
                        </div>
                        <div class="sm:col-span-3">
                            <label class="input input-bordered flex items-center gap-2">
                                Ordination Date
                                <input type="date" name="ordination_date"
                                    class="grow border-none focus:border-none focus:ring-0"
                                    placeholder="Enter Ordination Date" />
                            </label>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-gray-700 font-medium">Image</label>
                        <input type="file" name="image" accept="image/*"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 p-3 transition duration-150 ease-in-out">
                    </div>
                    <hr class="my-4">
                    <div class="flex justify-end">
                        <button class="btn btn-primary text-white me-2" type="submit">Save</button>
                        <button class="btn text-black hover:bg-red-700 hover:text-white" type="button"
                            onclick="addModal.close()">Close</button>
                    </div>
                </form>
            </div>
        </dialog>
    </div>
</x-app-layout>
