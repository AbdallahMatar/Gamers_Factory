@extends('admin.parent')

@section('title', 'Services Create')

@section('style')
    <style>
        .error {
            color: #e63333;
        }

    </style>
    <link rel="stylesheet" href="{{ asset('cms/dist/css/form.css') }}"/>
@endsection

@section('content')
    <div class="container mt-5 items-center p-5 box border-b border-gray-200">
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="rounded-md flex items-center px-5 py-4 mb-2 bg-theme-6 text-white">
                    <i data-feather="alert-octagon" class="w-6 h-6 mr-2"></i>
                    {{ $error }}
                </div>
            @endforeach
        @endif
        <form action="{{ route('projects.update',$project->id) }}" method="POST" id="create_service_form" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div id="select" class="inline-block">
                <label>Employees</label>
                <div class="mt-2">
                    @php ($employee_id = [])
                    <select data-placeholder="Select categories" class="select2 w-full" name="employee_id[]" id="employee_id" multiple>
                        @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->last_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div id="select" class="inline-block ml-12">
                <div id="select" class="inline-block mt-3">
                    <label>Project Price</label>
                    <input type="number" name="project_price" value="{{ old('project_price') }}" class="input w-full border mt-2"
                           placeholder="Project Price">
                </div>
            </div>
            <div id="select" class="inline-block mt-3">
                <label>Project Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="input w-full border mt-2"
                       placeholder="Project Name">
            </div>
            <div id="select" class="inline-block ml-12">
                <div id="select" class="inline-block mt-3">
                    <label>Client Name</label>
                    <input type="text" name="client_name" value="{{ old('client_name') }}" class="input w-full border mt-2"
                           placeholder="Client Name">
                </div>
            </div>
            <div class="mt-3">
                <label>Description</label>
                <textarea name="description" id="desc" class="input w-full border mt-2" cols="30"
                    rows="6">{{ old('description') }}</textarea>
            </div>
            <div class="text-right mt-3">
                <button class="button w-24 mr-1 mb-2 bg-theme-1 text-white">Submit</button>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.2.3/trix.js"></script>
    <script>
        $("#create_service_form").validate({
            rules: {
                first_name: {
                    required: true,
                    minlength: 3,
                    maxlength: 15,
                },
                last_name: {
                    required: true,
                    minlength: 3,
                    maxlength: 15,
                },
                email: {
                    required: true,
                },
                mobile: {
                    required: true,
                },
                password: {
                    required: true,
                },
                city_id: {
                    required: true,
                },
                state_id: {
                    required: true,
                },
                gender: {
                    required: true,
                },
                image: {
                    required: true,
                }
            },
            messages: {
                first_name: {
                    required: 'Please, Enter First Name',
                    minlength: 'Name must be at lest at 3 character',
                    maxlength: 'Nmae must be at less than 15 character'
                },
                last_name: {
                    required: 'Please, Enter Last Name',
                    minlength: 'Name must be at lest at 3 character',
                    maxlength: 'Nmae must be at less than 15 character'
                },
                email: {
                    required: 'Please, Enter Email',
                },
                mobile: {
                    required: 'Please, Enter Mobile',
                },
                password: {
                    required: 'Please, Enter Password',
                },
                city_id: {
                    required: 'Please, Enter City',
                },
                state_id: {
                    required: 'Please, Enter State',
                },
                gender: {
                    required: 'Please, Select Your Gender',
                },
                image: {
                    required: 'Please, Enter Image',
                }
            }
        });

    </script>
@endsection
