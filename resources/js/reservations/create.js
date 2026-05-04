/**
 * HostelFlow — Création réservation
 * Sprint 4 — JS complet (CORRIGÉ v3)
 *
 * FIXES appliqués :
 *  [FIX 1] loadUnits() — noms de variables corrects pour ce fichier
 *  [FIX 2] loadUnits() — auto-sélection robuste (vérifie validité, pas juste vide)
 *  [FIX 3] fetchAvailability() — reset intelligent (invalide seulement si plus dispo)
 *  [FIX 4] submit — validation item_id avant sérialisation
 */

document.addEventListener("DOMContentLoaded", function () {

    // ─── État global ─────────────────────────────────────────────────────────
    let guests              = [];
    let selectedGuestIndex  = 0;
    let availableUnits      = { beds: [], rooms: [], tent_spaces: [] };

    // ─── Éléments DOM ────────────────────────────────────────────────────────
    const startDateEl       = document.getElementById('start_date');
    const endDateEl         = document.getElementById('end_date');
    const nightsEl          = document.getElementById('nights');
    const totalGuestsEl     = document.getElementById('total_guests');
    const guestListEl       = document.getElementById('guest-list');
    const guestDetailsEl    = document.getElementById('guest-details');
    const summaryBodyEl     = document.getElementById('summary_body');
    const totalTndEl        = document.getElementById('total_tnd');
    const totalEurEl        = document.getElementById('total_eur');
    const totalUsdEl        = document.getElementById('total_usd');
    const passwordInputEl   = document.getElementById('password_input');
    const submitBtnEl       = document.getElementById('submit_btn');
    const passwordStatusEl  = document.getElementById('password_status');
    const addedByUserEl     = document.getElementById('added_by_user_id');
    const reservationFormEl = document.getElementById('reservation_form');
    const guestsDataEl      = document.getElementById('guests_data');
    const availNoticeEl     = document.getElementById('availability_notice');

    // ─── Guest vide par défaut ────────────────────────────────────────────────
    function emptyGuest(index) {
        return {
            first_name:    '',
            last_name:     '',
            identity_card: '',
            email:         '',
            phone:         '',
            country_id:    countries.length ? countries[0].id : '',
            gender:        'male',
            same_as_main:  index !== 0,
            item_type:     'bed',
            item_id:       '',
            price_input:   0,
            currency:      'TND',
            price_tnd:     0,
            exchange_rate: 1,
        };
    }

    // ─── Calcul nuits ────────────────────────────────────────────────────────
    function calculateNights() {
        if (!startDateEl.value || !endDateEl.value) {
            nightsEl.value = 0;
            return;
        }
        const diff = (new Date(endDateEl.value) - new Date(startDateEl.value)) / 86_400_000;
        nightsEl.value = diff > 0 ? diff : 0;
    }

    // ─── Génération guests ───────────────────────────────────────────────────
    function generateGuests(count) {
        const old = [...guests];
        guests = [];
        for (let i = 0; i < count; i++) {
            guests.push(old[i] ?? emptyGuest(i));
        }
        selectedGuestIndex = Math.min(selectedGuestIndex, guests.length - 1);
        renderGuestList();
        renderGuestDetails();
        calculateTotals();
    }

    // ─── Liste gauche ────────────────────────────────────────────────────────
    function renderGuestList() {
        guestListEl.innerHTML = '';

        guests.forEach((guest, index) => {
            const li      = document.createElement('li');
            const hasUnit = guest.item_id ? '✅' : '⚠️';

            const parts = [guest.first_name, guest.last_name].filter(s => s && s.trim());
            const nameLabel = parts.length > 0
                ? parts.join(' ')
                : (index === 0 ? 'Guest 1 (Principal)' : `Guest ${index + 1}`);

            li.textContent = `${hasUnit} ${nameLabel}`;
            if (index === selectedGuestIndex) li.classList.add('active');

            li.addEventListener('click', () => {
                selectedGuestIndex = index;
                renderGuestList();
                renderGuestDetails();
            });

            guestListEl.appendChild(li);
        });
    }

    // ─── Détail guest (droite) ───────────────────────────────────────────────
    function renderGuestDetails() {
        const guest   = guests[selectedGuestIndex];
        const isMain  = selectedGuestIndex === 0;
        const label   = isMain ? 'Guest Principal' : `Guest ${selectedGuestIndex + 1}`;
        const hasUnit = guest.item_id;

        guestDetailsEl.innerHTML = `
            <div class="hf-guest-card">

                <div class="hf-guest-card-head">
                    <h3 class="hf-guest-card-title">${label}</h3>
                    <span class="hf-badge-status ${hasUnit ? 'hf-badge-status--ok' : 'hf-badge-status--nok'}">
                        ${hasUnit ? 'Affecté' : 'Non affecté'}
                    </span>
                </div>

                ${!isMain ? `
                    <label class="hf-checkbox-label">
                        <input type="checkbox" id="same_as_main" ${guest.same_as_main ? 'checked' : ''}>
                        Même informations que le guest principal
                    </label>
                ` : ''}

                <div class="hf-grid-3">

                    <!-- Informations -->
                    <div>
                        <p class="hf-section-title">Informations</p>
                        <input type="text" id="first_name" class="hf-input mb-2"
                               placeholder="Nom *" value="${esc(guest.first_name)}">
                        <input type="text" id="last_name" class="hf-input mb-2"
                               placeholder="Prénom *" value="${esc(guest.last_name)}">
                        <input type="text" id="identity_card" class="hf-input mb-2"
                               placeholder="CIN / Passeport" value="${esc(guest.identity_card)}">
                        <input type="email" id="email" class="hf-input mb-2"
                               placeholder="Email" value="${esc(guest.email)}">
                        <input type="text" id="phone" class="hf-input mb-2"
                               placeholder="Téléphone" value="${esc(guest.phone)}">
                        <select id="gender" class="hf-select mb-2">
                            <option value="male"   ${guest.gender === 'male'   ? 'selected' : ''}>Homme</option>
                            <option value="female" ${guest.gender === 'female' ? 'selected' : ''}>Femme</option>
                        </select>
                        <select id="country_id" class="hf-select">
                            ${countries.map(c => `
                                <option value="${c.id}" ${Number(guest.country_id) === Number(c.id) ? 'selected' : ''}>
                                    ${esc(c.name)}
                                </option>
                            `).join('')}
                        </select>
                    </div>

                    <!-- Affectation -->
                    <div>
                        <p class="hf-section-title">Affectation</p>
                        <select id="item_type" class="hf-select mb-2">
                            <option value="bed"        ${guest.item_type === 'bed'        ? 'selected' : ''}>Dormitory (lit)</option>
                            <option value="room"       ${guest.item_type === 'room'       ? 'selected' : ''}>Chambre privée</option>
                            <option value="tent_space" ${guest.item_type === 'tent_space' ? 'selected' : ''}>Tente</option>
                        </select>
                        <select id="item_id" class="hf-select"></select>
                        <p id="availability_status" class="hf-avail-ok mt-2">
                            ${startDateEl.value && endDateEl.value
                                ? '✓ Disponibilité filtrée selon les dates.'
                                : '⚠ Sélectionnez des dates pour filtrer.'}
                        </p>
                    </div>

                    <!-- Tarification -->
                    <div>
                        <p class="hf-section-title">Tarification</p>
                        <input type="number" id="price_input" class="hf-input mb-2"
                               value="${guest.price_input}" min="0" step="0.001"
                               placeholder="Prix">
                        <select id="currency" class="hf-select mb-2">
                            <option value="TND" ${guest.currency === 'TND' ? 'selected' : ''}>TND</option>
                            <option value="EUR" ${guest.currency === 'EUR' ? 'selected' : ''}>EUR</option>
                            <option value="USD" ${guest.currency === 'USD' ? 'selected' : ''}>USD</option>
                        </select>
                        <div class="hf-tnd-display">
                            ≈ <strong><span id="price_tnd_display">${Number(guest.price_tnd).toFixed(3)}</span> TND</strong>
                            <br>
                            <small>Taux : <span id="rate_display">${guest.exchange_rate}</span></small>
                        </div>
                    </div>

                </div>
            </div>
        `;

        bindGuestInputs();
        loadUnits();
        calculatePrice();
    }

    // ─── Liaison événements formulaire guest ─────────────────────────────────
    function bindGuestInputs() {
        const guest  = guests[selectedGuestIndex];
        const isMain = selectedGuestIndex === 0;

        ['first_name', 'last_name', 'identity_card', 'email', 'phone', 'gender', 'country_id']
            .forEach(field => {
                const el = document.getElementById(field);
                if (!el) return;
                el.addEventListener('input',  () => handleFieldChange(field, el.value, guest, isMain));
                el.addEventListener('change', () => handleFieldChange(field, el.value, guest, isMain));
            });

        if (!isMain) {
            const cb = document.getElementById('same_as_main');
            if (cb) {
                cb.addEventListener('change', function () {
                    guest.same_as_main = this.checked;
                    if (this.checked) {
                        copyMainToGuest(guest);
                        renderGuestDetails();
                    }
                });
            }
        }

        const itemTypeEl = document.getElementById('item_type');
        if (itemTypeEl) {
            itemTypeEl.addEventListener('change', function () {
                guest.item_type = this.value;
                guest.item_id   = '';
                loadUnits();
                renderGuestList();
                calculateTotals();
            });
        }

        const itemIdEl = document.getElementById('item_id');
        if (itemIdEl) {
            itemIdEl.addEventListener('change', function () {
                guest.item_id = this.value;
                renderGuestList();
                calculateTotals();
                const badge = document.querySelector('.hf-badge-status');
                if (badge) {
                    badge.className   = 'hf-badge-status ' + (guest.item_id ? 'hf-badge-status--ok' : 'hf-badge-status--nok');
                    badge.textContent = guest.item_id ? 'Affecté' : 'Non affecté';
                }
            });
        }

        const priceInputEl = document.getElementById('price_input');
        if (priceInputEl) {
            priceInputEl.addEventListener('input', function () {
                guest.price_input = parseFloat(this.value) || 0;
                calculatePrice();
                calculateTotals();
            });
        }

        const currencyEl = document.getElementById('currency');
        if (currencyEl) {
            currencyEl.addEventListener('change', function () {
                guest.currency = this.value;
                calculatePrice();
                calculateTotals();
            });
        }
    }

    function handleFieldChange(field, value, guest, isMain) {
        guest[field] = value;
        if (isMain) propagateMainGuest();
        calculateTotals();
        renderGuestList();
    }

    // ─── Propagation guest principal ─────────────────────────────────────────
    function copyMainToGuest(target) {
        const main = guests[0];
        ['first_name','last_name','identity_card','email','phone','country_id','gender']
            .forEach(f => target[f] = main[f]);
    }

    function propagateMainGuest() {
        guests.forEach((g, i) => {
            if (i !== 0 && g.same_as_main) copyMainToGuest(g);
        });
    }

    // ╔═══════════════════════════════════════════════════════════════════════╗
    // ║  FIX 1+2+3 — loadUnits() : robuste et cohérente                     ║
    // ║  - Bons noms de variables                                            ║
    // ║  - Auto-sélection si item_id vide OU invalide dans la liste          ║
    // ╚═══════════════════════════════════════════════════════════════════════╝
    function loadUnits() {
        const guest = guests[selectedGuestIndex];
        const el    = document.getElementById('item_id');
        if (!el) return;

        el.innerHTML = '<option value="">— Sélectionner une unité —</option>';

        const listMap = {
            bed:        availableUnits.beds        || [],
            room:       availableUnits.rooms       || [],
            tent_space: availableUnits.tent_spaces || [],
        };

        const list = listMap[guest.item_type] || [];

        if (list.length === 0) {
            const noOpt       = document.createElement('option');
            noOpt.disabled    = true;
            noOpt.textContent = 'Aucune unité disponible';
            el.appendChild(noOpt);
            guest.item_id = '';
            const s = document.getElementById('availability_status');
            if (s) {
                s.textContent = '⚠️ Aucune unité disponible pour ces dates.';
                s.className   = 'hf-avail-warn mt-2';
            }
            return;
        }

        // Remplir les options
        list.forEach(u => {
            const opt       = document.createElement('option');
            opt.value       = String(u.id);
            opt.textContent = u.remaining_capacity !== undefined
                ? `${u.name} (${u.remaining_capacity} place(s) libre(s))`
                : u.name;
            el.appendChild(opt);
        });

        // Vérifier si le item_id actuel est toujours valide dans la liste
        const currentValid = guest.item_id && list.some(u => String(u.id) === String(guest.item_id));

        if (currentValid) {
            // Restaurer la sélection précédente
            el.value = String(guest.item_id);
        } else {
            // Auto-sélectionner la 1ère unité disponible
            guest.item_id = String(list[0].id);
            el.value      = guest.item_id;

            const badge = document.querySelector('.hf-badge-status');
            if (badge) {
                badge.className   = 'hf-badge-status hf-badge-status--ok';
                badge.textContent = 'Affecté';
            }

            renderGuestList();
            calculateTotals();
        }

        const s = document.getElementById('availability_status');
        if (s) {
            const hasDates = startDateEl.value && endDateEl.value;
            s.textContent  = hasDates
                ? '✓ Disponibilité filtrée selon les dates.'
                : '⚠ Toutes unités affichées (sans filtre date).';
            s.className = hasDates ? 'hf-avail-ok mt-2' : 'hf-avail-warn mt-2';
        }
    }

    // ─── Pricing ─────────────────────────────────────────────────────────────
    function calculatePrice() {
        const guest = guests[selectedGuestIndex];
        const price = parseFloat(guest.price_input) || 0;

        switch (guest.currency) {
            case 'TND':
                guest.exchange_rate = 1;
                guest.price_tnd     = price;
                break;
            case 'EUR':
                guest.exchange_rate = Number(exchangeRates.eur.sell) || 0;
                guest.price_tnd     = price * guest.exchange_rate;
                break;
            case 'USD':
                guest.exchange_rate = Number(exchangeRates.usd.sell) || 0;
                guest.price_tnd     = price * guest.exchange_rate;
                break;
        }

        const displayEl = document.getElementById('price_tnd_display');
        const rateEl    = document.getElementById('rate_display');
        if (displayEl) displayEl.textContent = Number(guest.price_tnd).toFixed(3);
        if (rateEl)    rateEl.textContent    = guest.exchange_rate;
    }

    // ─── Récapitulatif ───────────────────────────────────────────────────────
    function calculateTotals() {
        let totalTnd = 0, totalEur = 0, totalUsd = 0;
        summaryBodyEl.innerHTML = '';

        guests.forEach((guest, index) => {
            totalTnd += Number(guest.price_tnd) || 0;
            if (guest.currency === 'EUR') totalEur += Number(guest.price_input) || 0;
            if (guest.currency === 'USD') totalUsd += Number(guest.price_input) || 0;

            const parts = [guest.first_name, guest.last_name].filter(s => s && s.trim());
            const nameLabel = parts.length > 0
                ? parts.join(' ')
                : (index === 0 ? 'Guest 1' : `Guest ${index + 1}`);

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${esc(nameLabel)}</td>
                <td>${getItemLabel(guest)}</td>
                <td>${Number(guest.price_input).toFixed(3)}</td>
                <td>${guest.currency}</td>
                <td>${Number(guest.price_tnd).toFixed(3)}</td>
            `;
            summaryBodyEl.appendChild(tr);
        });

        totalTndEl.textContent = totalTnd.toFixed(3);
        totalEurEl.textContent = totalEur.toFixed(3);
        totalUsdEl.textContent = totalUsd.toFixed(3);
    }

    function getItemLabel(guest) {
        if (!guest.item_id) return '<em style="color:#9CA3AF">Non affecté</em>';
        const listMap = {
            bed:        availableUnits.beds        || [],
            room:       availableUnits.rooms       || [],
            tent_space: availableUnits.tent_spaces || [],
        };
        const list = listMap[guest.item_type] || [];
        const item = list.find(i => Number(i.id) === Number(guest.item_id));
        return item ? esc(item.name) : 'Unité sélectionnée';
    }

    // ╔═══════════════════════════════════════════════════════════════════════╗
    // ║  FIX 3 — fetchAvailability : reset intelligent                      ║
    // ║  N'invalide que les item_id qui ne sont plus dans la liste retournée ║
    // ║  Evite de perdre une sélection encore valide après changement de date║
    // ╚═══════════════════════════════════════════════════════════════════════╝
    function fetchAvailability() {
        if (!startDateEl.value || !endDateEl.value) return;

        fetch(`${routes.availableUnits}?start_date=${startDateEl.value}&end_date=${endDateEl.value}`)
            .then(r => r.json())
            .then(data => {
                availableUnits = data;

                // Invalider seulement les item_id qui ne sont plus disponibles
                guests.forEach(g => {
                    const listMap = {
                        bed:        data.beds        || [],
                        room:       data.rooms       || [],
                        tent_space: data.tent_spaces || [],
                    };
                    const list       = listMap[g.item_type] || [];
                    const stillValid = list.some(u => String(u.id) === String(g.item_id));
                    if (!stillValid) g.item_id = '';
                });

                if (availNoticeEl) availNoticeEl.style.display = 'flex';
                renderGuestDetails();
                renderGuestList();
                calculateTotals();
            })
            .catch(() => {
                console.warn('Erreur lors de la récupération des disponibilités.');
            });
    }

    // ─── AJAX vérification password ──────────────────────────────────────────
    let passwordTimer = null;

    passwordInputEl.addEventListener('input', function () {
        clearTimeout(passwordTimer);
        submitBtnEl.disabled         = true;
        passwordStatusEl.textContent = '';

        if (this.value.length < 4) return;

        passwordTimer = setTimeout(() => {
            fetch(routes.checkPassword, {
                method:  'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': routes.csrfToken,
                },
                body: JSON.stringify({
                    added_by_user_id: addedByUserEl.value,
                    password:         passwordInputEl.value,
                }),
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    submitBtnEl.disabled         = false;
                    passwordStatusEl.textContent = '✅ Mot de passe correct';
                    passwordStatusEl.className   = 'hf-password-status hf-password-ok';
                } else {
                    submitBtnEl.disabled         = true;
                    passwordStatusEl.textContent = '❌ Mot de passe incorrect';
                    passwordStatusEl.className   = 'hf-password-status hf-password-ko';
                }
            })
            .catch(() => {
                submitBtnEl.disabled         = true;
                passwordStatusEl.textContent = '⚠️ Erreur de vérification';
                passwordStatusEl.className   = 'hf-password-status hf-password-ko';
            });
        }, 500);
    });

    addedByUserEl.addEventListener('change', () => {
        passwordInputEl.value        = '';
        submitBtnEl.disabled         = true;
        passwordStatusEl.textContent = '';
    });

    // ╔═══════════════════════════════════════════════════════════════════════╗
    // ║  FIX 4 — Submit : validation item_id + nom obligatoires             ║
    // ╚═══════════════════════════════════════════════════════════════════════╝
    reservationFormEl.addEventListener('submit', function (e) {

        for (let i = 0; i < guests.length; i++) {
            const g = guests[i];

            if (!g.first_name.trim()) {
                e.preventDefault();
                alert(`❌ Guest ${i + 1} : le nom est obligatoire.`);
                selectedGuestIndex = i;
                renderGuestList();
                renderGuestDetails();
                return;
            }

            if (!g.last_name.trim()) {
                e.preventDefault();
                alert(`❌ Guest ${i + 1} : le prénom est obligatoire.`);
                selectedGuestIndex = i;
                renderGuestList();
                renderGuestDetails();
                return;
            }

            if (!g.item_id) {
                e.preventDefault();
                alert(`❌ Guest ${i + 1} (${g.first_name || '?'}) n'a pas d'affectation sélectionnée.`);
                selectedGuestIndex = i;
                renderGuestList();
                renderGuestDetails();
                return;
            }
        }

        calculateTotals();
        guestsDataEl.value = JSON.stringify(guests);
    });

    // ─── Événements globaux ──────────────────────────────────────────────────
    startDateEl.addEventListener('change', () => { calculateNights(); fetchAvailability(); });
    endDateEl.addEventListener('change',   () => { calculateNights(); fetchAvailability(); });

    totalGuestsEl.addEventListener('change', function () {
        let count = parseInt(this.value);
        if (!count || count < 1) { count = 1; this.value = 1; }
        generateGuests(count);
    });

    // ─── Utilitaires ─────────────────────────────────────────────────────────
    function esc(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    // ─── Initialisation ──────────────────────────────────────────────────────
    generateGuests(1);
    fetchAvailability();

}); // fin DOMContentLoaded