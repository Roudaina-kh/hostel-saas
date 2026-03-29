@extends('layouts.app')
@section('title', 'Taxes (Manager)')
@section('content')

<div class="flex items-center justify-between mb-8 fade-up text-[#1A2B3C]">
    <div>
        <h1 class="text-3xl font-extrabold tracking-tight">Paramètres de taxes</h1>
        <p class="text-[15px] font-medium text-[#8A9BB0] mt-1">Configurez les taxes applicables à toutes les réservations de votre hostel (Manager).</p>
    </div>
</div>

<div class="glass-table p-8 shadow-sm fade-up delay-1 max-w-3xl">

    <form method="POST" action="{{ route('manager.taxes.update') }}" class="space-y-8">
        @csrf
        @method('PUT')

        {{-- Toggle taxes actives --}}
        <div class="flex items-center justify-between p-6 rounded-2xl relative overflow-hidden"
             style="background: linear-gradient(135deg, #EFF6FF, #E0F2FE); border: 1.5px solid #BAE6FD;">
            <div class="relative z-10">
                <p class="text-[17px] font-black text-[#1E293B] tracking-tight">Activer la collecte de taxes</p>
                <p class="text-[14px] font-medium text-[#475569] mt-1">Si activé, les taxes ci-dessous seront calculées automatiquement lors des réservations.</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer z-10 shrink-0">
                <input type="checkbox" name="taxes_enabled" value="1" id="taxes_toggle"
                       {{ $tax->taxes_enabled ? 'checked' : '' }}
                       class="sr-only peer">
                <div class="w-14 h-8 rounded-full peer transition-all duration-300 shadow-inner"
                     style="background: {{ $tax->taxes_enabled ? '#3B82F6' : '#CBD5E1' }};"
                     id="toggle-bg"
                     onclick="const chk = document.getElementById('taxes_toggle'); setTimeout(() => { this.style.background = chk.checked ? '#3B82F6' : '#CBD5E1'; }, 50)">
                </div>
                <div class="absolute left-1 top-1 w-6 h-6 bg-white rounded-full transition-transform duration-300 peer-checked:translate-x-6 shadow-md"></div>
            </label>
        </div>

        @php 
            $input = "w-full rounded-xl px-4 py-3.5 text-[15px] font-bold outline-none transition-all duration-300 border border-white/60 bg-white/60 shadow-inner text-[#0F172A]"; 
            $focus = "this.style.borderColor='#3B82F6';this.style.background='#FFFFFF';this.style.boxShadow='0 0 0 4px rgba(59,130,246,0.15)'";
            $blur = "this.style.borderColor='rgba(255,255,255,0.6)';this.style.background='rgba(255,255,255,0.6)';this.style.boxShadow='inset 0 2px 4px 0 rgba(0, 0, 0, 0.02)'";
            
            $boxClass = "p-6 rounded-2xl border-2 border-[#BAE6FD] bg-gradient-to-br from-[#F0F9FF] to-[#E0F2FE] transition-all duration-300 hover:-translate-y-1.5 hover:shadow-lg hover:border-[#7DD3FC] group relative overflow-hidden";
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="{{ $boxClass }}">
                <label class="block text-[14px] font-black text-[#1E3A8A] mb-4 uppercase tracking-wider relative z-10">TVA (%)</label>
                <div class="relative z-10">
                    <input type="number" name="vat_percentage" step="0.01" min="0" max="100"
                           value="{{ old('vat_percentage', $tax->vat_percentage) }}"
                           class="{{ $input }}"
                           onfocus="{!! $focus !!}" onblur="{!! $blur !!}">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[#3B82F6] font-black pointer-events-none text-lg">%</span>
                </div>
            </div>
            <div class="{{ $boxClass }}">
                <label class="block text-[14px] font-black text-[#1E3A8A] mb-4 uppercase tracking-wider relative z-10">Frais de service (%)</label>
                <div class="relative z-10">
                    <input type="number" name="service_fee_percentage" step="0.01" min="0" max="100"
                           value="{{ old('service_fee_percentage', $tax->service_fee_percentage) }}"
                           class="{{ $input }}"
                           onfocus="{!! $focus !!}" onblur="{!! $blur !!}">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[#3B82F6] font-black pointer-events-none text-lg">%</span>
                </div>
            </div>
            <div class="{{ $boxClass }}">
                <label class="block text-[14px] font-black text-[#1E3A8A] mb-4 uppercase tracking-wider relative z-10">Taxe de séjour / nuit</label>
                <div class="relative z-10">
                    <input type="number" name="city_tax_per_night" step="0.001" min="0"
                           value="{{ old('city_tax_per_night', $tax->city_tax_per_night) }}"
                           class="{{ $input }} pl-12"
                           onfocus="{!! $focus !!}" onblur="{!! $blur !!}">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#3B82F6] font-bold pointer-events-none text-lg">💲</span>
                </div>
            </div>
            <div class="{{ $boxClass }}">
                <label class="block text-[14px] font-black text-[#1E3A8A] mb-4 uppercase tracking-wider relative z-10">Taxe / personne / nuit</label>
                <div class="relative z-10">
                    <input type="number" name="per_person_tax_per_night" step="0.001" min="0"
                           value="{{ old('per_person_tax_per_night', $tax->per_person_tax_per_night) }}"
                           class="{{ $input }} pl-12"
                           onfocus="{!! $focus !!}" onblur="{!! $blur !!}">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#3B82F6] font-bold pointer-events-none text-lg">🧑</span>
                </div>
            </div>
        </div>

        <div class="mt-8 flex items-center gap-5 p-6 rounded-2xl transition-all duration-300 cursor-pointer border-2 border-[#BAE6FD] bg-gradient-to-br from-[#F0F9FF] to-[#E0F2FE] hover:-translate-y-1 hover:shadow-lg hover:border-[#7DD3FC]" 
             onclick="document.getElementById('extras_taxable').click();">
            <input type="checkbox" name="extras_taxable" value="1" id="extras_taxable"
                   {{ $tax->extras_taxable ? 'checked' : '' }}
                   class="w-5 h-5 rounded border-[#CBD5E1] text-[#3B82F6] shadow-sm focus:ring-[#3B82F6] cursor-pointer"
                   onclick="event.stopPropagation()">
            <div>
                <label for="extras_taxable" class="text-[15px] font-extrabold text-[#0F172A] cursor-pointer" onclick="event.preventDefault()">
                    Appliquer les taxes sur les services extras
                </label>
            </div>
        </div>

        <button type="submit" class="btn-blue w-full py-4 text-[16px] mt-4">
            Sauvegarder les paramètres (Manager)
        </button>
    </form>
</div>
@endsection
