{{-- resources/views/search/_hostel_card.blade.php --}}
@php
    $minPrice = $hostel->prices->min('price_ttc');
    $typeLabel = match($hostel->type ?? 'hostel') {
        'camping' => '🏕 Camping',
        'mixed'   => '🌿 Mixte',
        default   => '🏨 Hostel',
    };

    /*
     * ─────────────────────────────────────────────────────────────────────
     *  Résolution intelligente du chemin de l'image de couverture.
     * ─────────────────────────────────────────────────────────────────────
     *  HostelFlow stocke les images dans 2 emplacements possibles :
     *
     *    1) public/images/...
     *       → assets statiques semés par DemoHostelImagesSeeder
     *       → cover_image en BD : "images/Tabarka.jpg"
     *       → URL finale : asset("images/Tabarka.jpg")
     *
     *    2) storage/app/public/uploads/...
     *       → uploads dynamiques depuis le formulaire owner
     *       → cover_image en BD : "uploads/abc123.jpg"
     *       → URL finale : asset("storage/uploads/abc123.jpg")
     *         (via le symlink créé par `php artisan storage:link`)
     *
     *  Détection : si le chemin commence par "images/" → asset public,
     *              sinon → storage symlink.
     *
     *  Pattern PFE : "Storage Resolution Strategy" — la vue est agnostique
     *  de la source des assets, ce qui permet de mixer seeds et uploads
     *  sans dupliquer la logique d'affichage.
     */
    $coverUrl = null;
    if ($hostel->cover_image) {
        $coverUrl = str_starts_with($hostel->cover_image, 'images/')
            ? asset($hostel->cover_image)
            : asset('storage/' . $hostel->cover_image);
    }
@endphp

<div class="hostel-card">
    <div class="card-img">
        @if($coverUrl)
            <img src="{{ $coverUrl }}" alt="{{ $hostel->name }}"
                 onerror="this.parentElement.innerHTML='<div class=\'card-placeholder\'>🏨</div>'">
        @else
            <div class="card-placeholder">
                {{ $hostel->type === 'camping' ? '🏕' : '🏨' }}
            </div>
        @endif
        <div class="card-badge {{ $hostel->rating >= 4.5 ? 'teal' : '' }}">
            @if($hostel->rating >= 4.5) ⭐ Top noté
            @elseif($hostel->total_reviews === 0) 🆕 Nouveau
            @else 🔥 Populaire
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="card-region">
            {{ $hostel->region?->name ?? ($hostel->city ?? 'Tunisie') }}
        </div>
        <div class="card-name">{{ $hostel->name }}</div>
        <div class="card-meta">
            @if($hostel->rating > 0)
            <div class="card-rating">
                <span class="star">★</span>
                {{ number_format($hostel->rating, 1) }}
                @if($hostel->total_reviews > 0)
                    <span style="color:var(--lgray);font-weight:400">({{ $hostel->total_reviews }})</span>
                @endif
            </div>
            @endif
            <span class="card-type">{{ $typeLabel }}</span>
        </div>
        <div class="card-footer">
            @if($minPrice)
                <div class="card-price">
                    {{ number_format($minPrice, 0) }} TND
                    <span>/ nuit</span>
                </div>
            @else
                <div class="card-no-price">Prix sur demande</div>
            @endif
            <a href="{{ route('search.show', $hostel->id) }}" class="btn-book">Voir →</a>
        </div>
    </div>
</div>