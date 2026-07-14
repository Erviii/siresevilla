<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        /* Aumentamos el margen inferior para darle espacio al pie de página */
        @page { margin: 1.5cm 1.5cm 2cm 1.5cm; }
        body { font-family: sans-serif; font-size: 11px; line-height: 1.4; }
        
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
            page-break-inside: auto !important; /* Permite que la tabla se divida en dos páginas */
        }
        tr { 
            page-break-inside: avoid !important; /* Evita que una fila se corte por la mitad del texto */
            page-break-after: auto !important;
        }
        
        th, td { border: 1px solid #000; padding: 6px; text-align: left; word-wrap: break-word; }
        th { background: #f2f2f2; }
        .signatures { margin-top: 50px; width: 100%; text-align: center; }
        .sig-box { display: inline-block; width: 45%; border-top: 1px solid #000; padding-top: 5px; margin-top: 40px; }

        /* Estilos para los Movimientos Registrales en bloque */
        .movimiento-box {
            border: 1px solid #333;
            margin-bottom: 15px;
            page-break-inside: auto !important; /* <-- CAMBIO CRUCIAL: Antes decía 'avoid', ahora fluye a la sig. pág */
            border-radius: 4px;
        }
        .mov-header {
            background-color: #eaeaea;
            padding: 6px 10px;
            border-bottom: 1px solid #333;
            font-size: 9px;
        }
        .mov-header strong { text-transform: uppercase; }
        .mov-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
            margin-top: 0;
        }
        .mov-table td {
            border: none;
            border-bottom: 1px dashed #ccc;
            padding: 6px 10px;
            vertical-align: top;
        }
        .mov-table tr:last-child td { border-bottom: none; }
        .mov-label { width: 18%; font-weight: bold; color: #444; }
        .mov-data { width: 82%; text-align: justify; }
    </style>
</head>
<body>

    <footer>
        <table class="footer-table">
            <tr>
                <td style="width: 30%;"></td>
                <td style="width: 40%; text-align: center; font-weight: bold; text-transform: uppercase;">
                    Registro de la Propiedad del Cantón Sevilla Don Bosco
                </td>
                <td style="width: 30%; text-align: right;">
                    Página <span class="pagenum"></span>
                </td>
            </tr>
        </table>
    </footer>

    <div class="header" style="border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 15px;">
        <table style="width: 100%; border-collapse: collapse; border: none; table-layout: fixed;">
            <tr>
                <td style="width: 20%; vertical-align: middle; border: none; padding: 0;">
                    <img src="{{ public_path('images/logosevilla.png') }}" style="width: 90px; height: auto; display: block;">
                </td>
                
                <td style="width: 55%; vertical-align: middle; text-align: center; border: none; padding: 0 10px;">
                    <h1 style="margin: 0; font-size: 14px; font-weight: bold; line-height: 1.2; text-transform: uppercase;">
                        Registro de la Propiedad del Cantón Sevilla Don Bosco
                    </h1>
                    <p style="margin: 3px 0; font-size: 10px; color: #444;">
                        Macas, calle 24 de Mayo y Domingo Comín
                    </p>
                    <h2 style="margin: 5px 0 0 0; font-size: 12px; color: #000; font-weight: bold;">
                        Ficha Registral - Bien Inmueble N°: {{ $ficha }}
                    </h2>
                </td>
                
                <td style="width: 25%; vertical-align: middle; text-align: right; border: none; padding: 0;">
                    <div style="display: inline-block; text-align: center;">
                        <img src="data:image/png;base64,{{ $imagenCodigoBarras }}" alt="Código de Barras" style="width: 160px; height: 40px; display: block; margin: 0 auto;">
                        
                        <div style="font-size: 8px; color: #333; font-family: monospace; letter-spacing: 1px; margin-top: 2px; text-align: center;">
                            **{{ $ficha }}**
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="section-title">INFORMACIÓN REGISTRAL</div>
    <table class="info-grid">
        <tr>
            <td><strong>Fecha de Apertura:</strong></td>
            <td>{{ $datosFicha->fichfecape ?? 'N/A' }}</td>
            <td><strong>Parroquia:</strong></td>
            <td>{{ $datosFicha->parroquia ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Tipo de Predio:</strong></td>
            <td>{{ $datosFicha->tipo_predio ?? 'N/A' }}</td>
            <td><strong>Cód. Catastral:</strong></td>
            <td>{{ $datosFicha->cod_catastral ?? 'N/A' }}</td>
        </tr>
    </table>

    <div class="section-title">LINDEROS REGISTRALES</div>
    <div class="linderos" style="border: 1px solid #000; padding: 5px;">
       {{ $datosFicha->fichlinreg ?? 'No registra linderos.' }}
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
        
        <table class="mov-table">
            <tr>
                <td class="mov-label">Intervinientes:</td>
                <td class="mov-data">{!! $fila->nombre_cliente !!}</td>
            </tr>
            @if(!empty($fila->observacion))
            <tr>
                <td class="mov-label">Observaciones:</td>
                <td class="mov-data">{{ $fila->observacion }}</td>
            </tr>
            @endif
            <tr>
                <td class="mov-label">Detalles del Libro:</td>
                <td class="mov-data">
                    Registrado en <strong>{{ $fila->libro }}</strong> 
                </td>
            </tr>

 <tr>
                <td class="mov-label">Responsable:</td>
                <td class="mov-data">
                    Usuario responsable del movimiento <strong>{{ $fila->usuario }}</strong> 
                </td>
            </tr>

        </table>
    </div>
    @empty
    <div style="text-align: center; border: 1px dashed #999; padding: 20px; color: #666;">
        No existen movimientos registrales para esta ficha.
    </div>
    @endforelse
   
    <div class="section-title">RESUMEN DE ACTOS A CERTIFICAR</div>
    <table>
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

    <div class="certification">
        <p><strong>CERTIFICACIÓN:</strong></p>
        <p>El suscrito Registrador de la Propiedad del Cantón Sevilla don Bosco, certifica que la presente ficha registral, con número <strong>** {{ $ficha }} **</strong>, es fiel copia del original que reposa en los archivos de esta institución. La presente certificación se extiende a los {{ \Carbon\Carbon::now()->format('d \ de F \ de Y') }}.</p>
    
        <div style="border: 1px solid #333; padding: 10px; margin: 20px 0; background-color: #f9f9f9; text-align: center;">
            <p style="margin: 0; font-size: 0.9em;">
                <strong>NOTA:</strong> El certificado tiene validez únicamente por <strong> 30 días </strong> a partir de su emisión.
            </p>
        </div>
    </div>

    <div class="signatures">
        <div class="sig-box">
            <strong>Abg. Jair Alexander Ojeda Bueno</strong><br>
            EL REGISTRADOR DE LA PROPIEDAD Y MERCANTIL<br>
            Firma y Sello
        </div>
    </div>
</body>
</html>