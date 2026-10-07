@extends('components.layouts.guest')

@section('title', 'Login')

@section('content')

    <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">

        <div class="w-full max-w-md">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-gray-900">
                        SEMA FE
                    </h1>

                    <p class="mt-2 text-sm text-gray-500">
                        Silakan login untuk melanjutkan
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-600 text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login.process') }}" method="POST" class="space-y-5">

                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>

                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            autocomplete="email"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            placeholder="Masukkan email">

                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Password
                        </label>

                        <input type="password" name="password" required autocomplete="current-password"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            placeholder="Masukkan password">

                        @error('password')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-2">

                        <input type="checkbox" name="remember" value="1"
                            class="rounded border-gray-300 text-indigo-600">

                        <label class="text-sm text-gray-600">
                            Ingat saya
                        </label>

                    </div>

                    <button type="submit"
                        class="w-full py-2.5 bg-indigo-600 text-white rounded-lg
                           hover:bg-indigo-700 transition">
                        Login
                    </button>

                </form>

            </div>

        </div>

    </div>

@endsection
