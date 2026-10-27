@extends('emails.layout', ['title' => 'Votre récapitulatif hebdomadaire · DevPulse'])

@section('content')
    <h2 style="color: #1E1E24; font-size: 20px; font-weight: 700; margin-top: 0;">
        📊 Votre semaine en chiffres
    </h2>
    <p>
        Bonjour {{ $user->name }}, voici le récapitulatif de votre activité de dev pour la semaine écoulée :
    </p>

    <div class="card-stat">
        <table style="width: 100%; text-align: center; border-collapse: collapse;">
            <tr>
                <td style="padding: 8px;">
                    <div class="stat-label">Heures de code</div>
                    <div class="stat-value">{{ $totalHours }}h</div>
                </td>
                <td style="padding: 8px; border-left: 1px solid #E9ECEF; border-right: 1px solid #E9ECEF;">
                    <div class="stat-label">Pomodoros 🍅</div>
                    <div class="stat-value" style="color: #E63946;">{{ $pomodorosCount }}</div>
                </td>
                <td style="padding: 8px;">
                    <div class="stat-label">Tâches Finies</div>
                    <div class="stat-value" style="color: #10B981;">{{ $completedTasksCount }}</div>
                </td>
            </tr>
        </table>
    </div>

    @if(!empty($topProjects))
        <div class="card-stat">
            <div style="font-weight: 700; color: #1E1E24; margin-bottom: 8px;">Répartition par projet :</div>
            <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                @foreach($topProjects as $project)
                    <tr style="border-bottom: 1px solid #ECEEF0;">
                        <td style="padding: 6px 0;">
                            <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background-color: {{ $project['color'] ?? '#7D2D68' }}; margin-right: 6px;"></span>
                            {{ $project['name'] }}
                        </td>
                        <td style="padding: 6px 0; text-align: right; font-weight: 700; color: #7D2D68;">
                            {{ $project['hours'] }}h
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    @endif

    <div style="text-align: center; margin: 28px 0;">
        <a href="{{ $analyticsUrl }}" class="btn-primary">
            Voir mes analyses détaillées →
        </a>
    </div>
@endsection
