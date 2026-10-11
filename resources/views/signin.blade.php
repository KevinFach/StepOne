@extends('layouts.body')
@section('title', 'Register')

@section('content')

<body>
    <div x-data="registerForm()" class="min-h-screen bg-blue-700 flex">

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                        <i class="fas fa-user-plus text-blue-600 fa-lg"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">Crea tu cuenta</h2>
                    <p class="text-gray-600 mt-2">Llena los campos para crear tu cuenta</p>
                    @if(session('messages'))
                    <div class="mb-4">
                        @foreach(session('messages', []) as $m)
                        <div class="p-3 mb-2 rounded text-sm {{ $m['tags'] }} text-red-600 ">{{ $m['text'] }}</div>
                        @endforeach
                    </div>
                    @endif
                </div>



                <form method="post" action="">
                    @csrf

                    <div class="mb-5">
                        <label class="block text-sm font-medium mb-2">Nombres
                        </label>
                        <div class="relative">
                            <input type="text" name="name" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-600"
                                placeholder="Ingresa tu nombre(s)">
                            <i class="fas fa-user absolute right-3 top-4 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- apellidos -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium mb-2">
                            Apellidos</label>
                        <div class="relative">
                            <input type="text" name="lastName" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-600"
                                placeholder="Ingresa tus apellidos">
                            <i class="fas fa-user absolute right-3 top-4 text-gray-400"></i>
                        </div>
                    </div>

                    <!--Correo -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium mb-2">Correo</label>
                        <div class="relative">
                            <input type="email" name="email" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-600"
                                placeholder="you@example.com">
                            <i class="fas fa-envelope absolute right-3 top-4 text-gray-400"></i>
                        </div>

                    </div>

                    <!--teléfono -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium mb-2">Teléfono</label>
                        <div class="relative">
                            <input type="tel" name="phone" pattern="[0-9]{10}" required maxlength="10"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-600"
                                placeholder="Ingresa 10 dígitos ">
                            <i class="fa-solid fa-phone-flip absolute right-3 top-4 text-gray-400"></i>
                        </div>
                    </div>

                    <!--curp -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium mb-2">CURP</label>
                        <div class="relative">
                            <input type="text" name="curp" pattern="^[A-Z]{4}[0-9]{6}[HM][A-Z]{5}[0-9A-Z][0-9]$"
                                required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-600"
                                maxlength="18" placeholder="Ingresa tu CURP">
                            <i class="fa-solid fa-file-lines absolute right-3 top-4 text-gray-400"></i>
                        </div>
                    </div>

                    <!--rfc -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium mb-2">RFC</label>
                        <div class="relative">
                            <input type="text" name="rfc" pattern="^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-600"
                                placeholder="Ingresa tu RFC ">
                            <i class="fa-solid fa-file absolute right-3 top-4 text-gray-400"></i>
                        </div>
                    </div>

                    <!--Contrasena -->

                    <div class="mb-5">
                        <label class="block text-sm font-medium mb-2">Contraseña</label>
                        <div class="relative">
                            <input type="password" name="pwd" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-600"
                                placeholder="••••••••">
                            <i class="fas fa-lock absolute right-3 top-4 text-gray-400"></i>
                        </div>

                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-medium mb-2">Confirmar contraseña </label>
                        <div class="relative">
                            <input type="password" name="confirm_pwd" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-600"
                                placeholder="••••••••">
                            <i class="fas fa-lock absolute right-3 top-4 text-gray-400"></i>
                        </div>
                        <p style="color:red;">{{ $message ?? '' }}</p>
                    </div>              
                    


                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700"
                        :disabled="loading">
                        <span x-show="!loading">Sign in </span>
                    </button>

                    <p class="mt-6 text-center text-gray-600">
                        ¿Ya tienes una cuenta?
                        <a href="{{ route('login') }}" class="ml-1 text-blue-600 hover:text-blue-700 font-semibold">Log
                            in</a>
                    </p>
                </form>
            </div>
        </div>

        <div class="hidden lg:block lg:w-1/2 bg-cover bg-center"
            style="background-image: url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&q=80')">
            <div class="h-full bg-black bg-opacity-50 flex items-center justify-center p-8">
                <div class="text-center text-white px-12">
                    <h2 class="text-4xl font-bold mb-6">Get Started</h2>
                    <p class="text-xl">Create your account to manage orders, track shipments and more.</p>
                </div>
            </div>
        </div>

    </div>
    @endsection

    @section('scripts')
    <script>
        function registerForm() {
            return {
                loading: false,
                handleSubmit() {
                    this.loading = true;
                    
                }
            }
        }
    </script>
    @endsection
</body>