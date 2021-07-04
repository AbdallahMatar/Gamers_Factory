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
        <form action="{{ route('employees.store') }}" method="POST" id="create_service_form" enctype="multipart/form-data">
            @csrf
            <div id="select" class="inline-block">
                <label>City</label>
                <div class="mt-2">
                    <select data-hide-search="true" class="select2 w-full" name="city_id" id="city_id"
                            onchange="getCityStates()">
                        <option disabled selected>--Select--</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div id="select" class="inline-block ml-12">
                <label>State</label>
                <div class="mt-2">
                    <select name="state_id" id="state_id" data-hide-search="true" class="select2 w-full">
                        <option disabled selected>--Select--</option>
                    </select>
                </div>
            </div>
            <div id="select" class="inline-block mt-3">
                <label>First Name</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}" class="input w-full border mt-2"
                       placeholder="First Name">
            </div>
            <div id="select" class="inline-block ml-12">
                <div id="select" class="inline-block mt-3">
                    <label>Last Name</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" class="input w-full border mt-2"
                           placeholder="Last Name">
                </div>
            </div>
            <div id="select" class="inline-block mt-3">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="input w-full border mt-2"
                       placeholder="Email">
            </div>
            <div id="select" class="inline-block ml-12">
                <div id="select" class="inline-block mt-3">
                    <label>Mobile</label>
                    <input type="number" name="mobile" value="{{ old('mobile') }}" class="input w-full border mt-2"
                           placeholder="Mobile">
                </div>
            </div>
            <div id="select" class="inline-block mt-3">
                <label>Password</label>
                <input type="password" name="password" value="{{ old('password') }}" class="input w-full border mt-2"
                       placeholder="Password">
            </div>
            <div id="select" class="inline-block ml-12">
                <div id="select" class="inline-block mt-3">
                    <div class="mt-3"><label>Gender</label>
                        <div class="flex flex-col sm:flex-row mt-2">
                            <div class="flex items-center text-gray-700 mr-2">
                                <input type="radio" class="input border mr-2" name="gender" id="gender_male"
                                       value="Male">
                                <label class="cursor-pointer select-none"
                                       for="horizontal-radio-chris-evans">Male</label>
                            </div>
                            <div class="flex items-center text-gray-700 mr-2 mt-2 sm:mt-0">
                                <input type="radio" class="input border mr-2" name="gender" id="gender_female"
                                       value="Female">
                                <label class="cursor-pointer select-none"
                                       for="horizontal-radio-liam-neeson">Female</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="select" class="inline-block mt-3">
                <label>Image</label>
                <div class="col-md-10">
                    <input class="form-control" type="file" name="image" accept="image/*">
                </div>
            </div>
            <div id="select" class="inline-block ml-12">
                <div id="select" class="inline-block mt-3">
                    <div class="w-full sm:w-auto flex items-center sm:ml-auto mt-3 sm:mt-0">
                        <div class="mr-3">Status</div>
                        <input name="status" data-target="#basic-textual-toast"
                               class="show-code input input--switch border" type="checkbox" checked>
                    </div>
                </div>
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
        function getCityStates() {
            var selectedCityId = document.getElementById('city_id').value;
            var stateSelect = document.getElementById('state_id');

            stateSelect.length = 0;

            @foreach($cities as $city)
            if (selectedCityId == '{{ $city->id }}') {
                @foreach($city->states as $state)
                var option = document.createElement('option');
                option.text = '{{ $state->name }}';
                option.value = '{{ $state->id }}';
                stateSelect.add(option);
                @endforeach
            }
            @endforeach
            // var option = document.createElement('option');
            // option.text = "State ABC";
            // stateSelect.add(option);
        }

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
