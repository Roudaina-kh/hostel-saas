<header class="px-8 py-5 flex items-center justify-between relative z-20">

    {{-- HOSTEL SWITCHER --}}
    <div class="flex items-center gap-3">
        @if(isset($ownerHostels) && $ownerHostels->count() > 1)
            <form method="POST" id="hostel-switch-form" class="m-0">
                @csrf
                <div class="relative">
                    <select name="hostel_id" onchange="switchHostel(this.value)"
                            class="appearance-none cursor-pointer pl-4 pr-10 py-2.5 rounded-xl text-[14px] font-bold text-[#1A2B3C] border border-[#E8EEF2] shadow-sm outline-none transition-all duration-200"
                            style="background: rgba(255,255,255,0.7); backdrop-filter: blur(12px);"
                            onfocus="this.style.borderColor='#2C6E8A';this.style.background='#FFFFFF';this.style.boxShadow='0 0 0 4px rgba(44,110,138,0.09)'"
                            onblur="this.style.borderColor='#E8EEF2';this.style.background='rgba(255,255,255,0.7)';this.style.boxShadow='0 1px 2px 0 rgba(0,0,0,0.05)'">
                        @foreach($ownerHostels as $h)
                            <option value="{{ $h->id }}" {{ $h->id == $activeHostel->id ? 'selected' : '' }}>
                                🏨 {{ $h->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-[#8A9BB0]">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </form>
        @else
            <div class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-[#E8EEF2] shadow-sm" style="background: rgba(255,255,255,0.7); backdrop-filter: blur(12px);">
                <span class="text-sm">🏨</span>
                <span class="text-[14px] font-bold text-[#1A2B3C]">{{ isset($activeHostel) ? $activeHostel->name : '' }}</span>
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