<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>código de verificación</title>
</head>

<body
    style="margin: 0; padding: 0; background-color: #1d4ed8; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; min-height: 100vh;">

    <!-- Contenedor principal -->
    <table role="presentation"
        style="width: 100%; border-collapse: collapse; background-color: #1d4ed8; padding: 40px 20px;">
        <tr>
            <td align="center">

                <!-- Cuadro principal -->
                <table role="presentation"
                    style="max-width: 600px; width: 100%; border-collapse: collapse; background-color: rgba(255, 255, 255, 0.95); border-radius: 16px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); border: 1px solid #e5e7eb;">

                    <!-- Header -->
                    <tr>
                        <td
                            style="background-color: #2563eb; padding: 32px; text-align: center; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                            <h1
                                style="margin: 0; font-size: 28px; font-weight: 600; color: #ffffff; letter-spacing: 0.025em; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);">
                                Verificación requerida
                            </h1>
                        </td>
                    </tr>

                    <!-- Contenido -->
                    <tr>
                        <td style="padding: 40px; text-align: center;">

                            <p style="color: #374151; margin: 0 0 16px 0; font-size: 18px;">Hello,</p>

                            <p style="color: #374151; margin: 0 0 32px 0; font-size: 16px; line-height: 1.5;">
                                Usa el código de abajo para continuar con el proceso :
                            </p>

                            <!-- Código OTP -->
                            <table role="presentation"
                                style="width: 100%; border-collapse: collapse; margin: 0 0 32px 0;">
                                <tr>
                                    <td
                                        style="background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 20px 24px; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
                                        <p
                                            style="margin: 0; font-size: 36px; font-weight: 800; color: #1d4ed8; letter-spacing: 0.1em;">
                                            {{ $otp_code }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="color: #374151; margin: 0 0 20px 0; font-size: 16px; line-height: 1.5;">
                                Este código es valido por: <span style="font-weight: 600;">2 minutos</span>.
                            </p>

                            <p style="color: #374151; margin: 0 0 24px 0; font-size: 16px; line-height: 1.5;">
                                En caso de no requerir este código, puedes ignorar este correo.
                            </p>

                            <p style="color: #374151; margin: 0; font-size: 16px; line-height: 1.5;">
                                Gracias por mantener segura tu cuenta!
                            </p>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td
                            style="background-color: #f3f4f6; padding: 20px; text-align: center; font-size: 14px; color: #6b7280; border-top: 1px solid #e5e7eb;">
                            © 2025 SecureAuth System — All rights reserved.
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>