<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-image: url('https://images.unsplash.com/photo-1525186402429-b4ff38bedbec?q=80&w=1920');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .accordion-content.active {
            max-height: 500px;
        }
    </style>
</head>

<body class="bg-blue-700 min-h-screen flex items-center justify-center p-6">

    <main class="w-full max-w-md">
        <div class="bg-white/95 backdrop-blur-lg rounded-2xl shadow-2xl border border-indigo-300 overflow-hidden">

            <!-- Header -->
            <div class="bg-blue-600 py-6 px-6 text-center shadow-md">
                <h1 class="text-2xl font-semibold text-white tracking-wide drop-shadow">
                    ¿Olvidaste tu contraseña?
                </h1>
            </div>

            <div class="p-6 sm:p-7 bg-white">
                @foreach(session('messages', []) as $m)
                <div class="p-3 mb-2 rounded text-sm text-center
        @if($m['tags'] == 'success') bg-green-100 text-green-700
        @elseif($m['tags'] == 'error') bg-red-100 text-red-700
        @elseif($m['tags'] == 'warning') bg-yellow-100 text-yellow-700
        @elseif($m['tags'] == 'info') bg-blue-100 text-blue-700
        @endif">{{ $m['text'] }}
                </div>
                @endforeach

                <div class="mt-6">
                    <form method="post" action="">
                        @csrf
                        <div class="grid gap-y-4">

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-bold ml-1 mb-2">
                                    Tu correo electrónico
                                </label>
                                <input type="email" id="email" name="email"
                                    class="py-3 px-4 block w-full border-2 border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                                    required>
                            </div>

                            <!-- Password -->
                            <div class="mb-5">
                                <label class="block text-sm font-medium mb-2">Contraseña</label>
                                <div class="relative">
                                    <input type="password" id="pwd" name="pwd" required
                                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=]).{8,}"
                                        class="w-full px-4 py-3 pr-10 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-600"
                                        placeholder="••••••••">
                                    <i class="fas fa-eye absolute right-3 top-4 text-gray-400 cursor-pointer"
                                        id="togglePwd" onclick="togglePassword('pwd', 'togglePwd')"></i>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-5">
                                <label class="block text-sm font-medium mb-2">Confirmar contraseña</label>
                                <div class="relative">
                                    <input type="password" id="confirm_pwd" name="confirm_pwd" required
                                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=]).{8,}"
                                        class="w-full px-4 py-3 pr-10 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-600"
                                        placeholder="••••••••">
                                    <i class="fas fa-eye absolute right-3 top-4 text-gray-400 cursor-pointer"
                                        id="toggleConfirmPwd"
                                        onclick="togglePassword('confirm_pwd', 'toggleConfirmPwd')"></i>
                                </div>
                            </div>

                            <button type="submit"
                                class="py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all text-sm">
                                Restablecer contraseña
                            </button>

                        </div>
                    </form>


                    <div class="mt-6">
                        <!-- Accordion 1 -->
                        <div class="border-b border-gray-200">
                            <button onclick="toggleAccordion('accordion1')"
                                class="w-full py-3 flex justify-between items-center text-left font-semibold text-gray-800 hover:text-blue-600 transition">
                                <span>Requisitos</span>
                                <i class="fas fa-chevron-down transition-transform" id="icon1"></i>
                            </button>
                            <div id="accordion1" class="accordion-content ">

                                <ul>
                                    <li>Al menos una mayúscula (A-Z)</li>
                                    <li>Al menos una minúscula (a-z)</li>
                                    <li>Al menos un número (0-9)</li>
                                    <li>Al menos un carácter especial (@#$%^&+=)</li>
                                    <li>Longitud mínima: 8 caracteres</li>
                                </ul>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <p class="mt-2 text-sm text-gray-600">
                        ¿Recordaste tu contraseña?
                        <a class="text-blue-600 decoration-2 hover:underline font-medium" href="{{ route('login') }}">
                            Login Aquí
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <script>
            // Toggle password visibility
            function togglePassword(inputId, iconId) {
                const input = document.getElementById(inputId);
                const icon = document.getElementById(iconId);

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }

            // Toggle accordion
            function toggleAccordion(id) {
                const content = document.getElementById(id);
                const iconNum = id.replace('accordion', '');
                const icon = document.getElementById('icon' + iconNum);

                // Toggle the accordion
                content.classList.toggle('active');

                // Rotate icon
                if (content.classList.contains('active')) {
                    icon.style.transform = 'rotate(180deg)';
                } else {
                    icon.style.transform = 'rotate(0deg)';
                }
            }
        </script>

    </main>

</body>

</html>