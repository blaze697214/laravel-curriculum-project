@extends('layouts.hod')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Course Management
    </h1>


    <div class="bg-white p-6 rounded-xl shadow w-full h-60 flex justify-center items-center">
        <div class="font-semibold text-gray-500 text-md">
            This will contain Scheme at Glance validation
        </div>
    </div>
    <div class="mt-5 bg-white p-6 rounded-xl shadow w-full h-full">
        <div class="flex justify-between mb-5">
            <a href="{{ route('hod.courses.view') }}">
                <button class="rounded-lg px-4 py-2 bg-gray-200 cursor-pointer text-gray-800 font-semibold flex gap-1 items-center">
                    <svg class="fill-green-200 w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                    </svg>

                    View Courses
                </button>
            </a>
            <a href="{{ route('hod.courses.create') }}">
                <button class="rounded-lg px-4 py-2 bg-gray-200 cursor-pointer text-gray-800 font-semibold flex gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Create Course
                </button>
            </a>
        </div>
        <div class="bg-white p-6 rounded-xl shadow w-full h-52 flex justify-center items-center">
            <div class="font-semibold text-gray-500 text-md">
                Batch Sumittion to CDC after Scheme at Glance validation is correct
            </div>
        </div>
    </div>
@endsection
