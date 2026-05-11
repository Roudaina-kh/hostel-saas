{{--
    Header — palette terra/sand cohérente avec la page d'accueil
    La logique JS (switchHostel) est strictement identique à l'original.
--}}

<header style="
    padding: 1.25rem 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    z-index: 20;
    background: rgba(254, 252, 249, 0.7);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-bottom: 1px solid var(--border, #DDD6CA);
">

    {{-- HOSTEL SWITCHER --}}
    <div style="display: flex; align-items: center; gap: 12px;">
        @if(isset($ownerHostels) && $ownerHostels->count() > 1)
            <form method="POST" id="hostel-switch-form" style="margin:0">
                @csrf
                <div style="position: relative; display: flex; align-items: center;">
                    <select name="hostel_id" onchange="switchHostel(this.value)"
                            style="
                                appearance: none;
                                cursor: pointer;
                                padding: 10px 40px 10px 16px;
                                border-radius: 12px;
                                border: 1.5px solid var(--border, #DDD6CA);
                                background: var(--white, #FEFCF9);
                                color: var(--terra, #C8602A);
                                font-family: 'DM Sans', sans-serif;
                                font-size: 13.5px;
                                font-weight: 600;
                                outline: none;
                                box-shadow: 0 1px 3px rgba(28,28,36,0.04);
                                transition: all 0.2s ease;
                            "
                            onfocus="this.style.borderColor='var(--terra, #C8602A)'; this.style.boxShadow='0 0 0 4px rgba(200,96,42,0.12)';"
                            onblur="this.style.borderColor='var(--border, #DDD6CA)'; this.style.boxShadow='0 1px 3px rgba(28,28,36,0.04)';">
                        @foreach($ownerHostels as $h)
                            <option value="{{ $h->id }}" {{ $h->id == $activeHostel->id ? 'selected' : '' }}>
                                🏨 {{ $h->name }}
                            </option>
                        @endforeach
                    </select>
                    <div style="
                        position: absolute;
                        right: 14px;
                        top: 50%;
                        transform: translateY(-50%);
                        pointer-events: none;
                        color: var(--terra, #C8602A);
                    ">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </form>
        @else
            @if(isset($activeHostel))
            <div style="
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 10px 18px;
                border-radius: 12px;
                border: 1.5px solid var(--border, #DDD6CA);
                background: var(--white, #FEFCF9);
                box-shadow: 0 1px 3px rgba(28,28,36,0.04);
            ">
                <span style="font-size: 14px;">🏨</span>
                <span style="
                    font-family: 'DM Sans', sans-serif;
                    font-size: 13.5px;
                    font-weight: 600;
                    color: var(--terra, #C8602A);
                ">{{ $activeHostel->name }}</span>
            </div>
            @endif
        @endif
    </div>

    {{-- Right side — placeholder pour usages futurs --}}
    <div style="display: flex; align-items: center; gap: 1.5rem;">
        {{-- Logout déjà dans la sidebar — on garde l'espace pour notifications/recherche futures --}}
    </div>
</header>

<script>
function switchHostel(hostelId) {
    Swal.fire({
        title: 'Changer de hostel ?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Oui, basculer',
        cancelButtonText: 'Annuler',
        background: '#FEFCF9',
        color: '#2E2E3A',
        confirmButtonColor: '#C8602A',
        cancelButtonColor: '#A0A0B0',
    }).then(result => {
        if (result.isConfirmed) {
            fetch(`/hostel/switch/${hostelId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            }).then(() => window.location.href = '/dashboard');
        }
    });
}
</script>