<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />

        </x-slot>

{{--        <x-jet-validation-errors class="mb-4" />--}}

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        @if(Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show">
                 {{ Session::get('success') }}
                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                </button>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" class="block mt-1 w-full form-control" type="email" name="email" :value="old('email')" autofocus />
                @error('email')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input id="password" class="block mt-1 w-full form-control" type="password" name="password" autocomplete="current-password" />
                @error('password')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

           <div class="block mt-4 dflex">
               <label for="remember_me" class="flex items-center">
                   <x-jet-checkbox id="remember_me" name="remember" />
                   <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
               </label>
               <div class="text-right">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 mleft" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                    </a>
                @endif
               </div>
            </div>



            <div class="flex items-center justify-end mt-4">
                <x-jet-button class="btn-form-submit my-4 text-center">
                    {{ __('Log in') }}
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>


<style>
    .alert-dismissible {
    padding-right: 1rem !important;
}

@media only screen and (min-width: 467px){

    .dflex
    {
        display: flex;
    }
    .mleft
    {
        margin-left: 118px;
    }
}
</style>
