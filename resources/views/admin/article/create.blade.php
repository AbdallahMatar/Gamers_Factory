@extends('admin.parent')

@section('title', 'Services Create')

@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        .error {
            color: #e63333;
        }

        .date_biker {
            background-color: #ffffff;
        }

        .error {
            color: #e63333;
        }

    </style>
    <link rel="stylesheet" href="{{ asset('cms/dist/css/form.css') }}" />
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
        <form action="{{ route('articles.store') }}" method="POST" id="create_service_form" enctype="multipart/form-data">
            @csrf
            <div id="select" class="inline-block mt-3">
                <label>Title</label>
                <input type="text" name="title" value="{{ old('title') }}" class="input w-full border mt-2"
                    placeholder="Your Name">
            </div>
            <div id="select" class="inline-block ml-12">
                <div>
                    <label>Authors</label>
                    <select name="author_id" data-hide-search="true" class="select2 w-full">
                        <option>--Select--</option>
                        @foreach ($authors as $author)
                        <option value="{{ $author->id }}">{{ $author->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div id="select" class="inline-block mt-3">
                <div>
                    <label>Authors</label>
                    <select name="category_id" data-hide-search="true" class="select2 w-full">
                        <option>--Select--</option>
                        @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div id="select" class="inline-block ml-12">
                <div id="select" class="inline-block mt-3">
                    <div class="w-full sm:w-auto flex items-center sm:ml-auto mt-3 sm:mt-0">
                        <div class="mr-3">Status</div>
                        <input name="status" data-target="#basic-textual-toast" class="show-code input input--switch border"
                            type="checkbox" checked>
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
                    <label>Description Arabic</label>
                    <textarea name="description" id="desc" class="input w-full border mt-2" cols="30"
                        rows="6">{{ old('description') }}</textarea>
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

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <script>
        function getCityStates() {
            var selectedCityId = document.getElementById('city_id').value;
            var stateSelect = document.getElementById('state_id');

            stateSelect.length = 0;
        }
        flatpickr('#published_at', {
                    enableTime: true
                });

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