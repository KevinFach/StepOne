<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación OTP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-blue-700">

    <div class="min-h-screen flex flex-col items-center justify-center p-5">

        <div class="w-full max-w-sm bg-white rounded-2xl shadow-xl p-10">

            <div class="text-center mb- 8">
                <h2 class="text-2xl font-bold text-gray-800">Recupera tu cuenta</h2>
                <p class="text-gray-600 mt-2">Ingresa el código enviado a tu correo.</p>
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
                <div class="flex justify-center gap-3 mb-6">
                    <input
                        class="w-14 h-14 text-center text-2xl border border-gray-300 rounded-md shadow-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-600"
                        type="text" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="one-time-code"
                        name="code1" required>
                    <input
                        class="w-14 h-14 text-center text-2xl border border-gray-300 rounded-md shadow-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-600"
                        type="text" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="one-time-code"
                        name="code2" required>
                    <input
                        class="w-14 h-14 text-center text-2xl border border-gray-300 rounded-md shadow-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-600"
                        type="text" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="one-time-code"
                        name="code3" required>
                    <input
                        class="w-14 h-14 text-center text-2xl border border-gray-300 rounded-md shadow-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-600"
                        type="text" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="one-time-code"
                        name="code4" required>
                </div>

                <div class="flex flex-col items-center">
                    <button
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg focus:outline-none focus:shadow-outline"
                        type="submit">
                        Verificar
                    </button>
                    <a class="inline-block align-baseline font-semibold text-sm text-blue-600 hover:text-blue-800 mt-4"
                        href="{{ route('login.password_forgot') }}">
                        ¿No recibiste el código? Reenviar
                    </a>
                </div>
            </form>
        </div>

    </div>

</body>

</html>