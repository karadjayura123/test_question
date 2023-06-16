<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
        <div>
            <form action="{{ route('profile.generateToken') }}" method="POST">
                @csrf
                <button style="text-align: center; color: white;font-size: 30px;" type="submit">Сгенерировать токен</button>
            </form>
        </div>
        @if(session('jwt_token'))

            <div class="token-field">
                <label>JWT Token:</label>
                <textarea readonly>{{ $token }}</textarea>
            </div>
        @endif
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
    <style>
        .token-field {
            margin-top: 20px;
        }

        .token-field label {
            display: block;
            font-size: 18px;
            font-weight: bold;
            color: #ffffff;
            margin-bottom: 5px;
        }

        .token-field textarea {
            width: 100%;
            height: 150px;
            padding: 10px;
            font-size: 16px;
            color: #ffffff;
            background-color: #333333;
            border: 1px solid #ffffff;
            border-radius: 5px;
        }

        .token-field button {
            color: #ffffff;
        }
    </style>
</x-app-layout>
