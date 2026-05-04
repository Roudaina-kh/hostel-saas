{{-- Partial : calendrier annuel --}}
{{-- Variables requises : $year, $calendarDays --}}
<div id="calendar-section" class="bg-white border border-gray-200 rounded-xl shadow-sm mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-5 py-3 border-b border-gray-100 bg-gray-50 rounded-t-xl">
        <span class="font-semibold text-gray-800 flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Calendrier {{ $year }}
        </span>
        <div class="flex items-center gap-4 text-xs font-medium">
            <span class="flex items-center gap-1.5">
                <span class="w-4 h-4 rounded-sm inline-block" style="background:#f87171"></span>Confirmé
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-4 h-4 rounded-sm inline-block" style="background:#34d399"></span>En attente
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-4 h-4 rounded-sm inline-block" style="background:#fde68a"></span>Libre
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-4 h-4 rounded-sm inline-block border border-gray-200" style="background:#f9fafb"></span>Sans resa
            </span>
        </div>
    </div>
    <div class="p-4">
        <div id="annual-calendar" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"></div>
    </div>
</div>

<script>
(function () {
    const CALENDAR_YEAR = {{ $year }};
    const CALENDAR_DAYS = @json($calendarDays);
    const TODAY_STR     = '{{ now()->format("Y-m-d") }}';

    const months   = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
    const dayNames = ['Lu','Ma','Me','Je','Ve','Sa','Di'];

    const allDates  = Object.keys(CALENDAR_DAYS).sort();
    const firstDate = allDates[0] || null;
    const lastDate  = allDates[allDates.length - 1] || null;

    function pad(n) { return String(n).padStart(2, '0'); }

    function applyColor(cell, dateStr) {
        const status = CALENDAR_DAYS[dateStr];
        if (status === 'confirmed') {
            cell.style.background   = '#f87171';
            cell.style.color        = '#ffffff';
            cell.style.borderRadius = '3px';
            cell.style.fontWeight   = '600';
        } else if (status === 'pending') {
            cell.style.background   = '#34d399';
            cell.style.color        = '#ffffff';
            cell.style.borderRadius = '3px';
            cell.style.fontWeight   = '600';
        } else if (firstDate && lastDate && dateStr >= firstDate && dateStr <= lastDate) {
            cell.style.background   = '#fde68a';
            cell.style.color        = '#92400e';
            cell.style.borderRadius = '3px';
        } else {
            cell.style.color = '#9ca3af';
        }
    }

    function buildMonth(year, monthIdx) {
        const wrapper     = document.createElement('div');
        wrapper.className = 'border border-gray-100 rounded-lg overflow-hidden';

        const header         = document.createElement('div');
        header.style.cssText = 'background:#f9fafb;text-align:center;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.08em;padding:6px 0;border-bottom:1px solid #f3f4f6;';
        header.textContent   = months[monthIdx];
        wrapper.appendChild(header);

        const grid         = document.createElement('div');
        grid.style.padding = '6px';

        const namesRow         = document.createElement('div');
        namesRow.style.cssText = 'display:grid;grid-template-columns:repeat(7,1fr);margin-bottom:3px;';
        dayNames.forEach(function(d) {
            const cell         = document.createElement('div');
            cell.style.cssText = 'text-align:center;font-size:9px;font-weight:600;color:#9ca3af;padding:2px 0;';
            cell.textContent   = d;
            namesRow.appendChild(cell);
        });
        grid.appendChild(namesRow);

        const daysGrid         = document.createElement('div');
        daysGrid.style.cssText = 'display:grid;grid-template-columns:repeat(7,1fr);gap:1px;';

        const firstDay = new Date(year, monthIdx, 1).getDay();
        const offset   = firstDay === 0 ? 6 : firstDay - 1;

        for (let i = 0; i < offset; i++) {
            const empty     = document.createElement('div');
            empty.style.height = '22px';
            daysGrid.appendChild(empty);
        }

        const daysInMonth = new Date(year, monthIdx + 1, 0).getDate();

        for (let d = 1; d <= daysInMonth; d++) {
            const dateStr      = year + '-' + pad(monthIdx + 1) + '-' + pad(d);
            const cell         = document.createElement('div');
            cell.style.cssText = 'height:22px;display:flex;align-items:center;justify-content:center;font-size:10px;cursor:default;';
            cell.textContent   = d;

            applyColor(cell, dateStr);

            if (dateStr === TODAY_STR) {
                cell.style.outline       = '2px solid #3b82f6';
                cell.style.outlineOffset = '-1px';
                cell.style.borderRadius  = '3px';
            }

            const statusLabel = CALENDAR_DAYS[dateStr];
            cell.title = dateStr + (statusLabel
                ? ' — ' + (statusLabel === 'confirmed' ? 'Confirmé' : 'En attente')
                : (firstDate && dateStr >= firstDate && dateStr <= lastDate ? ' — Libre' : ''));

            daysGrid.appendChild(cell);
        }

        grid.appendChild(daysGrid);
        wrapper.appendChild(grid);
        return wrapper;
    }

    const container = document.getElementById('annual-calendar');
    for (let m = 0; m < 12; m++) {
        container.appendChild(buildMonth(CALENDAR_YEAR, m));
    }
})();
</script>