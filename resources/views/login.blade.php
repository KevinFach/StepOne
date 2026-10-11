@extends('layouts.body')
@section('title', 'Sign In')

@section('content')

<body>
    <div x-data="loginPage()" class="min-h-screen bg-blue-700 flex">

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">

                <div class="text-center mb-8">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 text-blue-600 rounded-full mb-4">
                        <i class="fas fa-sign-in-alt fa-lg"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">Login</h2>
                    <p class="text-gray-600 mt-2">Llena los campos para iniciar sesión </p>
                    @foreach(session('messages', []) as $m)
                    <div class="p-3 mb-2 rounded text-sm text-center
        @if($m['tags'] == 'success') bg-green-100 text-green-700
        @elseif($m['tags'] == 'error') bg-red-100 text-red-700
        @elseif($m['tags'] == 'warning') bg-yellow-100 text-yellow-700
        @elseif($m['tags'] == 'info') bg-blue-100 text-blue-700
        @endif">{{ $m['text'] }}
                    </div>
                    @endforeach

                </div>

                <form method="post" action="">
                    @csrf
                    <!-- Email -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2">Email</label>
                        <div class="relative">
                            <input type="email" x-model="email" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-600"
                                placeholder="you@example.com" name="email">
                            <i class="fas fa-envelope absolute right-3 top-4 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2">Password</label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" x-model="password" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-600"
                                placeholder="••••••••" name="password">
                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                                <i :class="showPassword ? 'fa fa-eye-slash' : 'fa fa-eye'"></i>
                            </button>
                        </div>
                    </div>


                    <!-- Botón -->
                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700">
                        Login
                    </button>

                    <p class="mt-6 text-center text-gray-600">
                        <a href="{{ route('login.password_forgot') }}" class="text-blue-600 font-semibold">Se me olvido la contraseña</a>
                    </p>
                    <p class="mt-6 text-center text-gray-600">
                        ¿No tienes una cuenta?
                        <a href="{{ route('signin') }}" class="text-blue-600 font-semibold">Sign up</a>
                    </p>


                </form>
            </div>
        </div>

        <!--* Right Image agregar una imagen nueva -->
        <div class="hidden lg:block lg:w-1/2 bg-cover bg-center"
            style="background-image: url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&q=80')">
            <div class="h-full bg-black bg-opacity-50 flex items-center justify-center">
                <div class="text-center text-white px-12">
                    <h2 class="text-4xl font-bold mb-6">Bienvenido</h2>
                    <p class="text-xl">Inicia sesión e ingresa a tu cuenta</p>
                </div>
            </div>
        </div>

    </div>


    <script>
        function loginPage() {
            return {
                email: "",
                password: "",
                showPassword: false,
                handleSubmit() {
                    alert("Login successful!");
                }
            };
        }
    </script>
</body>

@endsection