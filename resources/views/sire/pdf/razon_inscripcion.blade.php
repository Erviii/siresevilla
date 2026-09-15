<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Razón de Inscripción N° {{ $movimiento->movinumins }}</title>
    <style>
        @page {
            size: A4;
           margin: 10mm 15mm 20mm 15mm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #1e293b;
        }
        
        /* Encabezado */
        .header {
            width: 100%;
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .header table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-title {
            font-size: 13pt;
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header-subtitle {
            font-size: 8.5pt;
            color: #64748b;
            text-transform: uppercase;
        }
        .doc-type {
            text-align: right;
            font-size: 10.5pt;
            font-weight: bold;
            color: #0f172a;
        }

        .title-banner {
            background-color: #f1f5f9;
            border-left: 4px solid #1e3a8a;
            padding: 8px 12px;
            margin-bottom: 15px;
        }
        .title-banner h1 {
            font-size: 11pt;
            margin: 0;
            color: #0f172a;
            text-transform: uppercase;
        }
        .title-banner p {
            margin: 2px 0 0 0;
            font-size: 8pt;
            color: #64748b;
        }

        /* Secciones y Tablas */
        .section-title {
            font-size: 9pt;
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 3px;
            margin-top: 14px;
            margin-bottom: 8px;
        }

        .grid-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .grid-table td {
            padding: 5px 8px;
            font-size: 8.5pt;
            vertical-align: top;
            border: 1px solid #e2e8f0;
        }
        .grid-table .label {
            font-weight: bold;
            color: #334155;
            width: 22%;
            background-color: #f8fafc;
        }
        .grid-table .value {
            color: #0f172a;
            width: 28%;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            margin-bottom: 10px;
        }
        .data-table th {
            background-color: #1e3a8a;
            color: #ffffff;
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            padding: 5px 8px;
            text-align: left;
            border: 1px solid #1e3a8a;
        }
        .data-table td {
            padding: 5px 8px;
            font-size: 8.5pt;
            border: 1px solid #e2e8f0;
        }

        /* Bloque de Certificación */
        .legal-text {
            text-align: justify;
            font-size: 9pt;
            line-height: 1.5;
            margin-top: 12px;
            margin-bottom: 12px;
            background-color: #fafafa;
            padding: 10px;
            border: 1px dashed #cbd5e1;
            border-radius: 4px;
        }

        /* Firmas y Pie */
        .signature-block {
            margin-top: 40px;
            page-break-inside: avoid;
            text-align: center;
        }
        .signature-line {
            width: 240px;
            margin: 0 auto;
            border-top: 1px solid #0f172a;
            padding-top: 4px;
        }
        .signer-name {
            font-weight: bold;
            font-size: 9.5pt;
            color: #0f172a;
            text-transform: uppercase;
        }
        .signer-title {
            font-size: 8pt;
            color: #64748b;
            text-transform: uppercase;
        }

        .footer-note {
            margin-top: 25px;
            font-size: 7.5pt;
            color: #94a3b8;
            text-align: center;
            border-top: 1px solid #f1f5f9;
            padding-top: 6px;
        }
    </style>
</head>
<body>

    <div class="header">
    <table>
        <tr>
            <td style="width: 20%; vertical-align: middle;">
                <img src="{{ public_path('images/logosevilla.png') }}" style="width: 85px; height: auto; display: block;">
            </td>
            <td style="width: 50%; vertical-align: middle;">
                <div class="header-title"><strong>{{ $configuracion->nombre_registro ?? 'REGISTRO DE LA PROPIEDAD Y MERCANTIL' }}</strong></div>
                <div class="header-subtitle">{{ $configuracion->direccion ?? 'Sevilla don Bosco, Ecuador' }}</div>
            </td>
            <td class="doc-type" style="width: 30%; vertical-align: middle; text-align: right;">
                @if(!empty($qrBase64))
                    <img src="data:image/png;base64,{{ $qrBase64 }}" style="width: 100px; height: 100px; display: inline-block;">
                @endif
                
            </td>
        </tr>
    </table>
</div>

    <div class="title-banner">
        <h1>RAZÓN DE INSCRIPCIÓN</h1>
        <p>Documento oficial generado automáticamente por el Sistema SIRE</p>
    </div>

    <div class="section-title">1. DATOS DE LA INSCRIPCIÓN</div>
    <table class="grid-table">
        <tr>
            <td class="label">Nro. Repertorio:</td>
            <td class="value">{{ $movimiento->movinumrep }}</td>
            <td class="label">Nro. Tomo</td>
            <td class="value">{{ $movimiento->movinumtom }}</td>
            
        </tr>
        <tr>
            <td class="label">Nro. Inscripción:</td>
            <td class="value">{{ $movimiento->movinumins }}</td>
            <td class="label">Fecha Inscripción:</td>
            <td class="value">{{ date('d/m/Y H:i', strtotime($movimiento->movifecins)) }}</td>
            
        </tr>
        <tr>
            <td class="label">Ficha N°:</td>
            <td class="value">SDB-{{ $fichaRef->reffnumfic ?? 'S/N' }}</td>
            <td class="label">Canton / Juzgado:</td>
            <td class="value">{{ $movimiento->cantnombre ?? 'N/A' }} / {{ $movimiento->junonombre ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Libro Registral:</td>
            <td class="value">{{ $movimiento->librnombre }}</td>
             <td class="label"></td>
            <td class="value"></td>
        </tr>
    </table>

    <div class="section-title">2. DETALLE DEL ACTO REGISTRAL</div>
    <table class="grid-table">
        <tr>
            <td class="label">Acto Registral:</td>
            <td class="value" colspan="3"><strong>{{ $movimiento->actonombre }}</strong></td>
        </tr>
    </table>

    <div class="section-title">3. INTERVINIENTES EN EL MOVIMIENTO</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25%;">Identificación</th>
                <th style="width: 50%;">Nombres y Apellidos / Razón Social</th>
                <th style="width: 25%;">Calidad / Rol</th>
            </tr>
        </thead>
        <tbody>
            @forelse($intervinientes as $cli)
                <tr>
                    <td>{{ $cli->cliecedruc }}</td>
                    <td>{{ $cli->clienombre }}</td>
                    <td>{{ $cli->papenombre ?? 'INTERVINIENTE' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center; color: #94a3b8;">No se registraron intervinientes.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">4. CERTIFICACIÓN REGISTRAL Y OBSERVACIONES</div>
    <div class="legal-text">
      
        <strong>Observaciones:</strong> 
        
<div class="linderos" style="text-align: justify; line-height: 1.5; font-size: 9.5pt;">
    @php
        $textoLinderos = $fichaRef->fichlinreg ?? 'No registra linderos.';
        
        if ($textoLinderos !== 'No registra linderos.') {
            // 1. Escapamos por seguridad
            $textoLinderos = e($textoLinderos);
            
            // 2. Eliminamos todo desde NORTE, SUR, ESTE u OESTE hasta llegar a AREA TOTAL.
            // La letra 's' al final de la expresión permite que el punto (.) incluya saltos de línea.
            $textoLinderos = preg_replace('/(?:NORTE|SUR|ESTE|OESTE).*?(?=(?:Á|A)REA\s+TOTAL|$)/is', '', $textoLinderos);
            
            // 3. (Opcional pero recomendado) Le damos un salto de línea y negrita solo a AREA TOTAL para que se vea bien
            $textoLinderos = preg_replace('/((?:Á|A)REA\s+TOTAL\s*[:\.\-]*)/i', '<br><b>$1</b> ', $textoLinderos);
        }
    @endphp
    
    {!! $textoLinderos !!}
</div>
        
        
    </div>

    <div class="signature-block">
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            {{-- Columna para el Código QR --}}
            <td style="width: 35%; text-align: center; vertical-align: middle;">
                @if(!empty($qrBase64))
                    <img src="data:image/png;base64,{{ $qrBase64 }}" style="width: 100px; height: 100px; display: inline-block;">
                    <div style="font-size: 7.5pt; color: #64748b; margin-top: 2px;">Validación SIRE</div>
                @endif
            </td>
            
            {{-- Columna para la Firma Institucional --}}
            <td style="width: 65%; text-align: center; vertical-align: middle;">
                <div style="height: 35px;"></div>
                <div class="signature-line"></div>
                <div class="signer-name">{{ $registradorActual?->titulo_profesional ?? 'Registrador de la Propiedad' }}. {{ $registradorActual?->nombre_completo ?? 'REGISTRADOR NO ASIGNADO' }}</div>
                <div class="signer-title">Registrador(a) de la Propiedad y Mercantil (E) </div>
                <div class="signer-title">Canton Sevilla Don Bosco</div>
            </td>
        </tr>
    </table>
</div>

    <div class="footer-note">
        Documento generado por el operador: {{ $movimiento->nom_usuario ?? 'SISTEMA' }}, Verifique la validez en la plataforma SIRE.
    </div>
{{-- PIE DE PÁGINA DINÁMICO CON NUMERACIÓN (Página X de Y) --}}
    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->get_font("Helvetica", "normal");
            $size = 8;
            // Posición X e Y en la página A4 (puntos)
            $x = 260; 
            $y = 810; 
            $text = "Página {PAGE_NUM} de {PAGE_COUNT}";
            $color = array(0.39, 0.45, 0.55); // Color gris slate (#64748b)
            
            $pdf->page_text($x, $y, $text, $font, $size, $color);
        }
    </script>
</body>
</html>