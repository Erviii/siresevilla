<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        /* Ajustamos el margen superior a 3.5cm para dar espacio al encabezado fijo repetible */
        @page { margin: 3.5cm 1.5cm 2cm 1.5cm; }
        body { font-family: sans-serif; font-size: 11px; line-height: 1.4; }
        
        /* ------------------------------------------------------------------
           ESTILOS DEL ENCABEZADO (REPETIBLE EN CADA PÁGINA)
           ------------------------------------------------------------------ */
        header {
            position: fixed;
            top: -3.0cm; /* Posicionado dentro del margen superior de la página */
            left: 0;
            right: 0;
            height: 2.2cm;
            border-bottom: 2px solid #000;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
            table-layout: fixed;
        }
        .header-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }

        /* ------------------------------------------------------------------
           ESTILOS DEL PIE DE PÁGINA
           ------------------------------------------------------------------ */
        footer {
            position: fixed;
            bottom: -1.5cm;
            left: 0;
            right: 0;
            height: 1cm;
            font-size: 9px;
            color: #555;
            border-top: 1px solid #ccc;
            padding-top: 4px;
        }
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
            margin: 0;
        }
        .footer-table td {
            border: none;
            padding: 0;
        }
        .pagenum:before {
            content: counter(page) " de " counter(pages);
        }
        /* ------------------------------------------------------------------ */

        .certification { margin: 20px 0; text-align: justify; border: 1px solid #ccc; padding: 10px; background: #fafafa; }
        .section-title { background: #eee; padding: 5px; font-weight: bold; border: 1px solid #999; margin: 15px 0 5px 0; text-transform: uppercase; }
        
        /* REGLAS CLAVE PARA EVITAR ESPACIOS EN BLANCO EN DOMPDF */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 5px; 
            table-layout: fixed; 
            page-break-inside: auto !important;
        }
        
        tr { 
            page-break-inside: auto !important; 
            page-break-after: auto !important;
            page-break-before: auto !important;
        }
        
        th, td { border: 1px solid #000; padding: 6px; text-align: left; word-wrap: break-word; page-break-inside: auto !important; }
        th { background: #f2f2f2; }
        
        /* Contenedor principal de Firmas */
        .signatures { 
            margin-top: 50px; 
            width: 100%; 
            text-align: center; 
            page-break-inside: avoid !important; 
        }
        
        /* Bloque de la firma estructurado para alinearse horizontalmente */
        .sig-box { 
            display: inline-block; 
            width: 45%; 
            border-top: 1px solid #000; 
            padding-top: 5px; 
            vertical-align: bottom;
            text-align: center;
        }

        /* Estilos para los Movimientos Registrales en bloque */
        .movimiento-box {
            margin-bottom: 25px;
            page-break-inside: auto !important; 
            border: none; 
        }
        .mov-header {
            background-color: #eaeaea;
            padding: 6px 10px;
            border-top: 1px solid #333;
            border-bottom: 1px solid #333;
            font-size: 9px;
            page-break-inside: avoid !important;
        }
        .mov-header strong { text-transform: uppercase; }
        
        /* MAQUETADO SIN TABLAS Y SIN LÍNEAS VERTICALES */
        .mov-row {
            display: block;
            border-bottom: 1px dashed #ccc; 
            padding: 6px 0; 
            page-break-inside: auto !important; 
            min-height: 18px;
        }
        .movimiento-box .mov-row:last-child {
            border-bottom: 1px solid #333; 
        }
        
        .mov-label {
            float: left;
            width: 18%;
            font-weight: bold;
            color: #444;
        }
       
        .mov-data {
            float: left;
            width: 82%;
            white-space: normal !important; 
            word-wrap: break-word;          
            text-align: justify;            
            page-break-inside: auto !important; 
        }
        
        /* Limpiador de floats obligatorio para Dompdf */
        .clearfix {
            clear: both;
        }

        .info-grid {
            width: 100%;             
            max-width: 800px;        
            border-collapse: collapse;
            table-layout: auto;      
        }

        .info-grid td {
            padding: 6px 10px;
            vertical-align: top;
            border: none; /* Quitamos bordes a la información registral */
        }

        .col-label {
            width: 20%;              
            white-space: nowrap;     
            font-weight: bold;
        }

        .col-value {
            width: 30%;              
        }   
    </style>
</head>
<body>

    <header style="border-bottom: 2px solid #000; padding-bottom: 10px;">
        <table class="header-table">
            <tr>
                <td style="width: 22%; vertical-align: middle;">
                    <img src="{{ public_path('images/logosevilla.png') }}" style="width: 90px; height: auto; display: block;">
                </td>
                
                <td style="width: 56%; vertical-align: middle; text-align: center; padding: 0 5px;">
                    <h1 style="margin: 0; font-size: 13px; font-weight: bold; line-height: 1.2; text-transform: uppercase;">
                        Registro de la Propiedad del Cantón Sevilla Don Bosco
                    </h1>
                    <p style="margin: 3px 0; font-size: 9px; color: #444;">
                        Macas, calle 24 de Mayo y Domingo Comín
                    </p>
                    <h2 style="margin: 5px 0 0 0; font-size: 11px; color: #000; font-weight: bold;">
                        Ficha Registral - Bien Inmueble N°: SDB-{{$ficha}}
                    </h2>
                </td>
                
                <td style="width: 22%; vertical-align: middle; text-align: right;">
                    <div style="display: inline-block; text-align: center;">
                        <img src="data:image/png;base64,{{ $imagenCodigoBarras }}" alt="Código QR" style="width: 70px; height: 70px; display: block; margin: 0 auto;">
                        <div style="font-size: 8px; color: #333; font-family: monospace; letter-spacing: 1px; margin-top: 2px; text-align: center;">
                            **SBD-{{$ficha}}**
                        </div>
                    </div>
                </td>
            </tr>
            
        </table>
    </header>

  <footer>
    <table class="footer-table">
        <tr>
            <td style="width: 30%;"></td>
            <td style="width: 40%; text-align: center; font-weight: bold; text-transform: uppercase;">
                Registro de la Propiedad del Cantón Sevilla Don Bosco
            </td>
            <td style="width: 30%; text-align: right;"></td>
        </tr>
    </table>
</footer>

    <div class="section-title" style="margin-top: 0;">INFORMACIÓN REGISTRAL</div>
    <table class="info-grid">
        <tr>
            <td class="col-label">Fecha de Apertura:</td>
            <td class="col-value">{{ $datosFicha->fichfecape ?? 'N/A' }}</td>
            <td class="col-label">Parroquia:</td>
            <td class="col-value">{{ $datosFicha->canton ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="col-label">Tipo de Predio:</td>
            <td class="col-value">{{ $datosFicha->fichtippre ?? 'N/A' }}</td>
            <td class="col-label">Cód. Catastral:</td>
            <td class="col-value">{{ $datosFicha->fichcodigo ?? 'N/A' }}</td>
        </tr>
    </table>

    <div class="section-title">LINDEROS REGISTRALES</div>
    <div class="linderos" style="border: 1px solid #000; padding: 5px; text-align: justify; line-height: 1.5; page-break-inside: auto !important;">
        @php
            // 1. Obtenemos el texto o el mensaje por defecto
            $linderos = $datosFicha->fichlinreg ?? 'No registra linderos.';
            
            // 2. Si hay linderos reales, aplicamos el formato con linderos
            if ($linderos !== 'No registra linderos.') {
                $linderos = preg_replace(
                    '/(NORTE[\.\-]*|SUR[\.\-]*|ESTE[\.\-]*|OESTE[\.\-]*|Área total:)/i', 
                    '<br><b>$1</b> ', 
                    $linderos
                );
            }
        @endphp
        
        {!! $linderos !!}
    </div>

    <div class="section-title">MOVIMIENTOS REGISTRALES</div>
    @forelse($resultados as $index => $fila)
    <div class="movimiento-box">
        <div class="mov-header">
            <strong>{{ $index + 1 }}.- ACTO: {{ $fila->tipo_acto }}</strong> 
            <span style="float: right;">
                <strong>Ins:</strong> N° {{ $fila->num_inscripcion }} &nbsp;|&nbsp;
                <strong>Rep:</strong> N° {{ $fila->num_repertorio }} &nbsp;|&nbsp;   
                <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($fila->fecha_inscripcion)->format('d/m/Y') }}
            </span>
        </div>
        
        <div class="mov-content" style="page-break-inside: auto !important;">
            
            <div class="mov-row">
                <div class="mov-label">Intervinientes:</div>
                <div class="mov-data">
                    {!! $fila->nombre_cliente !!}
                </div>
                <div class="clearfix"></div>
            </div>

            @if(!empty($fila->observacion))
            <div class="mov-row">
                <div class="mov-label">Observaciones:</div>
                <div class="mov-data">
                    {!! nl2br(e($fila->observacion)) !!}
                </div>
                <div class="clearfix"></div>
            </div>
            @endif

            <div class="mov-row">
                <div class="mov-label">Detalles del Libro:</div>
                <div class="mov-data">
                    Registrado en <strong>{{ $fila->libro }}</strong> 
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="mov-row">
                <div class="mov-label">Responsable:</div>
                <div class="mov-data">
                    Usuario responsable del movimiento <strong>{{ $fila->usuario }}</strong> 
                </div>
                <div class="clearfix"></div>
            </div>

        </div>
    </div>
    @empty
    <div style="text-align: center; border: 1px dashed #999; padding: 20px; color: #666;">
        No existen movimientos registrales para esta ficha.
    </div>
    @endforelse
   
    <div class="section-title">RESUMEN DE ACTOS A CERTIFICAR</div>
    <table style="page-break-inside: avoid !important;">
        <thead>
            <tr>
                <th>Tipo de Acto</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            @foreach($resumen as $acto => $cantidad)
                <tr>
                    <td>{{ $acto }}</td>
                    <td>{{ $cantidad }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="certification" style="page-break-inside: avoid !important;"> 
        <p><strong>CERTIFICACIÓN:</strong></p>
        <p>El suscrito Registrador de la Propiedad del Cantón Sevilla don Bosco, certifica que la presente ficha registral, con número <strong>** SDB-{{$ficha}} **</strong>, es fiel copia del original que reposa en los archivos de esta institución. La presente certificación se extiende a los {{ \Carbon\Carbon::now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}.</p>
    
        <div style="border: 1px solid #333; padding: 10px; margin: 20px 0; background-color: #f9f9f9; text-align: center;">
            <p style="margin: 0; font-size: 0.9em;">
                <strong>NOTA:</strong> El certificado tiene validez únicamente por <strong> 30 días </strong> a partir de su emisión.
            </p>
        </div>
    </div>

    <div class="signatures">
        
        <div style="display: inline-block; width: 40%; vertical-align: bottom; text-align: center; margin-right: 5%;">
            <img src="data:image/png;base64,{{ $imagenCodigoBarras }}" alt="Código QR" style="width: 85px; height: 85px; display: block; margin: 0 auto;">
            <div style="font-size: 8px; color: #333; font-family: monospace; letter-spacing: 1px; margin-top: 4px; text-align: center;">
                <strong> **SBD-{{$ficha}}**</strong>
            </div>
        </div>
        
        <div class="sig-box">
            <strong>Abg. Jair Alexander Ojeda Bueno</strong><br>
            <span style="font-size: 10px; color: #333;">EL REGISTRADOR DE LA PROPIEDAD Y MERCANTIL</span><br>
            <span style="font-size: 9px; color: #555;">Firma y Sello</span>
        </div>
        
    </div>
    <script type="text/php">
        if ( isset($pdf) ) {
            $font = $fontMetrics->get_font("sans-serif", "normal");
            // Texto, x, y, font, size, color
            $pdf->page_text(490, 800, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, 9, array(0.33, 0.33, 0.33));
        }
    </script>
</body>
</html>