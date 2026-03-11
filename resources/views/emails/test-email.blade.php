<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Saldo</title>
</head>
<body style="margin:0;background:#f3f4f6;font-family:Arial,Helvetica,sans-serif;color:#111827;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f3f4f6;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:520px;background:#ffffff;border-radius:18px;overflow:hidden;box-shadow:0 10px 25px rgba(17,24,39,0.08);">
                    <tr>
                        <td style="padding:22px 24px;background:linear-gradient(135deg,#2563eb,#4f46e5);color:#ffffff;">
                            <div style="font-size:20px;font-weight:700;letter-spacing:0.2px;">Saldo</div>
                            <div style="margin-top:6px;font-size:13px;opacity:0.9;">{{ $title }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:22px 24px;">
                            <div style="font-size:14px;line-height:20px;color:#374151;">
                                {{ $messageText }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:18px 24px;background:#fafafa;border-top:1px solid #f1f5f9;">
                            <div style="font-size:11px;color:#9ca3af;line-height:16px;">
                                © {{ date('Y') }} Saldo. Todos los derechos reservados.
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

