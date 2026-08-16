<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acta de Inscripción N° {{ $movimiento->movinumins }}</title>
    <style>
        @page {
            size: A4;
            margin: 10mm 15mm 20mm 15mm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #0f172a;
        }

        .header-acta {
            text-align: center;
            border-bottom: 2px double #0f172a;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }
        .header-acta h2 {
            font-size: 13pt;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header-acta h3 {
            font-size: 10pt;
            font-weight: normal;
            margin: 3px 0 0 0;
            text-transform: uppercase;
        }

        .acta-title {
            text-align: center;
            font-weight: bold;
            font-size: 12pt;
            margin-bottom: 18px;
            text-decoration: underline;
            text-transform: uppercase;
        }

        .meta-box {
            width: 100%;
            border: 1px solid #0f172a;
            margin-bottom: 18px;
            border-collapse: collapse;
        }
        .meta-box td {
            padding: 5px 8px;
            font-size: 9.5pt;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }
        .meta-box .lbl {
            font-weight: bold;
            background-color: #f1f5f9;
            width: 30%;
            border-right: 1px solid #0f172a;
            border-bottom: 1px solid #cbd5e1;
        }
        .meta-box .val {
            border-bottom: 1px solid #cbd5e1;
        }

        p.narrative {
            text-align: justify;
            text-indent: 25px;
            margin-bottom: 10px;
            margin-top: 0;
        }

        .section-h {
            font-weight: bold;
            font-size: 10pt;
            margin-top: 14px;
            margin-bottom: 4px;
            text-transform: uppercase;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 2px;
        }

        .property-desc {
            background-color: #fafafa;
            border: 1px solid #e2e8f0;
            padding: 8px 12px;
            margin: 8px 0 14px 0;
            font-size: 9.5pt;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            text-align: justify;
        }

        .signature-section {
            margin-top: 45px;
            page-break-inside: avoid;
        }
        .sig-table {
            width: 100%;
            border-collapse: collapse;
        }
        .sig-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 10px;
        }
        .sig-line {
            border-top: 1px solid #0f172a;
            width: 80%;
            margin: 0 auto 4px auto;
        }

        .footer-acta {
            margin-top: 30px;
            font-size: 7.5pt;
            color: #64748b;
            text-align: center;
            font-family: sans-serif;
        }
    </style>
</head>
<body>

    <div class="header-acta">
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width: 20%; vertical-align: middle; text-align: left;">
                <img src="{{ public_path('images/logosevilla.png') }}" style="width: 85px; height: auto; display: block;">
            </td>
            <td style="width: 80%; vertical-align: middle; text-align: center;">
                <div class="header-title"><strong>{{ $configuracion->nombre_registro ?? 'REGISTRO DE LA PROPIEDAD Y MERCANTIL' }}</strong></div>
                <div class="header-subtitle">{{ $configuracion->direccion ?? 'Sevilla don Bosco, Ecuador' }}</div>
                <h3 style="margin: 3px 0 0 0; font-size: 10pt; font-weight: normal; text-transform: uppercase;">LIBRO {{ $movimiento->librnombre }} - ACTA DE INSCRIPCIÓN</h3>
            </td>
        </tr>
    </table>
</div>

    <div class="acta-title">
        ACTA DE INSCRIPCIÓN N° {{ $movimiento->movinumins }}
    </div>

    <table class="meta-box">
        <tr>
            <td class="lbl">NÚMERO DE REPERTORIO:</td>
            <td class="val">{{ $movimiento->movinumrep }}</td>
        </tr>
        <tr>
            <td class="lbl">FECHA DE INSCRIPCIÓN:</td>
            <td class="val">{{ date('d/m/Y H:i', strtotime($movimiento->movifecins)) }}</td>
        </tr>
        <tr>
            <td class="lbl">NATURALEZA DEL ACTO:</td>
            <td class="val"><strong>{{ $movimiento->actonombre }}</strong></td>
        </tr>
        <tr>
            <td class="lbl">FICHA REGISTRAL:</td>
            <td class="val">{{ $fichaRef->reffnumfic ?? 'S/N' }}</td>
        </tr>
    </table>

    <p class="narrative">
        En el Cantón {{ $movimiento->cantnombre ?? 'Central' }}, el {{ date('d', strtotime($movimiento->movifecins)) }} de {{ \Carbon\Carbon::parse($movimiento->movifecins)->translatedFormat('F') }} del año {{ date('Y', strtotime($movimiento->movifecins)) }}, a las {{ date('H:i', strtotime($movimiento->movifecins)) }} horas, se procedió a inscribir en el <strong>LIBRO {{ $movimiento->librnombre }}</strong> el acto registral formalizado mediante el Sistema SIRE bajo el número de repertorio <strong>{{ $movimiento->movinumrep }}</strong> y la inscripción número <strong>{{ $movimiento->movinumins }}</strong>.
    </p>

    <div class="section-h">1. ANTECEDENTES Y DETALLES</div>
    <p class="narrative">
        Se presentó para la respectiva inscripción la documentación pertinente autorizada ante el {{ $movimiento->junonombre ?? 'Juzgado/Notaría correspondiente' }}. Habiéndose verificado el cumplimiento de todos los tributos, derechos y aranceles registrales establecidos por la ley.
    </p>

 <div class="section-h">2. DESCRIPCIÓN Y LINDEROS DEL INMUEBLE</div>
<div class="property-desc">
    <div style="margin-bottom: 6px;">
        <strong>Ficha Registral N°:</strong> SDB-{{ $fichaRef->reffnumfic ?? 'S/N' }} <br>
        <strong>Clave Catastral:</strong> {{ $fichaRef->fichcodigo ?? 'N/A' }} <br>
        <strong>Ubicación:</strong> {{ $fichaRef->fichparroq ?? $movimiento->cantnombre ?? 'N/A' }}
    </div>

    <div class="section-title" style="font-weight: bold; font-size: 9.5pt; text-transform: uppercase; border-bottom: 1px solid #cbd5e1; margin-top: 8px; margin-bottom: 4px;">
        LINDEROS REGISTRALES
    </div>

    <div class="linderos" style="text-align: justify; line-height: 1.5; font-size: 9.5pt;">
        @php
            // 1. Obtenemos el texto de la columna exacta 'fichlinreg'
            $textoLinderos = $fichaRef->fichlinreg ?? 'No registra linderos.';
            
            // 2. Formateamos los puntos cardinales y el área
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

    <div class="section-h">3. INTERVINIENTES Y COMPARECIENTES</div>
    <p class="narrative">
        Comparecen al presente asiento registral las siguientes personas:
    </p>
    <ul>
        @forelse($intervinientes as $persona)
            <li style="font-size: 10pt; font-family: sans-serif; margin-bottom: 3px;">
                <strong>{{ $persona->clienombre }}</strong> con numero de identifacion <code>{{ $persona->cliecedruc }}</code> y en calidad de: <strong>{{ $persona->papenombre ?? 'INTERVINIENTE' }}</strong>.
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
            
         {{-- Código QR centrado --}}
            <td style="width: 30%; text-align: center;">
                @if(!empty($qrBase64))
                    <img src="data:image/png;base64,{{ $qrBase64 }}" style="width: 90px; height: 90px; display: inline-block;">
                @endif
            </td>
        
        <td style="width: 35%;">
                <div class="sig-line"></div>
                <strong>ELABORADO POR</strong><br>
                <span style="font-size: 8.5pt; font-family: sans-serif;">{{ $movimiento->nom_usuario ?? 'Operador SIRE' }}</span>
            </td>
            
           

            <td style="width: 35%;">
                <div class="sig-line"></div>
                <strong>{{ $registradorActual?->titulo_profesional ?? 'Registrador de la Propiedad' }}. {{ $registradorActual?->nombre_completo ?? 'REGISTRADOR NO ASIGNADO' }}</strong><br>
                <span style="font-size: 8.5pt; font-family: sans-serif;">Firma Autorizada</span>
            </td>
        </tr>
    </table>
</div>

    <div class="footer-acta">
        Asiento Registral Digital generado por SIRE (Sistema de Información Registral).
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