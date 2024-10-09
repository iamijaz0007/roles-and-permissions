<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Users / Create
            </h2>
            <a href="{{ route('users.index') }}" class="bg-slate-700 text-sm rounded-md px-5 py-3 text-white">Back</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('users.store') }}" method="post">
                        @csrf
                        <div>
                            <label class="text-lg font-medium">Name</label>
                            <div class="my-3">
                                <input value="{{ old('name') }}" placeholder="Enter Role Name" name="name" type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                                @error('name')
                                <p class="text-red-400 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <label class="text-lg font-medium">Email</label>
                            <div class="my-3">
                                <input value="{{ old('email') }}" placeholder="Email" name="email" type="email" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                                @error('email')
                                <p class="text-red-400 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <label class="text-lg font-medium">Password</label>
                            <div class="my-3">
                                <input value="{{ old('password') }}" placeholder="password" name="password" type="password" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                                @error('password')
                                <p class="text-red-400 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <label class="text-lg font-medium">Confirm Password</label>
                            <div class="my-3">
                                <input value="{{ old('confirm_password') }}" placeholder="confirm_password" name="confirm_password" type="password" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                                @error('confirm_password')
                                <p class="text-red-400 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-4 mb-3">
                                @if($roles->isNotEmpty())
                                    @foreach($roles as $role)
                                        <div class="mt-3">
                                            <input type="checkbox" id="roles{{ $role->id }}" class="rounded" name="role[]" value="{{ $role->name }}">
                                            <label for="permission{{ $role->id }}">{{ $role->name }}</label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button class="bg-slate-700 text-sm rounded-md px-5 py-3 text-white">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
