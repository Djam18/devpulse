@extends('emails.layout', ['title' => 'Bienvenue sur DevPulse'])

@section('content')
    <h2 style="color: #1E1E24; font-size: 20px; font-weight: 700; margin-top: 0;">
        Bienvenue, {{ $user->name }} 👋
    </h2>
    <p>
        Votre compte <strong>DevPulse</strong> est opérationnel ! Vous êtes prêt à suivre votre temps de code, prioriser vos tickets et optimiser vos cycles de concentration.
    </p>

    <div class="card-stat">
        <div style="font-weight: 700; color: #7D2D68; margin-bottom: 6px;">🚀 Vos 3 premières étapes recommandées :</div>
        <ul style="margin: 0; padding-left: 20px; color: #495057; font-size: 14px;">
            <li style="margin-bottom: 4px;"><strong>Lancer votre premier chrono</strong> d'un clic dans la barre supérieure.</li>
            <li style="margin-bottom: 4px;"><strong>Créer vos premières tâches</strong> sur votre tableau Kanban de sprint.</li>
            <li style="margin-bottom: 4px;"><strong>Activer le mode Pomodoro</strong> pour des blocs de focus de 25 minutes.</li>
        </ul>
    </div>

    <div style="text-align: center; margin: 28px 0;">
        <a href="{{ $dashboardUrl }}" class="btn-primary">
            Ouvrir mon tableau de bord →
        </a>
    </div>

    <p style="font-size: 13px; color: #6C757D; margin-top: 24px;">
        Astuce : Votre avatar et votre compte sont synchronisés avec votre profil GitHub pour une expérience fluide.
    </p>
@endsection
