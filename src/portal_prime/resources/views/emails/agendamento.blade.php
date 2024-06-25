<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação de Agendamento de Entrega</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
        <div style="display: block; margin: 0 auto; margin-bottom: 20px; background-color: #425789; padding: 10px; border-radius: 8px;">
            <img src="https://www.lineaalimentos.com.br/arquivos/logo-linea-desk-small.png" alt="Logo Linea Alimentos" style="display: block; margin: 0 auto;">
        </div>
        <h3 style="color: #333; font-size: 24px; text-align: center; margin-bottom: 20px;">Confirmação de Agendamento de Entrega</h3>
        <p style="color: #666; font-size: 15px; line-height: 1.6; margin-bottom: 15px;">Prezado Cliente,</p>
        <p style="color: #666; font-size: 15px; line-height: 1.6; margin-bottom: 15px;">Seu agendamento foi realizado com sucesso. Seguem os detalhes:</p>
        <ul>
            <li><strong>Data:</strong> {{ $detalhes['data'] }}</li>
            <li><strong>Hora:</strong> {{ $detalhes['hora'] }}</li>
        </ul>
        <h4 style="color: #333; font-size: 20px; margin-bottom: 10px;">Notas Fiscais:</h4>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 14px;">
            <thead>
                <tr>
                    <th style="padding: 8px; border: 1px solid #ddd; text-align: left; background-color: #f2f2f2;">Nota Fiscal</th>
                    <th style="padding: 8px; border: 1px solid #ddd; text-align: left; background-color: #f2f2f2;">Emissão</th>
                    <th style="padding: 8px; border: 1px solid #ddd; text-align: left; background-color: #f2f2f2;">Pedido</th>
                    <th style="padding: 8px; border: 1px solid #ddd; text-align: left; background-color: #f2f2f2;">Transportadora</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detalhes['notas'] as $nota)
                <tr>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $nota['f2_doc'] }}</td>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $nota['c5_emissao'] }}</td>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $nota['c5_num'] }}</td>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $nota['f2_transp'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p style="color: #666; font-size: 15px; line-height: 1.6; margin-bottom: 15px;">Obrigado por usar nosso serviço.</p>
        <div style="margin-top: 20px; text-align: center; font-size: 11px; color: #999;">
            Este é um e-mail automático. Por favor, não responda a este e-mail.<br>
            Departamento de Logística - Linea Alimentos<br>
            Endereço de correspondência (LINEA): Rua VPR 01, Ala A, Quadra 2-B - Módulos 3 e 4 - Distrito Agroindustrial de Anápolis - DAIA, Anápolis/GO - CEP: 75132-020 - 05.207.076/0002-97.
        </div>
    </div>
</body>
</html>
