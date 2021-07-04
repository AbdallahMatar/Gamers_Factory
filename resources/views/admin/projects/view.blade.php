@extends('admin.parent')

@section('title', 'Services Index')

@section('content')
    <div class="intro-y box px-5 pt-5 mt-5">
        <div class="flex flex-col lg:flex-row border-b border-gray-200 pb-5 -mx-5">
            <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
                <div class="ml-5">
                    <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">Project Name</div>
                    <div class="text-gray-600">{{ $project->name }}</div>
                </div>
            </div>
            <div
                class="flex mt-6 lg:mt-0 items-center lg:items-start flex-1 flex-col justify-center px-5 border-l border-r border-gray-200 border-t lg:border-t-0 pt-5 lg:pt-0">
                <div class="ml-5">
                    <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">Client Name</div>
                    <div class="text-gray-600">{{ $project->client_name }}</div>
                </div>
            </div>
            <div
                class="flex mt-6 lg:mt-0 items-center lg:items-start flex-1 flex-col justify-center px-5 border-l border-r border-gray-200 border-t lg:border-t-0 pt-5 lg:pt-0">
                <div class="ml-5">
                    <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">Project Price</div>
                    <div class="text-gray-600">{{ $project->project_price }}</div>
                </div>
            </div>
            <div
                class="flex mt-6 lg:mt-0 items-center lg:items-start flex-1 flex-col justify-center px-5 border-l border-r border-gray-200 border-t lg:border-t-0 pt-5 lg:pt-0">
                <div class="ml-5">
                    <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">Description</div>
                    <div class="text-gray-600">{{ $project->description }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="intro-y box px-5 pt-5 mt-5">
        <div class="flex flex-col lg:flex-row border-b border-gray-200 pb-5 -mx-5">
            <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
                <div class="ml-5">
                    <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">Project Name</div>
                    <div class="text-gray-600">{{ $project->created_at->diffForHumans() }}</div>
                </div>
            </div>
            <div
                class="flex mt-6 lg:mt-0 items-center lg:items-start flex-1 flex-col justify-center px-5 border-l border-r border-gray-200 border-t lg:border-t-0 pt-5 lg:pt-0">
                <div class="ml-5">
                    <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">Client Name</div>
                    <div class="text-gray-600">{{ $project->updated_at->diffForHumans() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg mt-5">Employees</div>

    <div class="container mt-5 items-center p-5 box border-b border-gray-200">
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-no-wrap">IMAGE</th>
                        <th class="whitespace-no-wrap">NAME</th>
                        <th class="whitespace-no-wrap">EMAIL</th>
                        <th class="text-center whitespace-no-wrap">STATUS</th>
                        <th class="whitespace-no-wrap">MOBILE</th>
                        <th class="whitespace-no-wrap">GENDER</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($project->employees as $employee)
                        <tr class="intro-x">
                            <td>
                                <div class="w-10 h-10 image-fit zoom-in">
                                    <img alt="Employee" class="tooltip rounded-full"
                                        src="{{ url('images/employee/' . $employee->image) }}"
                                        title="Uploaded at 17 July 2021">
                                </div>
                            </td>
                            <td>{{ $employee->first_name }}</td>
                            <td>{{ $employee->email }}</td>
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
                            <td>{{ $employee->mobile }}</td>
                            <td>{{ $employee->gender }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
