@extends('emails.layout', ['title' => 'Chronomètre actif prolongé · DevPulse'])

@section('content')
    <h2 style="color: #E63946; font-size: 20px; font-weight: 700; margin-top: 0;">
        ⏱️ Chronomètre resté actif depuis {{ $hours }}h
    </h2>
    <p>
        Bonjour {{ $user->name }}, votre session sur la tâche <strong>« {{ $taskTitle }} »</strong> tourne toujours en continu.
    </p>

    <div class="card-stat">
        <div class="stat-label">Durée enregistrée</div>
        <div class="stat-value">{{ $hours }}h {{ $minutes }}m</div>
        <p style="margin: 8px 0 0 0; font-size: 13px; color: #495057;">
            Démarré le {{ $startedAt->format('d/m/Y à H:i') }}.
        </p>
    </div>

    <p style="font-size: 14px; color: #495057;">
        Afin de conserver des statistiques de facturation exactes et propres pour vos clients, vous pouvez ajuster ou clore ce créneau directement :
    </p>

    <div style="text-align: center; margin: 24px 0;">
        <a href="{{ $timerUrl }}" class="btn-primary" style="background-color: #E63946;">
            Ajuster mon chronomètre →
        </a>
    </div>

    <p style="font-size: 12px; color: #868E96; text-align: center;">
        Note : Les chronomètres dépassant 12 heures sont automatiquement détectés par nos garde-fous de productivité.
    </p>
@endsection
