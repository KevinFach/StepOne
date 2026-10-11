<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.6; }
        .header { background-color: #013D7D; padding: 20px; text-align: center; color: white; border-radius: 10px 10px 0 0; }
        .content { padding: 20px; border: 1px solid #ddd; border-top: none; border-radius: 0 0 10px 10px; }
        .details { background-color: #f4f4f4; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .footer { font-size: 12px; color: #888; text-align: center; margin-top: 20px; }
        .btn { display: inline-block; background-color: #407AD0; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>¡Gracias por tu compra, {{ $usuario->first_name }}!</h1>
    </div>
    
    <div class="content">
        <p>Hola <strong>{{ $usuario->first_name }}</strong>,</p>
        <p>Tu pago ha sido procesado exitosamente por Mercado Pago. Aquí tienes los detalles:</p>
        
        <div class="details">
            <h3> Orden #{{ $orden->idOrder }}</h3>
            <p><strong>Total:</strong> ${{ $orden->totalAmount }} MXN</p>
            <p><strong>Fecha:</strong> {{ $orden->createdAt?->format('d/m/Y H:i') }}</p>
            <p><strong>Enviar a:</strong> {{ $orden->address->street }} #{{ $orden->address->number }}, {{ $orden->address->colony }}</p>
        </div>

        <p>Estamos preparando tu pedido. Te avisaremos cuando sea enviado.</p>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ route('login.perfil') }}" class="btn">Ver Mis Pedidos</a>
        </div>
    </div>

    <div class="footer">
        <p>StepOne Sneakers<br>Este es un correo automático, por favor no respondas.</p>
    </div>
</body>
</html>