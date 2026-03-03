<!-- header -->
<div class="header">
    <div class="nav-header">
        <div class="brand-logo">
            <a href="{{ route(auth()->user()->role . '.dashboard') }}">
                <span class="brand-title">
                    <strong style="
                        color:#006b08;
                        font-size:13px;
                        letter-spacing:2px;
                        text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
                        font-family: 'Segoe UI', sans-serif;
                    ">PROGRAMME</strong>
                    <strong style="
                        color:#f4a900;
                        font-size:20px;
                        letter-spacing:4px;
                        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
                        font-family: 'Segoe UI', sans-serif;
                        display:block;
                        margin-top:-4px;
                    ">CLEE</strong>
                </span>
            </a>
        </div>
        <div class="nav-control">
            <div class="hamburger">
                <span class="line"></span>
                <span class="line"></span>
                <span class="line"></span>
            </div>
        </div>
    </div>

    <div class="header-content">
        <div class="header-left">
            <ul>
                <li class="icons position-relative">
                    <a href="javascript:void(0)">
                        <i class="fas fa-search f-s-16"></i>
                    </a>
                    <div class="drop-down animated bounceInDown">
                        <div class="dropdown-content-body">
                            <div class="header-search" id="header-search">
                                <form action="#">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Rechercher...">
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="fas fa-search"></i>
                                            </span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

        <div class="header-right">
            <ul>

                {{-- Notifications --}}
                <li class="icons">
                    <a href="javascript:void(0)" class="position-relative">
                        <i class="fas fa-bell f-s-18" aria-hidden="true"></i>
                        
                        @php
                            $notificationCount = 0;
                            $notifications = [];
                            
                            // Pour les COACHS : Candidats affectés sans rendez-vous programmé
                            if(auth()->user()->role === 'coach') {
                                $assignmentsSansRdv = \App\Models\CoachAssignment::where('coach_id', auth()->id())
                                    ->where('status', 'active')
                                    ->whereDoesntHave('appointments')
                                    ->with('candidat')
                                    ->get();
                                    // dd($assignmentsSansRdv);
                                
                                $notificationCount += $assignmentsSansRdv->count();
                                
                                foreach($assignmentsSansRdv as $assignment) {
                                    $notifications[] = [
                                        'type' => 'candidat_affecte',
                                        'icon' => 'fa-user-plus',
                                        'color' => 'primary',
                                        'message' => 'Nouveau candidat affecté : ' . $assignment->candidat->name,
                                        'link' => route('coach.candidats.show', $assignment->candidat->id),
                                        'time' => $assignment->created_at->diffForHumans()
                                    ];
                                }
                            }
                            
                            // Pour les CANDIDATS : Entretiens/RDV programmés
                            if(auth()->user()->role === 'candidat') {
                                // Récupérer les rendez-vous programmés via l'assignment
                                $monAssignment = \App\Models\CoachAssignment::where('candidat_id', auth()->id())
                                    ->where('status', 'active')
                                    ->first();
                                
                                if($monAssignment) {
                                    $rdvAPasser = \App\Models\Appointment::where('coach_assignment_id', $monAssignment->id)
                                        ->where('status', 'scheduled')
                                        ->whereRaw("CONCAT(scheduled_date, ' ', scheduled_time) > NOW()")
                                        ->get();
                                    
                                    $notificationCount += $rdvAPasser->count();
                                    
                                    foreach($rdvAPasser as $rdv) {
                                        $dateTime = \Carbon\Carbon::parse($rdv->scheduled_date . ' ' . $rdv->scheduled_time);
                                        $notifications[] = [
                                            'type' => 'entretien_programme',
                                            'icon' => 'fa-calendar-check',
                                            'color' => 'success',
                                            'message' => 'Entretien programmé le ' . $dateTime->format('d/m/Y à H:i'),
                                            'link' => route('candidat.appointments.show', $rdv->id),
                                            'time' => $rdv->created_at->diffForHumans()
                                        ];
                                    }
                                }
                            }
                            
                            // Pour les ADMINS : Demandes de diagnostic en attente
                            if(auth()->user()->role === 'admin') {
                                $demandesEnAttente = \App\Models\DiagnosticRequest::where('status', 'pending')
                                    ->with('candidat')
                                    ->get();
                                
                                $notificationCount += $demandesEnAttente->count();
                                
                                foreach($demandesEnAttente as $demande) {
                                    $notifications[] = [
                                        'type' => 'demande_diagnostic',
                                        'icon' => 'fa-file-alt',
                                        'color' => 'warning',
                                        'message' => 'Nouvelle demande de diagnostic : ' . $demande->candidat->name,
                                        'link' => route('admin.demandes.show', $demande->id),
                                        'time' => $demande->created_at->diffForHumans()
                                    ];
                                }
                            }
                        @endphp
                        
                        @if($notificationCount > 0)
                            <span class="badge badge-danger notification-badge pulse-badge">
                                {{ $notificationCount > 99 ? '99+' : $notificationCount }}
                            </span>
                            <div class="pulse-css"></div>
                        @endif
                    </a>
                    
                    <div class="drop-down animated bounceInDown">
                        <div class="dropdown-content-heading">
                            <span class="text-left">
                                Notifications 
                                @if($notificationCount > 0)
                                    <span class="badge badge-danger ml-2" style="color: #fff">{{ $notificationCount }}</span>
                                @endif
                            </span>
                        </div>
                        <div class="dropdown-content-body" style="max-height: 400px; overflow-y: auto;">
                            <ul>
                                @forelse($notifications as $notification)
                                    <li>
                                        <a href="{{ $notification['link'] }}" class="notification-item">
                                            <div class="notification-content">
                                                <i class="fas {{ $notification['icon'] }} text-{{ $notification['color'] }} mr-2"></i>
                                                <div>
                                                    <p class="mb-0">{{ $notification['message'] }}</p>
                                                    <small class="text-muted">{{ $notification['time'] }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @empty
                                    <li class="text-center text-muted p-3">
                                        <i class="fas fa-bell-slash mb-2" style="font-size: 24px;"></i>
                                        <p class="mb-0">Aucune notification</p>
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </li>

                {{-- Profil utilisateur --}}
                <li class="icons">
                    <a href="javascript:void(0)">
                        @if(auth()->user()->avatar)
                            <img src="{{ Storage::url(auth()->user()->avatar) }}"
                                 alt="{{ auth()->user()->name }}"
                                 style="width:32px; height:32px; border-radius:50%; object-fit:cover;">
                        @else
                            <i class="fas fa-user-circle f-s-20" aria-hidden="true"></i>
                        @endif
                    </a>
                    <div class="drop-down dropdown-profile animated bounceInDown">
                        <div class="dropdown-content-body">

                            {{-- Infos utilisateur --}}
                            <div class="text-center p-3 border-bottom">
                                @if(auth()->user()->avatar)
                                    <img src="{{ Storage::url(auth()->user()->avatar) }}"
                                         style="width:60px; height:60px; border-radius:50%; object-fit:cover;">
                                @else
                                    <div style="width:60px; height:60px; border-radius:50%; background:#006b08;
                                                display:flex; align-items:center; justify-content:center; margin:0 auto;">
                                        <i class="fas fa-user text-white fa-2x"></i>
                                    </div>
                                @endif
                                <p class="mb-0 mt-2 font-weight-bold">{{ auth()->user()->name }}</p>
                                <small class="text-muted">{{ ucfirst(auth()->user()->role) }}</small>
                            </div>

                            <ul>
                                {{-- Lien profil selon le rôle --}}
                                @php $role = auth()->user()->role; @endphp

                                @if($role === 'candidat')
                                    <li>
                                        <a href="{{ route('candidat.profile.edit') }}">
                                            <i class="fas fa-user mr-2"></i>
                                            <span>Mon Profil</span>
                                        </a>
                                    </li>
                                @elseif($role === 'coach')
                                    <li>
                                        <a href="{{ route('coach.profile.edit') }}">
                                            <i class="fas fa-user-edit mr-2"></i>
                                            <span>Mon Profil</span>
                                        </a>
                                    </li>
                                @elseif($role === 'admin')
                                    <li>
                                        <a href="{{ route('admin.profile.edit') }}">
                                            <i class="fas fa-user-edit mr-2"></i>
                                            <span>Mon Profil</span>
                                        </a>
                                    </li>
                                @endif

                                {{-- Déconnexion --}}
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a href="#"
                                           onclick="event.preventDefault(); this.closest('form').submit();">
                                            <i class="fas fa-sign-out-alt mr-2"></i>
                                            <span>Déconnexion</span>
                                        </a>
                                    </form>
                                </li>
                            </ul>

                        </div>
                    </div>
                </li>

            </ul>
        </div>
    </div>
</div>

{{-- Styles pour les notifications --}}
<style>
/* Badge de notification */
.notification-badge {
    position: absolute;
    top: -5px;
    right: -8px;
    padding: 2px 6px;
    border-radius: 10px;
    font-size: 10px;
    font-weight: bold;
    min-width: 18px;
    text-align: center;
    z-index: 10;
}

/* Animation de pulsation pour le badge */
.pulse-badge {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
    }
    50% {
        box-shadow: 0 0 0 8px rgba(220, 53, 69, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
    }
}

/* Animation de clignotement pour l'icône */
.pulse-css {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 8px;
    height: 8px;
    background: #dc3545;
    border-radius: 50%;
    animation: blink 1.5s infinite;
}

@keyframes blink {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.3;
        transform: scale(1.2);
    }
}

/* Style des items de notification */
.notification-item {
    display: block;
    padding: 12px 15px;
    border-bottom: 1px solid #f0f0f0;
    transition: background-color 0.3s;
    text-decoration: none;
}

.notification-item:hover {
    background-color: #f8f9fa;
    text-decoration: none;
}

.notification-content {
    display: flex;
    align-items: flex-start;
}

.notification-content i {
    font-size: 18px;
    margin-top: 2px;
    min-width: 20px;
}

.notification-content p {
    font-size: 13px;
    color: #333;
    line-height: 1.4;
}

.notification-content small {
    font-size: 11px;
}

/* Dropdown des notifications */
.drop-down {
    min-width: 320px;
}

.dropdown-content-heading {
    padding: 12px 15px;
    border-bottom: 1px solid #f0f0f0;
    background: #f8f9fa;
    font-weight: 600;
}
</style>

<!-- #/ header -->