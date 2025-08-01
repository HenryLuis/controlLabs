<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Sesión de Laboratorio</title>
    <style>
        /* Mantenemos los estilos generales, pero añadimos algunos para la nueva estructura */
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; }
        .container { width: 100%; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 25px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header h2 { margin: 0; font-size: 14px; font-weight: normal; }
        .header h3 { margin: 0; font-size: 14px; font-weight: bold; border-bottom: 1px solid #333; padding-bottom: 5px; margin-bottom: 15px;}

        .details-table, .attendance-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .details-table th, .details-table td, .attendance-table th, .attendance-table td { border: 1px solid #999; padding: 6px; text-align: left; }
        .details-table th { background-color: #e9e9e9; font-weight: bold; width: 15%; }
        .attendance-table th { background-color: #e9e9e9; font-weight: bold; text-align: center;}

        .observations-section { margin-bottom: 30px; }
        .observations-section h4 { border-bottom: 1px solid #333; padding-bottom: 3px; margin-bottom: 5px;}

        .footer-signatures { margin-top: 40px; }
        .footer-signatures .signature-block { display: inline-block; width: 45%; text-align: center; }
        .footer-signatures .signature-line { border-top: 1px solid #333; margin-top: 40px; padding-top: 5px; }

        .page-footer { text-align: center; font-size: 9px; color: #777; position: fixed; bottom: -20px; width: 100%; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>UNIVERSIDAD DEL VALLE</h1>
            <h2>SUB SEDE LA PAZ - CENTRO DE CÓMPUTO</h2>
            <h3>CONTROL DE LABORATORIOS</h3>
        </div>

        <!-- TABLA DE DETALLES DE LA SESIÓN -->
        <table class="details-table">
            <tr>
                <th>MATERIA:</th>
                <td>{{ $labSession->subject->name }} ({{ $labSession->subject->acronym }})</td>
                <th>FECHA:</th>
                <td>{{ $labSession->session_date->format('d / m / Y') }}</td>
            </tr>
            <tr>
                <th>DOCENTE:</th>
                <td>{{ $labSession->teacher->name }}</td>
                <th>HORARIO:</th>
                <td>DE: {{ \Carbon\Carbon::parse($labSession->start_time)->format('H:i') }} A: {{ \Carbon\Carbon::parse($labSession->end_time)->format('H:i') }}</td>
            </tr>
             <tr>
                <th>AULA:</th>
                <td colspan="3">{{ $labSession->classroom->name }}</td>
            </tr>
        </table>

        <!-- TABLA DE ASISTENCIA DE ESTUDIANTES -->
        <table class="attendance-table">
            <thead>
                <tr>
                    <th style="width: 15%;">NÚMERO PC</th>
                    <th>NOMBRE DEL ALUMNO</th>
                    <th style="width: 30%;">FIRMA</th>
                </tr>
            </thead>
            <tbody>
                @forelse($labSession->attendances as $attendance)
                <tr>
                    <td style="text-align: center;">{{ $attendance->pc_number }}</td>
                    <td>{{ $attendance->student->name }}</td>
                    <td>
                        @if($attendance->student_signature)
                        <img src="{{ $attendance->student_signature }}" alt="Firma" style="max-width: 150px; max-height: 40px;">
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center;">No hay registros de asistencia para esta sesión.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- SECCIÓN DE OBSERVACIONES Y CONTROL INTERNO -->
        <div class="observations-section">
            <h4>OBSERVACIONES:</h4>
            @if($labSession->observations->isNotEmpty())
                <ul>
                    @foreach($labSession->observations as $observation)
                        <li>
                            <strong>{{ $observation->user->name }}</strong> ({{ $observation->created_at->format('d/m/Y H:i') }}):
                            {{ $observation->observation }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p>Sin observaciones.</p>
            @endif
        </div>

        <div class="footer-signatures">
             @if($labSession->reviewer)
                <p><strong>Control interno:</strong> {{ $labSession->reviewer->name }}</p>
                <p><strong>Fecha revisión:</strong> {{ $labSession->internal_control_reviewed_at->format('d/m/Y H:i') }}</p>
            @else
                <p><strong>Control interno:</strong> _________________________</p>
                <p><strong>Fecha revisión:</strong> ____/____/______</p>
            @endif
        </div>

    </div>
    <div class="page-footer">
        Reporte generado el {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>
