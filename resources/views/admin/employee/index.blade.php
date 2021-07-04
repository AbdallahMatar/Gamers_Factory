@extends('admin.parent')

@section('title', 'Services Index')

@section('content')

    <!-- BEGIN: Data List -->
    <div class="container mt-5 items-center p-5 box border-b border-gray-200">
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-no-wrap">Image</th>
                        <th class="whitespace-no-wrap">ID</th>
                        <th class="whitespace-no-wrap">NAME</th>
                        <th class="text-center whitespace-no-wrap">STATUS</th>
                        {{-- <th class="whitespace-no-wrap">Employee</th> --}}
                        <th class="whitespace-no-wrap">CREATED AT</th>
                        <th class="whitespace-no-wrap">UPDATED AT</th>
                        <th class="text-center whitespace-no-wrap">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employees as $index => $employee)
                        <tr class="intro-x">
                            <td>
                                <div class="w-10 h-10 image-fit zoom-in">
                                    <img alt="Employee" class="tooltip rounded-full"
                                        src="{{ url('images/employee/' . $employee->image) }}"
                                        title="Uploaded at 17 July 2021">
                                </div>
                            </td>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $employee->first_name }}</td>
                            @if ($employee->status == 'Active')
                                <td class="w-40">
                                    <div class="flex items-center justify-center text-theme-9"> <svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" class="feather feather-check-square w-4 h-4 mr-2">
                                            <polyline points="9 11 12 14 22 4"></polyline>
                                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                        </svg> Active </div>
                                </td>
                            @else
                                <td class="w-40">
                                    <div class="flex items-center justify-center text-theme-6"> <svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" class="feather feather-check-square w-4 h-4 mr-2">
                                            <polyline points="9 11 12 14 22 4"></polyline>
                                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                        </svg> Inactive </div>
                                </td>
                            @endif
                            {{-- <td>{{ $Employee->state->name }}</td> --}}
                            <td>{{ $employee->created_at->diffForHumans() }}</td>
                            <td>{{ $employee->updated_at->diffForHumans() }}</td>
                            <td class="table-report__action w-56">
                                <div class="flex justify-center items-center">
                                    <a class="flex items-center mr-3" href="{{ route('employees.edit', $employee->id) }}">
                                        <i data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit </a>
                                    <a onclick="confirmDelete(this, '{{ $employee->id }}')" href="#"
                                        class="flex items-center text-theme-6" data-toggle="modal"
                                        data-target="#delete-confirmation-modal"> <i data-feather="trash-2"
                                            class="w-4 h-4 mr-1"></i> Delete </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- END: Data List -->

@endsection

@section('script')

    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <script>
        function confirmDelete(app, id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    deleteService(app, id)
                }
            })
        }

        function deleteService(app, id) {
            axios.delete('/cms/admin/employees/' + id)
                .then(function(response) {
                    // handle success
                    app.closest('tr').remove();
                    showMessage(response.data)
                })
                .catch(function(error) {
                    // handle error
                    showMessage(error.response.data)
                })
                .then(function() {
                    // always executed
                });
        }

        function showMessage(data) {
            Swal.fire({
                title: data.title,
                text: data.text,
                icon: data.icon,
                showConfirmButton: false,
                timer: 1500,
            })
        }

    </script>
@endsection
