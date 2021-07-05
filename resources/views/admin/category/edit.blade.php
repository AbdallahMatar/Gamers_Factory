@extends('admin.parent')

@section('title', 'Services Create')

@section('style')
    <style>
        .error {
            color: #e63333;
        }

    </style>
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
        <form action="{{ route('categories.update',$category->id) }}" method="POST" id="create_service_form" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div>
                <label>Name</label>
                <input type="text" name="name" value="{{ $category->name }}" class="input w-full border mt-2"
                    placeholder="Name">
            </div>
            <div class="text-right mt-3">
                <div class="w-full sm:w-auto flex items-center sm:ml-auto mt-3 sm:mt-0">
                    <div class="mr-3">Status</div>
                    <input name="status" data-target="#basic-textual-toast" class="show-code input input--switch border"
                        type="checkbox" @if ($category->status === 'Active') checked @endif>
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
        $("#create_service_form").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 3,
                    maxlength: 15,
                },
                description: {
                    required: true,
                    minlength: 3,
                },
                image: {
                    required: true,
                }
            },
            messages: {
                name: {
                    required: 'Please, Enter Name',
                    minlength: 'Name must be at lest at 3 character',
                    maxlength: 'Nmae must be at less than 15 character'
                },
                description: {
                    required: 'Please, Enter Description',
                    minlength: 'Description must be at lest at 3 character',
                },
                image: {
                    required: 'Please, Enter Image',
                }
            }
        });

    </script>
@endsection
