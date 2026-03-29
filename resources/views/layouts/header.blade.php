<header class="px-8 py-5 flex items-center justify-between relative z-20">

    {{-- HOSTEL SWITCHER --}}
    <div class="flex items-center gap-3">
        @if(isset($ownerHostels) && $ownerHostels->count() > 1)
            <form method="POST" id="hostel-switch-form" class="m-0">
                @csrf
                <div class="relative flex items-center">
                    <select name="hostel_id" onchange="switchHostel(this.value)"
                            class="appearance-none cursor-pointer pl-4 pr-10 py-2.5 rounded-xl border border-[#E8EEF2] shadow-sm outline-none transition-all duration-200"
                            style="background-color: #FDFAF5; color: #2563EB; font-size: 13.5px; font-weight: 500;"
                            onfocus="this.style.borderColor='#93C5FD';this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.15)'"
                            onblur="this.style.borderColor='#E8EEF2';this.style.boxShadow='0 1px 2px 0 rgba(0,0,0,0.05)'">
                        @foreach($ownerHostels as $h)
                            <option value="{{ $h->id }}" {{ $h->id == $activeHostel->id ? 'selected' : '' }}>
                                {{ $h->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" style="color: #2563EB; opacity: 0.8;">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </form>
        @else
            <div class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-[#E8EEF2] shadow-sm" style="background-color: #FDFAF5;">
                <span style="font-size: 13.5px; font-weight: 500; color: #2563EB;">{{ isset($activeHostel) ? $activeHostel->name : '' }}</span>
            </div>
        @endif
    </div>

    {{-- PROFIL + LOGOUT --}}
    <div class="flex items-center gap-6">
        {{-- Empty space since logout moved to sidebar --}}
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