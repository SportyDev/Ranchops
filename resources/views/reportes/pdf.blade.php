<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte Ranchops</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .header {
        text-align: center;
        border-bottom: 2px solid #5E1B22;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .logo {
        font-size: 24px;
        font-weight: bold;
        color: #5E1B22;
        margin: 0;
    }

    .subtitle {
        font-size: 14px;
        color: #666;
        margin: 5px 0 0 0;
    }

    .info-fecha {
        text-align: right;
        font-size: 10px;
        color: #999;
        margin-bottom: 15px;
    }

    table {
        w-full;
        border-collapse: collapse;
        margin-top: 10px;
        width: 100%;
    }

    th {
        background-color: #f3f4f6;
        color: #333;
        font-weight: bold;
        text-align: left;
        padding: 10px;
        border-bottom: 2px solid #ddd;
    }

    td {
        padding: 8px 10px;
        border-bottom: 1px solid #eee;
    }

    tr:nth-child(even) {
        background-color: #fafafa;
    }

    .footer {
        position: fixed;
        bottom: -20px;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 10px;
        color: #aaa;
        border-top: 1px solid #eee;
        padding-top: 10px;
    }
    </style>
</head>

<body>
    <div class="header">
        <h1 class="logo">Ranchops</h1>
        <p class="subtitle">Reporte Oficial de {{ ucfirst($categoria) }}</p>
    </div>

    <div class="info-fecha">
        Generado el: {{ date('d/m/Y H:i A') }}<br>
        Por: {{ auth()->user()->name ?? 'Administrador' }}
    </div>

    <table>
        <thead>
            <tr>
                @foreach($columnas as $columna)
                <th>{{ $columna }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($filas as $fila)
            <tr>
                @foreach($fila as $celda)
                <td>{{ $celda }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Software de Gestión Ganadera Ranchops - Documento generado automáticamente.
    </div>
</body>

</html>