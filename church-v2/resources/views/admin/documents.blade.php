<x-app-layout>
    <div class="py-12 bg-white h-full mt-4 rounded-2xl">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-100 p-4 rounded-lg shadow-md cursor-pointer" data-type="Baptismal Certificate">
                    <h3 class="text-lg font-semibold text-black">Baptismal Certificates</h3>
                    <p class="text-2xl font-bold">{{ $baptismal }}</p>
                </div>
                <div class="bg-[#f5f5dc] p-4 rounded-lg shadow-md cursor-pointer" data-type="Marriage Certificate">
                    <h3 class="text-lg font-semibold text-black">Marriage Certificates</h3>
                    <p class="text-2xl font-bold">{{ $marriage }}</p>
                </div>
                <div class="bg-gray-500 p-4 rounded-lg shadow-md cursor-pointer" data-type="Death Certificate">
                    <h3 class="text-lg font-semibold text-black">Death Certificates</h3>
                    <p class="text-2xl font-bold">{{ $death }}</p>
                </div>
                <div class="bg-[#c3b091] p-4 rounded-lg shadow-md cursor-pointer" data-type="Confirmation Certificate">
                    <h3 class="text-lg font-semibold text-black">Confirmation Certificates</h3>
                    <p class="text-2xl font-bold">{{ $confirmation }}</p>
                </div>
            </div>
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
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-lg font-semibold">Documents List</h3>
                        <button class="btn btn-primary" onclick="addModal.showModal()">
                            Add Document asfasdf
                        </button>
                        <!-- history icon for deleted files -->
                        <button>
                        <img src="{{ asset('assets/img/save.svg') }}" class="w-[22px] h-[22px]">
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
                                        Full Name</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        File</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Uploaded By</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>    
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($documents as $document)
                                    <tr class="cursor-pointer" data-document-type="{{ $document->document_type }}"
                                        onclick="viewModal{{ $document->id }}.showModal()">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $document->document_type }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $document->full_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ strlen($document->file) > 10 ? substr($document->file, 0, 10) . '...' : $document->file }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $document->uploaded_by }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button class="btn bg-blue-700 hover:bg-blue-800 text-white"
                                                onclick="event.stopPropagation(); editModal{{ $document->id }}.showModal()">
                                                Edit
                                            </button>
                                            <button class="btn bg-red-700 hover:bg-red-800 text-white"
                                                onclick="event.stopPropagation(); destroyModal{{ $document->id }}.showModal()">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <dialog id="editModal{{ $document->id }}" class="modal">
                                        <div class="modal-box rounded-lg shadow-lg">
                                            <h3 class="text-lg font-bold mb-4">Edit Document</h3>
                                            <hr class="my-4">
                                            <form action="{{ route('documents.update', $document->id) }}"
                                                method="POST" enctype="multipart/form-data" id="editForm">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="uploaded_by"
                                                    value="{{ Auth::user()->name }}">
                                                <div class="mb-4">
                                                    <label class="block text-gray-700 font-medium">Full Name</label>
                                                    <input type="text" name="full_name"
                                                        placeholder="Enter donor name"
                                                        value="{{ $document->full_name }}"
                                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 p-3 transition duration-150 ease-in-out"
                                                        required>
                                                </div>
                                                <div class="mb-4">
                                                    <label class="block text-gray-700 font-medium">File</label>
                                                    <input type="file" name="file" placeholder="Enter note"
                                                        value="{{ $document->file }}"
                                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 p-3 transition duration-150 ease-in-out">
                                                </div>
                                                <hr class="my-4">
                                                <div class="flex justify-end">
                                                    <button class="btn bg-blue-700 hover:bg-blue-800 text-white"
                                                        type="submit">Update</button>
                                                    <button class="btn text-black hover:bg-red-700 hover:text-white"
                                                        type="button"
                                                        onclick="editModal{{ $document->id }}.close()">Close</button>
                                                </div>
                                            </form>
                                        </div>
                                    </dialog>

                                    <!-- Delete Modal -->
                                    <dialog id="destroyModal{{ $document->id }}" class="modal">
                                        <div class="modal-box rounded-lg shadow-lg">
                                            <h3 class="text-lg font-bold mb-4">Delete Document</h3>
                                            <p>Are you sure you want to delete this document?</p>
                                            <form action="{{ route('documents.destroy', $document->id) }}"
                                                method="POST" id="deleteForm{{ $document->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <div class="flex justify-end mt-4">
                                                    <button class="btn bg-red-700 hover:bg-red-800 text-white"
                                                        type="submit">Delete</button>
                                                    <button class="btn text-black hover:bg-red-700 hover:text-white"
                                                        type="button"
                                                        onclick="destroyModal{{ $document->id }}.close()">Close</button>
                                                </div>
                                            </form>
                                        </div>
                                    </dialog>

                                    <!-- View Modal -->
                                    <dialog id="viewModal{{ $document->id }}" class="modal">
                                        <div class="modal-box rounded-lg shadow-lg w-11/12 max-w-5xl">
                                            <h3 class="text-lg font-bold mb-4">View Document</h3>
                                            <p><strong>Full Name:</strong> {{ $document->full_name }}</p>
                                            <p><strong>Uploaded By:</strong> {{ $document->uploaded_by }}</p>
                                            @php
                                                $documentPath = '';
                                                switch ($document->document_type) {
                                                    case 'Baptismal Certificate':
                                                        $documentPath = 'Baptismal Certificate';
                                                        break;
                                                    case 'Marriage Certificate':
                                                        $documentPath = 'Marriage Certificate';
                                                        break;
                                                    case 'Death Certificate':
                                                        $documentPath = 'Death Certificate';
                                                        break;
                                                    case 'Confirmation Certificate':
                                                        $documentPath = 'Confirmation Certificate';
                                                        break;
                                                    default:
                                                        $documentPath = 'Unknown';
                                                        break;
                                                }
                                            @endphp
                                            <img src="{{ asset('assets/documents/' . $documentPath . '/' . $document->file) }}"
                                                class="max-w-full max-h-screen mx-auto">
                                            <hr class="my-4">
                                            <div class="flex justify-end">
                                                <button class="btn text-black hover:bg-red-700 hover:text-white"
                                                    type="button"
                                                    onclick="viewModal{{ $document->id }}.close()">Close</button>
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

        <!-- Add Document Modal -->
        <dialog id="addModal" class="modal">
            <div class="modal-box rounded-lg shadow-lg">
                <h3 class="text-lg font-bold mb-4">Add Document</h3>
                <hr class="my-4">
                <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="uploaded_by" value="{{ Auth::user()->name }}">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">Full Name</label>
                        <input type="text" name="full_name" placeholder="Enter full name"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 p-3 transition duration-150 ease-in-out"
                            required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">File</label>
                        <input type="file" name="file" id="file_input" accept="docx,pdf,jpg,jpeg,png"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 p-3 transition duration-150 ease-in-out"
                            required>
                    </div>
                    <hr class="my-4">
                    <div class="flex justify-end">
                        <button class="btn btn-primary text-white" type="submit">Save</button>
                        <button class="btn text-black hover:bg-red-700 hover:text-white" type="button"
                            onclick="addModal.close()">Close</button>
                    </div>
                </form>
            </div>
        </dialog>

        <style>
            .dataTables_length select {
                width: 60px;
            }

            .dataTables_filter {
                position: relative;
            }

            .dataTables_filter input {
                padding-left: 2rem;
            }

            .dataTables_filter i {
                position: absolute;
                color: #666;
                right: 0.5rem;
                top: 50%;
                transform: translateY(-50%);
                pointer-events: none;
            }
        </style>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                const table = $('table').DataTable({
                    "dom": '<"flex justify-between items-center mb-4"lf>t<"flex justify-between items-center mt-4"ip>',
                    "language": {
                        "search": "",
                        "searchPlaceholder": "Search documents...",
                        "lengthMenu": "Show _MENU_ entries",
                        "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                        "paginate": {
                            "previous": "Previous",
                            "next": "Next"
                        }
                    }
                });

                $('.dataTables_filter').prepend("<i class='bx bx-search'></i>");

                $('.grid div').click(function() {
                    const type = $(this).data('type');
                    $('tbody tr').each(function() {
                        if ($(this).data('document-type') === type) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                });

                $('#addModal').on('close.modal', function() {
                    $('#file_input').val('');
                });
            });
        </script>
</x-app-layout>
