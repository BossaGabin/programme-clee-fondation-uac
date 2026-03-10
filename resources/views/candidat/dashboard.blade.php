<!DOCTYPE html>
<html lang="en">
   <title>CLEE - Tableau de bord</title>
   @include('section.head')
   <body class="v-light vertical-nav fix-header fix-sidebar">
      <div id="preloader">
         <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
               <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3"
                  stroke-miterlimit="10" />
            </svg>
         </div>
      </div>
      <div id="main-wrapper">
         @include('section.header')
         @include('section.sidebar')
         <!-- content body -->
         <div class="content-body">
            <div class="container">
               <div class="row page-titles">
                  <div class="col p-0">
                     <h4>Salut, <span>{{ Auth::user()->name }}</span></h4>
                  </div>
               </div>
               @if (session('success'))
               <div class="alert alert-success alert-dismissible fade show">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                     aria-hidden="true">×</span>
                  </button> <strong>Bravo!</strong> {{ session('success') }}
               </div>
               @endif
               @if (session('error'))
               <div class="alert alert-danger alert-dismissible fade show">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                     aria-hidden="true">×</span>
                  </button> <strong>Erreur!</strong> {{ session('error') }}
               </div>
               @endif
               <div class="row">
                  <div class="col-12">
                     <div class="card">
                        <div class="card-body">
                           <div class="d-flex align-items-center justify-content-between mb-2">
                              <div class="d-flex align-items-center" style="gap: 12px;">
                                 @if ($candidat->avatar)
                                 <img src="{{ Storage::url($candidat->avatar) }}"
                                    style="width:55px; height:55px; border-radius:50%; object-fit:cover;">
                                 @endif
                                 <div>
                                    <h5 class="mb-0">{{ $candidat->name }}</h5>
                                    <small class="text-muted">{{ $candidat->email }}</small>
                                 </div>
                              </div>
                              <div class="text-right">
                                 <span class="font-weight-bold" style="font-size: 22px;">
                                 {{ $profile->profile_completion ?? 0 }}%
                                 </span>
                                 <br>
                                 <small class="text-muted">Profil complété</small>
                              </div>
                           </div>
                           <div class="progress" style="height: 10px; border-radius: 10px;">
                              <div class="progress-bar
                                 @if (($profile->profile_completion ?? 0) < 50) bg-danger
                                 @elseif(($profile->profile_completion ?? 0) < 100) bg-warning
                                 @else bg-success @endif"
                                 role="progressbar"
                                 style="width: {{ $profile->profile_completion ?? 0 }}%; border-radius: 10px;"
                                 aria-valuenow="{{ $profile->profile_completion ?? 0 }}" aria-valuemin="0"
                                 aria-valuemax="100">
                              </div>
                           </div>
                           @if (($profile->profile_completion ?? 0) < 100)
                           <div class="mt-2 d-flex align-items-center justify-content-between">
                              <small class="text-danger">
                              <i class="fas fa-exclamation-circle"></i>
                              Complétez votre profil à 100% pour faire une demande d'appui.
                              </small>
                              <a href="{{ route('candidat.profile.edit') }}" class="btn btn-sm btn-primary">
                              <i class="fas fa-user-edit"></i> Compléter mon profil
                              </a>
                           </div>
                           @else
                           <div class="mt-2">
                              <small class="text-success">
                              <i class="fas fa-check-circle"></i>
                              Profil complet. Vous pouvez faire une demande d'appui.
                              </small>
                           </div>
                           @endif
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row">
                  {{-- Statut de la demande de diagnostic --}}
                  <div class="col-md-4">
                     <div class="card">
                        <div class="card-header">
                           <h5 class="card-title mb-0">
                              <i class="fas fa-clipboard-list text-primary mr-2"></i>
                              Demande d'appui
                           </h5>
                        </div>
                        <div class="card-body text-center">
                           @if (!$demande)
                           <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                           <p class="text-muted">Aucune demande effectuée.</p>
                           @if (($profile->profile_completion ?? 0) >= 100)
                           {{-- Bouton déclencheur --}}
                           <button type="button" class="btn btn-primary btn-block" data-toggle="modal"
                              data-target="#modalDiagnostic">
                           <i class="fas fa-paper-plane mr-1"></i> Faire une demande
                           </button>
                           {{-- Modal --}}
                           <div class="modal fade" id="modalDiagnostic">
                              <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                 <div class="modal-content">
                                    <div class="modal-header">
                                       <h5 class="modal-title">
                                          <i class="fas fa-clipboard-list mr-2 text-primary"></i>
                                          Demande d'appui
                                       </h5>
                                       <button type="button" class="close" data-dismiss="modal">
                                       <span>&times;</span>
                                       </button>
                                    </div>
                                    <form method="POST"
                                       action="{{ route('candidat.diagnostic.store') }}">
                                       @csrf
                                       <div class="modal-body">
                                          <p class="text-muted mb-3">
                                             Avant de soumettre votre demande, décrivez votre parcours professionnel.
                                             Ces informations aideront l'administration à vous affecter le coach le plus adapté.
                                          </p>
                                          {{-- Parcours professionnel --}}
                                          <div class="form-group">
                                             <label for="parcours_professionnel" class="font-weight-bold">
                                             Votre parcours professionnel <span class="text-danger">*</span>
                                             </label>
                                             <textarea id="parcours_professionnel" name="parcours_professionnel"
                                                class="form-control @error('parcours_professionnel') is-invalid @enderror"
                                                style="height:120px;"
                                                placeholder="Je m'appelle [Prénom Nom], titulaire d'un [niveau d'étude] en [domaine]. J'ai [X] ans d'expérience dans [secteur d'activité]. Mes principales compétences sont [compétence 1], [compétence 2] et [compétence 3]. Actuellement [situation actuelle], je souhaite [objectif professionnel] afin de [ambition à court/moyen terme].">{{ old('parcours_professionnel') }}</textarea>
                                             @error('parcours_professionnel')
                                             <div class="invalid-feedback">{{ $message }}</div>
                                             @enderror
                                             <small class="text-muted">Minimum 50 caractères.</small>
                                          </div>
                                          <hr>
                                          {{-- Mode d'entretien --}}
                                          <div class="form-group">
                                             <label class="font-weight-bold">
                                             Mode d'entretien souhaité <span class="text-danger">*</span>
                                             </label>
                                             <div class="d-flex" style="gap:15px;">
                                                <div class="custom-control custom-radio">
                                                   <input type="radio" id="mode_presentiel" name="mode_entretien"
                                                   value="presentiel" class="custom-control-input"
                                                   {{ old('mode_entretien') === 'presentiel' ? 'checked' : '' }}
                                                   onchange="toggleModeEntretien('presentiel')">
                                                   <label class="custom-control-label" for="mode_presentiel">
                                                   <i class="fas fa-map-marker-alt mr-1 text-success"></i> Présentiel
                                                   </label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                   <input type="radio" id="mode_en_ligne" name="mode_entretien"
                                                   value="en_ligne" class="custom-control-input"
                                                   {{ old('mode_entretien') === 'en_ligne' ? 'checked' : '' }}
                                                   onchange="toggleModeEntretien('en_ligne')">
                                                   <label class="custom-control-label" for="mode_en_ligne">
                                                   <i class="fas fa-video mr-1 text-primary"></i> En ligne
                                                   </label>
                                                </div>
                                             </div>
                                             @error('mode_entretien')
                                             <small class="text-danger">{{ $message }}</small>
                                             @enderror
                                          </div>
                                          {{-- Sous-options EN LIGNE --}}
                                          <div id="bloc_en_ligne" style="{{ old('mode_entretien') === 'en_ligne' ? '' : 'display:none;' }}">
                                             <div class="form-group">
                                                <label class="font-weight-bold">
                                                Plateforme en ligne <span class="text-danger">*</span>
                                                </label>
                                                <div class="d-flex" style="gap:15px;">
                                                   <div class="custom-control custom-radio">
                                                      <input type="radio" id="plateforme_whatsapp" name="plateforme_enligne"
                                                      value="whatsapp" class="custom-control-input"
                                                      {{ old('plateforme_enligne') === 'whatsapp' ? 'checked' : '' }}
                                                      onchange="togglePlateforme('whatsapp')">
                                                      <label class="custom-control-label" for="plateforme_whatsapp">
                                                      <i class="fab fa-whatsapp mr-1 text-success"></i> WhatsApp
                                                      </label>
                                                   </div>
                                                   <div class="custom-control custom-radio">
                                                      <input type="radio" id="plateforme_meet" name="plateforme_enligne"
                                                      value="google_meet" class="custom-control-input"
                                                      {{ old('plateforme_enligne') === 'google_meet' ? 'checked' : '' }}
                                                      onchange="togglePlateforme('google_meet')">
                                                      <label class="custom-control-label" for="plateforme_meet">
                                                      <i class="fas fa-video mr-1 text-danger"></i> Google Meet
                                                      </label>
                                                   </div>
                                                   <div class="custom-control custom-radio">
                                                      <input type="radio" id="plateforme_appel" name="plateforme_enligne"
                                                      value="appel_direct" class="custom-control-input"
                                                      {{ old('plateforme_enligne') === 'appel_direct' ? 'checked' : '' }}
                                                      onchange="togglePlateforme('appel_direct')">
                                                      <label class="custom-control-label" for="plateforme_appel">
                                                      <i class="fas fa-phone mr-1 text-info"></i> Appel direct
                                                      </label>
                                                   </div>
                                                </div>
                                                @error('plateforme_enligne')
                                                <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                             </div>
                                             {{-- Numéro WhatsApp --}}
                                             <div id="bloc_whatsapp" style="{{ old('plateforme_enligne') === 'whatsapp' ? '' : 'display:none;' }}">
                                                <div class="form-group">
                                                   <label for="numero_whatsapp" class="font-weight-bold">
                                                   <i class="fab fa-whatsapp mr-1 text-success"></i>
                                                   Numéro WhatsApp <span class="text-danger">*</span>
                                                   </label>
                                                   <input type="text" id="numero_whatsapp" name="numero_whatsapp"
                                                      class="form-control @error('numero_whatsapp') is-invalid @enderror"
                                                      placeholder="Ex : +229 97 00 00 00"
                                                      value="{{ old('numero_whatsapp') }}">
                                                   @error('numero_whatsapp')
                                                   <div class="invalid-feedback">{{ $message }}</div>
                                                   @enderror
                                                </div>
                                             </div>
                                             {{-- Numéro appel direct --}}
                                             <div id="bloc_appel" style="{{ old('plateforme_enligne') === 'appel_direct' ? '' : 'display:none;' }}">
                                                <div class="form-group">
                                                   <label for="numero_appel" class="font-weight-bold">
                                                   <i class="fas fa-phone mr-1 text-info"></i>
                                                   Numéro joignable <span class="text-danger">*</span>
                                                   </label>
                                                   <input type="text" id="numero_appel" name="numero_appel"
                                                      class="form-control @error('numero_appel') is-invalid @enderror"
                                                      placeholder="Ex : +229 97 00 00 00"
                                                      value="{{ old('numero_appel') }}">
                                                   @error('numero_appel')
                                                   <div class="invalid-feedback">{{ $message }}</div>
                                                   @enderror
                                                </div>
                                             </div>
                                             {{-- Google Meet — pas de champ, le coach envoie le lien --}}
                                             <div id="bloc_meet" style="{{ old('plateforme_enligne') === 'google_meet' ? '' : 'display:none;' }}">
                                                <div class="alert alert-info py-2">
                                                   <i class="fas fa-info-circle mr-2"></i>
                                                   Le coach vous enverra le lien Google Meet lors de la confirmation de l'entretien.
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="modal-footer">
                                          <button type="button" class="btn btn-danger"
                                             data-dismiss="modal">
                                          <i class="fas fa-times mr-1"></i> Annuler
                                          </button>
                                          <button type="submit" class="btn btn-primary">
                                          <i class="fas fa-paper-plane mr-1"></i> Envoyer ma
                                          demande
                                          </button>
                                       </div>
                                    </form>
                                 </div>
                              </div>
                           </div>
                           @else
                           <button class="btn btn-secondary btn-block" disabled>
                           <i class="fas fa-lock"></i> Profil incomplet
                           </button>
                           @endif
                           @elseif($demande->status === 'pending')
                           <i class="fas fa-clock fa-3x text-warning mb-3"></i>
                           <p class="font-weight-bold text-warning">En attente de validation</p>
                           <small class="text-muted">Votre demande est en cours de traitement par
                           l'administration.</small>
                           @elseif($demande->status === 'validated')
                           <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                           <p class="font-weight-bold text-success">Demande validée</p>
                           <small class="text-muted">Un coach vous a été assigné.</small>
                           @elseif($demande->status === 'rejected')
                           <i class="fas fa-times-circle fa-3x text-danger mb-3"></i>
                           <p class="font-weight-bold text-danger">Demande rejetée</p>
                           @if ($demande->note_admin)
                           <small class="text-muted">{{ $demande->note_admin }}</small>
                           @endif
                           @endif
                        </div>
                     </div>
                  </div>
                  {{-- Informations du coach assigné --}}
                  <div class="col-md-4">
                     <div class="card">
                        <div class="card-header">
                           <h5 class="card-title mb-0">
                              <i class="fas fa-chalkboard-teacher text-info mr-2"></i>
                              Mon Coach
                           </h5>
                        </div>
                        <div class="card-body text-center">
                           @if ($candidat->candidatAssignment && $candidat->candidatAssignment->coach)
                           @php $coach = $candidat->candidatAssignment->coach; @endphp
                           @if ($coach->avatar)
                           <img src="{{ Storage::url($coach->avatar) }}"
                              style="width:65px; height:65px; border-radius:50%; object-fit:cover;"
                              class="mb-3">
                           @else
                           <i class="fas fa-user-circle fa-4x text-info mb-3"></i>
                           @endif
                           <p class="font-weight-bold mb-0">{{ $coach->name }}</p>
                           @if ($coach->coachProfile?->speciality)
                           <small class="text-muted">{{ $coach->coachProfile->speciality }}</small>
                           @endif
                           <p class="mt-2 mb-0">
                              <i class="fas fa-phone text-muted mr-1"></i>
                              <small>{{ $coach->phone ?? 'Non renseigné' }}</small>
                           </p>
                           @else
                           <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                           <p class="text-muted">Aucun coach assigné pour le moment.</p>
                           @endif
                        </div>
                     </div>
                  </div>
                  {{-- Besoin professionnel --}}
                  <div class="col-md-4">
                     <div class="card">
                        <div class="card-header">
                           <h5 class="card-title mb-0">
                              <i class="fas fa-bullseye text-success mr-2"></i>
                              Mon Besoin Professionnel
                           </h5>
                        </div>
                        <div class="card-body text-center">
                           @if ($candidat->needAssignment)
                           @php
                           $besoin = $candidat->needAssignment;
                           $labels = [
                           'stage' => [
                           'label' => 'Stage',
                           'icon' => 'fas fa-briefcase',
                           'color' => 'primary',
                           ],
                           'insertion_emploi' => [
                           'label' => 'Insertion emploi',
                           'icon' => 'fas fa-handshake',
                           'color' => 'success',
                           ],
                           'auto_emploi' => [
                           'label' => 'Auto-emploi',
                           'icon' => 'fas fa-store',
                           'color' => 'warning',
                           ],
                           'formation' => [
                           'label' => 'Formation',
                           'icon' => 'fas fa-graduation-cap',
                           'color' => 'info',
                           ],
                           ];
                           $info = $labels[$besoin->type] ?? [
                           'label' => $besoin->type,
                           'icon' => 'fas fa-question',
                           'color' => 'secondary',
                           ];
                           @endphp
                           <i class="{{ $info['icon'] }} fa-3x text-{{ $info['color'] }} mb-3"></i>
                           <p class="font-weight-bold text-{{ $info['color'] }}">{{ $info['label'] }}</p>
                           @if ($besoin->duration)
                           <small class="text-muted">
                           <i class="fas fa-clock mr-1"></i> Durée : {{ $besoin->duration }}
                           </small>
                           @endif
                           @if ($besoin->program_start_date)
                           <p class="mt-2 mb-0">
                              <small class="text-muted">
                              Du {{ $besoin->program_start_date->format('d/m/Y') }}
                              @if ($besoin->program_end_date)
                              au {{ $besoin->program_end_date->format('d/m/Y') }}
                              @endif
                              </small>
                           </p>
                           @endif
                           @else
                           <i class="fas fa-hourglass-half fa-3x text-muted mb-3"></i>
                           <p class="text-muted">Besoin non encore défini par votre coach.</p>
                           @endif
                        </div>
                     </div>
                  </div>
               </div>


            {{-- ============================================ --}}
            {{-- ALERTE PROPOSITION HORAIRES EN ATTENTE      --}}
            {{-- ============================================ --}}
            @if(isset($proposalEnAttente) && $proposalEnAttente)
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="alert mb-0"
                            style="background:#e8f5e9; border:1px solid #1cc88a;
                                border-left:5px solid #1cc88a; border-radius:6px; padding:20px;">

                            <div class="d-flex align-items-center mb-3" style="gap:14px;">
                                <div style="width:42px;height:42px;border-radius:50%;background:#1cc88a22;
                                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-calendar-check" style="color:#1cc88a; font-size:18px;"></i>
                                </div>
                                <div>
                                    <p class="mb-0 font-weight-bold" style="color:#1b5e20;">
                                        Votre coach <strong>{{ $proposalEnAttente->coachAssignment->coach->name }}</strong>
                                        vous propose 3 horaires d'entretien
                                    </p>
                                    <small style="color:#1b5e20; opacity:0.85;">
                                        Choisissez le créneau qui vous convient
                                    </small>
                                </div>
                            </div>

                            <div class="row">
                                {{-- Horaire 1 --}}
                                <div class="col-md-4 mb-2">
                                    <div class="card h-100" style="border:2px solid #006b08;">
                                        <div class="card-body text-center py-3">
                                            <p class="font-weight-bold text-success mb-1">
                                                <i class="fas fa-calendar-day mr-1"></i> Horaire 1
                                            </p>
                                            <p class="mb-1">
                                                <strong>
                                                    {{ \Carbon\Carbon::parse($proposalEnAttente->date_1)->format('d/m/Y') }}
                                                </strong>
                                            </p>
                                            <p class="mb-3 text-muted">
                                                {{ \Carbon\Carbon::parse($proposalEnAttente->heure_1)->format('H:i') }}
                                            </p>
                                            <a href="{{ route('candidat.appointments.confirm', [$proposalEnAttente->id, 1]) }}"
                                                class="btn btn-success btn-sm btn-block">
                                                <i class="fas fa-check mr-1"></i> Choisir
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                {{-- Horaire 2 --}}
                                <div class="col-md-4 mb-2">
                                    <div class="card h-100" style="border:2px solid #17a2b8;">
                                        <div class="card-body text-center py-3">
                                            <p class="font-weight-bold text-info mb-1">
                                                <i class="fas fa-calendar-day mr-1"></i> Horaire 2
                                            </p>
                                            <p class="mb-1">
                                                <strong>
                                                    {{ \Carbon\Carbon::parse($proposalEnAttente->date_2)->format('d/m/Y') }}
                                                </strong>
                                            </p>
                                            <p class="mb-3 text-muted">
                                                {{ \Carbon\Carbon::parse($proposalEnAttente->heure_2)->format('H:i') }}
                                            </p>
                                            <a href="{{ route('candidat.appointments.confirm', [$proposalEnAttente->id, 2]) }}"
                                                class="btn btn-info btn-sm btn-block">
                                                <i class="fas fa-check mr-1"></i> Choisir
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                {{-- Horaire 3 --}}
                                <div class="col-md-4 mb-2">
                                    <div class="card h-100" style="border:2px solid #f4a900;">
                                        <div class="card-body text-center py-3">
                                            <p class="font-weight-bold text-warning mb-1">
                                                <i class="fas fa-calendar-day mr-1"></i> Horaire 3
                                            </p>
                                            <p class="mb-1">
                                                <strong>
                                                    {{ \Carbon\Carbon::parse($proposalEnAttente->date_3)->format('d/m/Y') }}
                                                </strong>
                                            </p>
                                            <p class="mb-3 text-muted">
                                                {{ \Carbon\Carbon::parse($proposalEnAttente->heure_3)->format('H:i') }}
                                            </p>
                                            <a href="{{ route('candidat.appointments.confirm', [$proposalEnAttente->id, 3]) }}"
                                                class="btn btn-warning btn-sm btn-block">
                                                <i class="fas fa-check mr-1"></i> Choisir
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Mode d'entretien --}}
                            <div class="mt-3 p-2" style="background:#fff; border-radius:6px;">
                                <small class="font-weight-bold">
                                    <i class="fas fa-info-circle mr-1 text-success"></i>
                                    Mode d'entretien :
                                </small>
                                @if($proposalEnAttente->mode === 'presentiel')
                                    <small>📍 Présentiel — {{ $proposalEnAttente->location ?? 'Lieu à confirmer' }}</small>
                                @else
                                    <small>
                                        @if($proposalEnAttente->plateforme_enligne === 'whatsapp')
                                            📱 WhatsApp — {{ $proposalEnAttente->numero_whatsapp }}
                                        @elseif($proposalEnAttente->plateforme_enligne === 'appel_direct')
                                            📞 Appel direct — {{ $proposalEnAttente->numero_appel }}
                                        @elseif($proposalEnAttente->plateforme_enligne === 'google_meet')
                                            🎥 Google Meet — Le lien vous sera envoyé après confirmation
                                        @endif
                                    </small>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            @endif

               {{-- ===== BLOC ENTRETIEN ===== --}}
               <div class="row">
                  <div class="col-12">
                     <div class="card">
                        <div class="card-header">
                           <h5 class="card-title mb-0">
                              <i class="fas fa-handshake text-warning mr-2"></i> Mon Entretien
                           </h5>
                        </div>
                        <div class="card-body">
                           @if (!$entretien && !$interview)
                           <div class="text-center py-3">
                              <i class="fas fa-calendar fa-2x text-muted mb-2 d-block"></i>
                              <p class="text-muted mb-0">Aucun entretien programmé pour le moment.</p>
                           </div>
                           @elseif($entretien && !$interview)
                           {{-- Entretien programmé mais pas encore passé --}}
                           <div class="row align-items-center">
                              <div class="col-md-12">
                                 <div
                                    style="background:#f0f9f0; border-left:4px solid #006b08;
                                    border-radius:4px; padding:15px 20px;">
                                    <p class="mb-2 font-weight-bold">
                                       <i class="fas fa-calendar-check text-success mr-2"></i>
                                       Entretien programmé
                                    </p>
                                    <p class="mb-1">
                                       <i class="fas fa-calendar mr-2 text-muted"></i>
                                       <strong>{{ \Carbon\Carbon::parse($entretien->scheduled_date)->format('d/m/Y') }}</strong>
                                       à
                                       <strong>{{ \Carbon\Carbon::parse($entretien->scheduled_time)->format('H:i') }}</strong>
                                    </p>
                                    <p class="mb-1">
                                       <i class="fas fa-map-marker-alt mr-2 text-muted"></i>
                                       @if ($entretien->mode === 'presentiel')
                                       <span class="badge badge-primary mr-1">Présentiel</span>
                                       {{ $entretien->location }}
                                       @else
                                       <span class="badge badge-info mr-1">En ligne</span>
                                       @if ($entretien->meeting_link)
                                       <a href="{{ $entretien->meeting_link }}" target="_blank"
                                          class="btn btn-sm btn-outline-info ml-1">
                                       <i class="fas fa-external-link-alt mr-1"></i> Rejoindre
                                       </a>
                                       @endif
                                       @endif
                                    </p>
                                    <p class="mb-0">
                                       <i class="fas fa-chalkboard-teacher mr-2 text-muted"></i>
                                       Avec Coach
                                       <strong>{{ $candidat->candidatAssignment?->coach?->name ?? 'votre coach' }}</strong>
                                    <p>
                                       <small>{{ $candidat->candidatAssignment?->coach?->phone ?? 'Aucun numéro renseigné' }}
                                       / </small>
                                       <small>{{ $candidat->candidatAssignment?->coach?->email ?? 'Aucun email renseigné' }}</small>
                                    </p>
                                    </p>
                                 </div>
                              </div>
                           </div>
                           @elseif($interview)
                           @php
                           $noteFinale = round($interview->total_score / 5);
                           $scoreColor =
                           $noteFinale >= 16
                           ? '#1cc88a'
                           : ($noteFinale >= 12
                           ? '#36b9cc'
                           : ($noteFinale >= 8
                           ? '#f6c23e'
                           : '#e74a3b'));
                           $scoreLabel =
                           $noteFinale >= 16
                           ? 'Excellent'
                           : ($noteFinale >= 12
                           ? 'Bien'
                           : ($noteFinale >= 8
                           ? 'Moyen'
                           : 'À améliorer'));
                           $orientation = match (true) {
                           $noteFinale <= 9 => 'Renforcement compétences',
                           $noteFinale <= 14 => 'Stage / immersion',
                           $noteFinale <= 17 => 'Insertion accompagnée',
                           default => 'Insertion rapide / autonomie',
                           };
                           $blocColors = ['#006b08', '#4e73df', '#1cc88a', '#f6c23e', '#e74a3b'];
                           @endphp
                           <div class="row align-items-center">
                              {{-- Cercle score --}}
                              <div class="col-md-3 text-center">
                                 <div
                                    style="width:100px; height:100px; border-radius:50%; margin:0 auto;   background:{{ $scoreColor }}; display:flex; flex-direction:column;  align-items:center; justify-content:center;">
                                    <span
                                       style="color:#fff; font-size:22px; font-weight:bold; line-height:1.1;">
                                    {{ $noteFinale }}/20
                                    </span>
                                    <small style="color:rgba(255,255,255,0.85); font-size:10px;">
                                    {{ $interview->total_score }}/100
                                    </small>
                                 </div>
                                 <p class="mt-2 mb-0 font-weight-bold" style="color:{{ $scoreColor }};">
                                    {{ $scoreLabel }}
                                 </p>
                                 <small class="text-muted d-block">Score global</small>
                                 <div class="mt-3 p-2 rounded text-center"
                                    style="background:{{ $scoreColor }}18; border:1px solid {{ $scoreColor }}; font-size:11px;">
                                    <i class="fas fa-compass mr-1"
                                       style="color:{{ $scoreColor }};"></i>
                                    <strong>Orientation :</strong><br>
                                    <span
                                       style="color:{{ $scoreColor }}; font-weight:bold;">{{ $orientation }}</span>
                                 </div>
                              </div>
                              {{-- Barres par bloc --}}
                              <div class="col-md-9">
                                 @foreach ($interview->scores->sortBy('competence.order') as $index => $score)
                                 @php
                                 $color = $blocColors[$index] ?? '#006b08';
                                 $pct = ($score->note / 20) * 100;
                                 @endphp
                                 <div class="mb-3">
                                    <div
                                       class="d-flex justify-content-between align-items-center mb-1">
                                       <small class="font-weight-bold" style="font-size:12px;">
                                       {{ $score->competence->name }}
                                       </small>
                                       <small class="font-weight-bold"
                                          style="color:{{ $color }};">
                                       {{ $score->note }}/20
                                       ({{ round($pct) }}%)
                                       </small>
                                    </div>
                                    <div class="progress"
                                       style="height:12px; border-radius:8px; background:#e9ecef;">
                                       <div class="progress-bar" role="progressbar"
                                          style="width:{{ $pct }}%; background:{{ $color }}; border-radius:8px; transition:width 1s ease;">
                                       </div>
                                    </div>
                                 </div>
                                 @endforeach
                                 <hr class="my-2">
                                 <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">Total général</small>
                                    <strong>{{ $interview->total_score }}/100</strong>
                                 </div>
                                 <div class="d-flex justify-content-between align-items-center mt-1">
                                    <small class="text-muted">Note finale (/20)</small>
                                    <strong
                                       style="font-size:18px; color:{{ $scoreColor }};">{{ $noteFinale }}/20</strong>
                                 </div>
                                 @if ($interview->strengths || $interview->weaknesses)
                                 <div class="row mt-3">
                                    @if ($interview->strengths)
                                    <div class="col-md-6">
                                       <small class="font-weight-bold text-success">
                                       <i class="fas fa-plus-circle mr-1"></i> Points forts
                                       </small>
                                       <p class="text-muted mt-1"
                                          style="font-size:11px; line-height:1.5;">
                                          {{ $interview->strengths }}
                                       </p>
                                    </div>
                                    @endif
                                    @if ($interview->weaknesses)
                                    <div class="col-md-6">
                                       <small class="font-weight-bold text-danger">
                                       <i class="fas fa-minus-circle mr-1"></i> Points à
                                       améliorer
                                       </small>
                                       <p class="text-muted mt-1"
                                          style="font-size:11px; line-height:1.5;">
                                          {{ $interview->weaknesses }}
                                       </p>
                                    </div>
                                    @endif
                                 </div>
                                 @endif
                              </div>
                           </div>
                           @endif
                        </div>
                     </div>
                  </div>
               </div>
               {{-- ===== BLOC PROJET PROFESSIONNEL ===== --}}
               <div class="row">
                  <div class="col-12">
                     <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                           <h5 class="card-title mb-0">
                              <i class="fas fa-project-diagram text-info mr-2"></i> Mon Projet Professionnel
                           </h5>
                           {{-- @if (auth()->user()->professionalProject)
                           <a href="{{ route('candidat.projects.pdf') }}"
                              class="btn btn-sm btn-danger d-none d-md-inline-block" target="_blank">
                           <i class="fas fa-file-pdf mr-1"></i> Exporter en PDF
                           </a>
                           @endif --}}
                        </div>
                        <div class="card-body">
                           @if (!auth()->user()->professionalProject)
                           {{-- Aucun projet --}}
                           <div class="text-center py-3">
                              <i class="fas fa-project-diagram fa-2x text-muted mb-2 d-block"></i>
                              <p class="text-muted mb-0">
                                 Votre projet professionnel n'a pas encore été défini.
                              </p>
                              <small class="text-muted">
                              Votre coach s'en chargera après votre entretien de diagnostic.
                              </small>
                           </div>
                           @else
                           @php $projet = auth()->user()->professionalProject; @endphp
                           <div class="row">
                              <div class="col-md-4">
                                 <small class="text-muted">Titre du projet</small>
                                 <p class="font-weight-bold mb-2">
                                    {{ $projet->titre_projet }}
                                 </p>
                              </div>
                              <div class="col-md-4">
                                 <small class="text-muted">Secteur cible</small>
                                 <p class="font-weight-bold mb-2">
                                    {{ $projet->secteur_cible ?? '—' }}
                                 </p>
                              </div>
                              <div class="col-md-4">
                                 <small class="text-muted">Poste visé</small>
                                 <p class="font-weight-bold mb-2">
                                    {{ $projet->poste_vise ?? '—' }}
                                 </p>
                              </div>
                              @if ($projet->description)
                              <div class="col-12">
                                 <small class="text-muted">Description</small>
                                 <p class="mb-2">
                                    {{ $projet->description }}
                                 </p>
                              </div>
                              @endif
                              @if ($projet->objectif_court_terme)
                              <div class="col-md-6">
                                 <small class="text-muted">
                                 <i class="fas fa-flag text-warning mr-1"></i>
                                 Objectif court terme
                                 </small>
                                 <p class="mb-0">
                                    {{ $projet->objectif_court_terme }}
                                 </p>
                              </div>
                              @endif
                              @if ($projet->objectif_long_terme)
                              <div class="col-md-6">
                                 <small class="text-muted">
                                 <i class="fas fa-flag-checkered text-success mr-1"></i>
                                 Objectif long terme
                                 </small>
                                 <p class="mb-0">
                                    {{ $projet->objectif_long_terme }}
                                 </p>
                              </div>
                              @endif
                           </div>
                           <div class="mt-3" style="font-size:12px; color:#aaa;">
                              <i class="fas fa-calendar-alt mr-1"></i>
                              Enregistré le {{ $projet->created_at->format('d/m/Y') }}
                              @if ($projet->updated_at->ne($projet->created_at))
                              — <small>
                              Modifié le {{ $projet->updated_at->format('d/m/Y') }}
                              </small>
                              @endif
                           </div>
                           @endif
                        </div>
                     </div>
                  </div>
               </div>
               {{-- Mon Parcours --}}
               <div class="row">
                  <div class="col-12">
                     <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                           <h5 class="card-title mb-0">
                              <i class="fas fa-chart-line text-primary mr-2"></i>
                              Mon Parcours
                           </h5>
                           <small class="text-muted">
                           {{ $steps->where('status', 'completed')->count() }} /
                           {{ $steps->count() }} étapes terminées
                           </small>
                        </div>
                        <div class="card-body">
                           @if ($steps->isEmpty())
                           <div class="text-center py-4">
                              <i class="fas fa-road fa-3x text-muted mb-3"></i>
                              <p class="text-muted">Votre parcours de suivi n'a pas encore commencé.</p>
                           </div>
                           @else
                           <div class="timeline">
                              @foreach ($steps as $step)
                              <div class="timeline-item d-flex mb-4">
                                 {{-- Icône statut --}}
                                 <div class="timeline-icon mr-3" style="flex-shrink:0;">
                                    @if ($step->status === 'completed')
                                    <div
                                       style="width:40px; height:40px; border-radius:50%; background:#1cc88a; display:flex; align-items:center; justify-content:center;">
                                       <i class="fas fa-check text-white"></i>
                                    </div>
                                    @else
                                    <div
                                       style="width:40px; height:40px; border-radius:50%; background:#4e73df; display:flex; align-items:center; justify-content:center;">
                                       <i class="fas fa-spinner text-white"></i>
                                    </div>
                                    @endif
                                 </div>
                                 {{-- Contenu --}}
                                 <div class="timeline-content flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                       <h6 class="mb-1 font-weight-bold">{{ $step->title }}</h6>
                                       <span
                                          class="badge badge-{{ $step->status === 'completed' ? 'success' : 'primary' }}">
                                       {{ $step->status === 'completed' ? 'Terminé' : 'En cours' }}
                                       </span>
                                    </div>
                                    @if ($step->description)
                                    <p class="text-muted mb-1" style="font-size:13px;">
                                       {{ $step->description }}
                                    </p>
                                    @endif
                                    @if ($step->result)
                                    <p class="mb-1" style="font-size:13px;">
                                       <i class="fas fa-comment-alt text-info mr-1"></i>
                                       <em>{{ $step->result }}</em>
                                    </p>
                                    @endif
                                    <small class="text-muted">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ $step->created_at->format('d/m/Y') }}
                                    @if ($step->completed_date)
                                    → Terminé le
                                    {{ \Carbon\Carbon::parse($step->completed_date)->format('d/m/Y') }}
                                    @endif
                                    </small>
                                 </div>
                              </div>
                              @endforeach
                           </div>
                           @endif
                        </div>
                     </div>
                  </div>
               </div>
               {{-- GRAPHIQUES --}}
               {{-- @if ($interview || $compteurs['steps_total'] > 0)
               <div class="row mt-4">
                  @if ($interview && count($radarChart['labels']) > 0)
                  <div class="col-md-6 mb-4">
                     <div class="card h-100">
                        <div class="card-header">
                           <h6 class="mb-0 font-weight-bold">
                              <i class="fas fa-chart-area mr-2 text-primary"></i> Mes compétences
                           </h6>
                        </div>
                        <div class="card-body d-flex justify-content-center align-items-center">
                           <canvas id="radarChart" style="max-height:260px;"></canvas>
                        </div>
                     </div>
                  </div>
                  @endif
                  @if ($compteurs['steps_total'] > 0)
                  <div class="col-md-6 mb-4">
                     <div class="card h-100">
                        <div class="card-header">
                           <h6 class="mb-0 font-weight-bold">
                              <i class="fas fa-tasks mr-2 text-warning"></i> Progression de mon parcours
                           </h6>
                        </div>
                        <div class="card-body d-flex justify-content-center align-items-center">
                           <canvas id="parcourChart" style="max-height:260px;"></canvas>
                        </div>
                     </div>
                  </div>
                  @endif
               </div>
               @endif --}}
            </div>
         </div>
      </div>
      <!-- #/ container -->
      </div>
      <!-- #/ content body -->
      <!-- footer -->
      <!-- #/ footer -->
      </div>
      {{-- Chart.js --}}
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      <script>
         @if ($interview && count($radarChart['labels']) > 0)
             new Chart(document.getElementById('radarChart'), {
                 type: 'radar',
                 data: {
                     labels: @json($radarChart['labels']),
                     datasets: [{
                         label: 'Score /20',
                         data: @json($radarChart['data']),
                         backgroundColor: 'rgba(0, 107, 8, 0.15)',
                         borderColor: '#006b08',
                         pointBackgroundColor: '#006b08',
                         pointRadius: 4,
                         borderWidth: 2,
                     }]
                 },
                 options: {
                     responsive: true,
                     scales: {
                         r: {
                             min: 0,
                             max: 20,
                             ticks: {
                                 stepSize: 5,
                                 font: {
                                     size: 10
                                 }
                             },
                             pointLabels: {
                                 font: {
                                     size: 11
                                 }
                             },
                             grid: {
                                 color: '#e0e0e0'
                             },
                         }
                     },
                     plugins: {
                         legend: {
                             display: false
                         }
                     }
                 }
             });
         @endif
         
         @if ($compteurs['steps_total'] > 0)
             new Chart(document.getElementById('parcourChart'), {
                 type: 'doughnut',
                 data: {
                     labels: @json($parcourChart['labels']),
                     datasets: [{
                         data: @json($parcourChart['data']),
                         backgroundColor: ['#1cc88a', '#f6c23e'],
                         borderWidth: 2,
                     }]
                 },
                 options: {
                     responsive: true,
                     plugins: {
                         legend: {
                             position: 'bottom',
                             labels: {
                                 font: {
                                     size: 11
                                 }
                             }
                         }
                     }
                 }
             });
         @endif
      </script>
      @include('section.footer')
      @include('section.foot')
      {{-- Rouvrir le modal si erreur de validation --}}
      @if ($errors->has('parcours_professionnel'))
      <script>
         document.addEventListener('DOMContentLoaded', function() {
             $('#modalDiagnostic').modal('show');
         });
      </script>
      @endif
      <script>
        function toggleModeEntretien(mode) {
            document.getElementById('bloc_en_ligne').style.display =
                mode === 'en_ligne' ? 'block' : 'none';

            // Réinitialiser les sous-champs
            if (mode !== 'en_ligne') {
                document.querySelectorAll('input[name="plateforme_enligne"]')
                    .forEach(r => r.checked = false);
                togglePlateforme(null);
            }
        }

        function togglePlateforme(plateforme) {
            document.getElementById('bloc_whatsapp').style.display =
                plateforme === 'whatsapp'    ? 'block' : 'none';
            document.getElementById('bloc_appel').style.display =
                plateforme === 'appel_direct' ? 'block' : 'none';
            document.getElementById('bloc_meet').style.display =
                plateforme === 'google_meet'  ? 'block' : 'none';
        }

        // Au chargement si old values existent
        document.addEventListener('DOMContentLoaded', function () {
            const modeChecked = document.querySelector('input[name="mode_entretien"]:checked');
            if (modeChecked) toggleModeEntretien(modeChecked.value);

            const platChecked = document.querySelector('input[name="plateforme_enligne"]:checked');
            if (platChecked) togglePlateforme(platChecked.value);
        });
    </script>
   </body>
</html>