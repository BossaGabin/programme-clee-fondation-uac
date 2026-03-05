{{-- resources/views/layouts/partials/sidebar.blade.php --}}

<!-- sidebar -->
<div class="nk-sidebar">
    <div class="nk-nav-scroll" >
        <ul class="metismenu" id="menu">

            {{-- =============================== --}}
            {{-- SIDEBAR ADMIN --}}
            {{-- =============================== --}}
            @if (auth()->user()->role === 'admin')

                <li class="nav-label">Principal</li>

                <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>

                <li class="nav-label">Gestion</li>

                {{-- Demandes de diagnostic --}}
                <li class="{{ request()->routeIs('admin.demandes.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.demandes.index') }}">
                        <i class="fas fa-clipboard-list"></i>
                        <span class="nav-text">Les Demandes </span>
                        @php
                            $pendingCount = \App\Models\DiagnosticRequest::where('status', 'pending')->count();
                        @endphp
                        @if ($pendingCount > 0)
                            <span class="badge badge-danger nav-badge">{{ $pendingCount }}</span>
                        @endif
                    </a>
                </li>

                {{-- Coachs --}}
                <li class="{{ request()->routeIs('admin.coachs.*') ? 'active' : '' }}">
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span class="nav-text">Coachs</span>
                    </a>
                    <ul aria-expanded="false">
                        <li class="{{ request()->routeIs('admin.coachs.index') ? 'active' : '' }}">
                            <a href="{{ route('admin.coachs.index') }}">
                                <i class="fas fa-list"></i> Liste des coachs
                            </a>
                        </li>
                    </ul>
                </li>



                {{-- Candidats avec sous-menu par statut --}}
                <li class="{{ request()->routeIs('admin.candidats.*') ? 'active' : '' }}">
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fas fa-users"></i>
                        <span class="nav-text">Candidats</span>
                    </a>
                    <ul aria-expanded="false">
                        <li class="{{ request()->is('admin/candidats') && !request()->has('statut') ? 'active' : '' }}">
                            <a href="{{ route('admin.candidats.index') }}">
                                <i class="fas fa-th-list"></i> Tous les candidats
                            </a>
                        </li>
                        <li
                            class="{{ request()->is('admin/candidats') && request()->get('statut') === 'stage' ? 'active' : '' }}">
                            <a href="{{ route('admin.candidats.index', ['statut' => 'stage']) }}">
                                <i class="fas fa-briefcase"></i> Stage
                            </a>
                        </li>
                        <li
                            class="{{ request()->is('admin/candidats') && request()->get('statut') === 'insertion_emploi' ? 'active' : '' }}">
                            <a href="{{ route('admin.candidats.index', ['statut' => 'insertion_emploi']) }}">
                                <i class="fas fa-handshake"></i> Insertion emploi
                            </a>
                        </li>
                        <li
                            class="{{ request()->is('admin/candidats') && request()->get('statut') === 'auto_emploi' ? 'active' : '' }}">
                            <a href="{{ route('admin.candidats.index', ['statut' => 'auto_emploi']) }}">
                                <i class="fas fa-store"></i> Auto-emploi
                            </a>
                        </li>
                        <li
                            class="{{ request()->is('admin/candidats') && request()->get('statut') === 'formation' ? 'active' : '' }}">
                            <a href="{{ route('admin.candidats.index', ['statut' => 'formation']) }}">
                                <i class="fas fa-graduation-cap"></i> Formation
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fas fa-chalkboard-user"></i>
                        <span class="nav-text">Utilsateurs</span>
                    </a>
                    <ul aria-expanded="false">
                        <li class="{{ request()->routeIs('admin.users.create') ? 'active' : '' }}">
                            <a href="{{ route('admin.users.create') }}">
                                <i class="fas fa-user-plus"></i> Créer un utilisateur
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                            <a href="{{ route('admin.users.index') }}">
                                <i class="fas fa-list"></i> Liste des utilisateur
                            </a>
                        </li>
                        {{-- <li class="{{ request()->routeIs('admin.users.trashed') ? 'active' : '' }}">
                            <a href="{{ route('admin.users.trashed') }}">
                                <i class="fas fa-archive mr-1"></i> Utilisateurs archivés
                                @php
                                    $trashedCount = \App\Models\User::onlyTrashed()->count();
                                @endphp
                                @if ($trashedCount > 0)
                                    <span class="badge badge-danger ml-1">{{ $trashedCount }}</span>
                                @endif
                            </a>
                        </li> --}}

                    </ul>
                </li>


            @endif

            {{-- =============================== --}}
            {{-- SIDEBAR COACH --}}
            {{-- =============================== --}}
            @if (auth()->user()->role === 'coach')

                <li class="nav-label">Principal</li>

                <li class="{{ request()->routeIs('coach.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('coach.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>

                <li class="nav-label">Mes Candidats</li>

                {{-- Candidats avec sous-menu par statut --}}
                <li class="{{ request()->routeIs('coach.dashboard') ? 'active' : '' }}">
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fas fa-users"></i>
                        <span class="nav-text">Mes candidats</span>
                        @php
                            $totalCandidats = auth()->user()->assignments()->where('status', 'active')->count();
                        @endphp
                        @if ($totalCandidats > 0)
                            <span class="badge badge-primary nav-badge">{{ $totalCandidats }}</span>
                        @endif
                    </a>
                    <ul aria-expanded="false">
                        <li
                            class="{{ request()->routeIs('coach.dashboard') && !request()->has('statut') ? 'active' : '' }}">
                            <a href="{{ route('coach.dashboard') }}">
                                <i class="fas fa-th-list"></i> Tous
                            </a>
                        </li>
                        <li class="{{ request()->get('statut') === 'stage' ? 'active' : '' }}">
                            <a href="{{ route('coach.dashboard', ['statut' => 'stage']) }}">
                                <i class="fas fa-briefcase"></i> Stage
                            </a>
                        </li>
                        <li class="{{ request()->get('statut') === 'insertion_emploi' ? 'active' : '' }}">
                            <a href="{{ route('coach.dashboard', ['statut' => 'insertion_emploi']) }}">
                                <i class="fas fa-handshake"></i> Insertion emploi
                            </a>
                        </li>
                        <li class="{{ request()->get('statut') === 'auto_emploi' ? 'active' : '' }}">
                            <a href="{{ route('coach.dashboard', ['statut' => 'auto_emploi']) }}">
                                <i class="fas fa-store"></i> Auto-emploi
                            </a>
                        </li>
                        <li class="{{ request()->get('statut') === 'formation' ? 'active' : '' }}">
                            <a href="{{ route('coach.dashboard', ['statut' => 'formation']) }}">
                                <i class="fas fa-graduation-cap"></i> Formation
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Entretiens programmés --}}
                <li class="{{ request()->routeIs('coach.appointments.*') ? 'active' : '' }}">
                    <a href="{{ route('coach.appointments.index') }}">
                        <i class="fas fa-calendar-check"></i>
                        <span class="nav-text">Entretiens programmés</span>
                    </a>
                </li>

            @endif

            {{-- =============================== --}}
            {{-- SIDEBAR CANDIDAT --}}
            {{-- =============================== --}}
            @if (auth()->user()->role === 'candidat')

                <li class="nav-label">Principal</li>

                <li class="{{ request()->routeIs('candidat.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('candidat.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>

                <li class="nav-label">Mon Espace</li>

                <li class="{{ request()->routeIs('candidat.profile.*') ? 'active' : '' }}">
                    <a href="{{ route('candidat.profile.edit') }}">
                        <i class="fas fa-user-edit"></i>
                        <span class="nav-text">Mon Profil</span>
                        @php
                            $completion = auth()->user()->candidatProfile?->profile_completion ?? 0;
                        @endphp
                        @if ($completion < 100)
                            <span class="badge badge-warning nav-badge">{{ $completion }}%</span>
                        @else
                            <span class="badge badge-success nav-badge">
                                <i class="fas fa-check"></i>
                            </span>
                        @endif
                    </a>
                </li>

                <li>
                    <a href="{{ route('candidat.dashboard') }}">
                        <i class="fas fa-chart-line"></i>
                        <span class="nav-text">Mon Parcours</span>
                    </a>
                </li>

            @endif

        </ul>
    </div>
</div>
<!-- #/ sidebar -->
