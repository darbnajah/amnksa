<div dir="rtl">
    <x-guest-layout>
        <x-jet-authentication-card>
            <x-slot name="logo">
                <img src="{{ asset('img/logo.png') }}" width="100px">
            </x-slot>

            <x-jet-validation-errors class="mb-4" />

            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div>
                    <x-jet-label for="email" value="الإيميل" />
                    <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="الإيميل" />
                    <br>

                </div>

                <div class="mt-4">
                    <x-jet-label for="password" value="كلمة المرور" />
                    <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" placeholder="كلمة المرور" />
                    <br>
                </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-lg btn-primary"><i class="fa fa-sign-in"></i> تسجيل الدخول</button>
            </div>

            </form>
        </x-jet-authentication-card>
    </x-guest-layout>

</div>
