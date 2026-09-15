<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acta de Inscripción N° {{ $movimiento->movinumins }}</title>
    <style>
        @page {
            size: A4;
            /* IMPORTANTE: Aumenté el margen superior de 10mm a 35mm para darle espacio al encabezado */
            margin: 40mm 15mm 20mm 15mm;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #0f172a;
        }

        /* Encabezado Fijo */
        .header-fijo {
            position: fixed;
            /* Sube exactamente la misma cantidad que le dimos al margen superior (35mm) */
            top: -35mm; 
            left: 0px;
            right: 0px;
            height: 30mm;
        }

        .header {
            width: 100%;
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 8px;
            /* Quitamos el margin-bottom porque el espacio ya lo da el @page */
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

        /* Resto de tus estilos... */
        .title-banner { background-color: #f1f5f9; border-left: 4px solid #1e3a8a; padding: 8px 12px; margin-bottom: 15px; }
        .title-banner h1 { font-size: 11pt; margin: 0; color: #0f172a; text-transform: uppercase; }
        .title-banner p { margin: 2px 0 0 0; font-size: 8pt; color: #64748b; }
        .grid-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .grid-table td { padding: 5px 8px; font-size: 8.5pt; vertical-align: top; border: 1px solid #e2e8f0; }
        .grid-table .label { font-weight: bold; color: #334155; width: 22%; background-color: #f8fafc; }
        .grid-table .value { color: #0f172a; width: 28%; }
        .section-title { font-size: 9pt; font-weight: bold; color: #1e3a8a; text-transform: uppercase; border-bottom: 1px solid #cbd5e1; padding-bottom: 3px; margin-top: 14px; margin-bottom: 8px; }
        p.narrative { text-align: justify; text-indent: 25px; margin-bottom: 10px; margin-top: 0; }
        .property-desc { background-color: #fafafa; border: 1px solid #e2e8f0; padding: 8px 12px; margin: 8px 0 14px 0; font-size: 9.5pt; text-align: justify; }
        .signature-section { margin-top: 45px; page-break-inside: avoid; }
        .sig-table { width: 100%; border-collapse: collapse; }
        .sig-table td { width: 50%; text-align: center; vertical-align: top; padding: 10px; }
        .sig-line { border-top: 1px solid #0f172a; width: 80%; margin: 0 auto 4px auto; }
        .footer-acta { margin-top: 30px; font-size: 7.5pt; color: #64748b; text-align: center; font-family: sans-serif; }
    </style>
</head>
<body>

    <!-- 1. AQUÍ APLICAMOS LA CLASE header-fijo -->
    <div class="header header-fijo">
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
                        <img src="data:image/png;base64,{{ $qrBase64 }}" style="width: 90px; height: 90px; display: inline-block;">
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- 2. ENVOLVEMOS EL CONTENIDO EN UNA ETIQUETA <main> -->
    <main>
        <div class="title-banner">
            <h1>ACTA DE INSCRIPCIÓN</h1>
            <p>Documento oficial generado automáticamente por el Sistema SIRE</p>
        </div>

        <table class="grid-table">
            <tr>
                <td class="label">Numero. Repertorio:</td>
                <td class="value">{{ $movimiento->movinumrep }}</td>
                <td class="label">Fecha Inscripción:</td>
                <td class="value">{{ date('d/m/Y', strtotime($movimiento->movifecins)) }}</td>
            </tr>
            <tr>
                <td class="label">Numero. Inscripción:</td>
                <td class="value">{{ $movimiento->movinumins }}</td>
                <td class="label">Numero. Tomo</td>
                <td class="value">{{ $movimiento->movinumtom }}</td>
            </tr>
            <tr>
                <td class="label">Ficha N°:</td>
                <td class="value">SDB-{{ $fichaRef->reffnumfic ?? 'S/N' }}</td>
                <td class="label">Naturaleza del Acto</td>
                <td class="value">{{ $movimiento->actonombre }}</td>
            </tr>
        </table>

        <p class="narrative">
            En el Cantón Sevilla Don Bosco, el {{ date('d', strtotime($movimiento->movifecins)) }} de {{ ucfirst(\Carbon\Carbon::parse($movimiento->movifecins)->locale('es')->translatedFormat('F')) }} del año {{ date('Y', strtotime($movimiento->movifecins)) }}, se procedió a inscribir en el <strong>LIBRO {{ $movimiento->librnombre }}</strong> La escritura publica formalizado mediante el Sistema Registral signado mediante el número de repertorio <strong>{{ $movimiento->movinumrep }}</strong>, número de tomo <strong>{{ $movimiento->movinumtom }}</strong>  y la inscripción número <strong>{{ $movimiento->movinumins }}</strong>.
        </p>

        <div class="section-title">1. OBSERVACIONES</div>
        <p class="narrative">
             {!! nl2br(e($movimiento->moviobserv ?? 'Sin observaciones adicionadas.' )) !!}
        </p>

        <div class="section-title">2. DESCRIPCIÓN Y LINDEROS DEL INMUEBLE</div>
        <div class="property-desc">
            <div style="margin-bottom: 6px;">
                <strong>Clave Catastral:</strong> {{ $fichaRef->fichcodigo ?? 'N/A' }} <br>
                <strong>Ubicación:</strong> {{ $fichaRef->fichparroq ?? $movimiento->cantnombre ?? 'N/A' }}
            </div>

            <div class="section-title" style="font-weight: bold; font-size: 9.5pt; text-transform: uppercase; border-bottom: 1px solid #cbd5e1; margin-top: 8px; margin-bottom: 4px;">
                LINDEROS REGISTRALES
            </div>

            <div class="linderos" style="text-align: justify; line-height: 1.5; font-size: 9.5pt;">
                @php
                    $textoLinderos = $fichaRef->fichlinreg ?? 'No registra linderos.';
                    if ($textoLinderos !== 'No registra linderos.') {
                        $textoLinderos = preg_replace(
                            '/(NORTE[\.\-]*|SUR[\.\-]*|ESTE[\.\-]*|OESTE[\.\-]*|Área total:)/i', 
                            '<br><b>$1</b> ', 
                            $textoLinderos
                        );
                    }
                @endphp
                {!! $textoLinderos !!}
            </div>
        </div>

        <div class="section-title">3. INTERVINIENTES Y COMPARECIENTES</div>
        <p class="narrative">
            Comparecen al presente asiento registral las siguientes personas:
        </p>
        <ul>
            @forelse($intervinientes as $persona)
                <li style="font-size: 10pt; font-family: sans-serif; margin-bottom: 3px;">
                    <strong>{{ $persona->clienombre }}</strong> con numero de identificacion <code>{{ $persona->cliecedruc }}</code> y en calidad de: <strong>{{ $persona->papenombre ?? 'INTERVINIENTE' }}</strong>.
                </li>
            @empty
                <li style="font-size: 10pt; font-family: sans-serif;">Sin intervinientes detallados.</li>
            @endforelse
        </ul>

        <p class="narrative">
            Con lo cual queda formalizado y concluido el presente asiento en el Libro Registro correspondiente, firmando los funcionarios responsables para constancia de lo actuado.
        </p>

        <div class="signature-section">
            <table class="sig-table">
                <tr>
                    <td style="width: 30%; text-align: center;">
                        @if(!empty($qrBase64))
                            <img src="data:image/png;base64,{{ $qrBase64 }}" style="width: 90px; height: 90px; display: inline-block;">
                            <div style="font-size: 7.5pt; color: #64748b; margin-top: 2px;">Validación SIRE</div>    
                        @endif
                    </td>
                    <td style="width: 35%; padding-top: 80px;">
                        <div class="sig-line"></div>
                        <strong>{{ $registradorActual?->titulo_profesional ?? 'Registrador de la Propiedad' }}. {{ $registradorActual?->nombre_completo ?? 'REGISTRADOR NO ASIGNADO' }}</strong><br>
                        <span style="font-size: 8.5pt; font-family: sans-serif;">Registrador(a) de la Propiedad y Mercantil (E) </span>
                        <span style="font-size: 8.5pt; font-family: sans-serif;">Canton Sevilla Don Bosco</span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="footer-acta">
            Documento generado por el operador: {{ $movimiento->nom_usuario ?? 'SISTEMA' }}. Verifique la validez en la plataforma SIRE.
        </div>
    </main>

    {{-- PIE DE PÁGINA DINÁMICO CON NUMERACIÓN --}}
    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->get_font("Helvetica", "normal");
            $size = 8;
            $x = 260; 
            $y = 810; 
            $text = "Página {PAGE_NUM} de {PAGE_COUNT}";
            $color = array(0.39, 0.45, 0.55);
            $pdf->page_text($x, $y, $text, $font, $size, $color);
        }
    </script>
</body>
</html>