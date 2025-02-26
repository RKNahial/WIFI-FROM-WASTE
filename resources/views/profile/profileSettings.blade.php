@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-leaf">
    <!-- Navigation Bar is inherited from app layout -->
    
    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="card-overlay rounded-lg shadow-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">Profile Settings</h2>
                        <a href="{{ route('WifiFromWaste') }}" class="text-emerald-600 hover:text-emerald-700">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                        </a>
                    </div>

                    @if (session('status'))
                        <div class="mb-4 p-4 rounded-md bg-emerald-50 border border-emerald-200">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-emerald-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-emerald-800">
                                        {{ session('status') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 p-4 rounded-md bg-red-50 border border-red-200">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-red-400"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">
                                        There were {{ $errors->count() }} errors with your submission
                                    </h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc pl-5 space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Profile Update Form -->
                    <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                        @csrf
                        @method('patch')

                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 dark:text-dark-300">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', Auth::user()->name) }}" placeholder="Enter your name"
                                   class="py-2 px-4 block w-full rounded-md border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-dark-300">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', Auth::user()->email) }}" placeholder="Enter your email address"
                                   class="py-2 px-4 block w-full rounded-md border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                        </div>

                        <div>
                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                Update Profile
                            </button>
                        </div>
                    </form>

                    <div class="mt-10 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <h3 class="text-lg font-medium text-red-600 dark:text-red-500">Delete Account</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-dark-400">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
                        
                        <!-- Delete Account Form -->
                        <form method="POST" action="{{ route('profile.destroy') }}" class="mt-4">
                            @csrf
                            @method('delete')

                            <div class="mt-4">
                                <label for="password" class="block text-sm font-medium text-slate-700 dark:text-dark-300">Password</label>
                                <input type="password" name="password" id="password" required
                                       class="py-2 px-4 block w-full rounded-md border-slate-300 shadow-sm focus:border-red-500 focus:ring-red-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                            </div>

                            <button type="submit" class="mt-4 w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Delete Account
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection