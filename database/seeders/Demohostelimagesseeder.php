<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * DemoHostelImagesSeeder — assigne les cover_image des hostels.
 *
 * Exécution :  php artisan db:seed --class=DemoHostelImagesSeeder
 *
 * ─────────────────────────────────────────────────────────────────────────
 *  STRATÉGIE :
 *  Pour chaque hostel, on stocke dans `hostels.cover_image` un chemin
 *  RELATIF à `public/` (sans slash initial). Ex: "images/Tabarka.jpg".
 *
 *  Côté Blade, l'affichage se fait via : asset($hostel->cover_image)
 *  → produit : http://127.0.0.1:8000/images/Tabarka.jpg
 *
 *  Le seeder est IDEMPOTENT : on peut le relancer autant de fois que
 *  voulu, il fait des UPDATE par nom. Si tu ajoutes une nouvelle image,
 *  ajoute juste une ligne au tableau $mapping ci-dessous puis relance.
 *
 * ─────────────────────────────────────────────────────────────────────────
 *  ATTENTION : mapping nom_BD → nom_fichier_disque
 *  Plusieurs noms de fichiers diffèrent du nom du hostel en BD :
 *    - Jendouba   : fichier en minuscule
 *    - Avenue Fès : fichier sans accent sur "Fes"
 *    - Aéroport   : nom contient une apostrophe
 *  → Toujours mapper "nom en BD" → "chemin réel sur disque".
 */
class DemoHostelImagesSeeder extends Seeder
{
    /**
     * Mapping : nom EXACT du hostel en BD => chemin relatif depuis public/
     *
     * Les clés DOIVENT correspondre exactement à la colonne hostels.name
     * Les valeurs sont les chemins relatifs depuis public/
     */
    private array $mapping = [
        // ─── 18 hostels AVEC photo (livrés par l'utilisateur) ──────────────
        'Maison de Jeunes Tabarka'               => 'images/Maison de Jeunes Tabarka.jpg',
        'Maison de Jeunes Mahdia'                => 'images/Maison de Jeunes Mahdia.jpg',
        'Complexe de Jeunes Médenine'            => 'images/Complexe de Jeunes Médenine.jpg',
        'Complexe de Jeunes de Métlaoui'         => 'images/Complexe de Jeunes de Métlaoui.jpg',

        // ⚠ casse du fichier en minuscule
        'Maison de Jeunes Jendouba'              => 'images/maison de jeunes jendouba.jpg',

        // ⚠ accent grave en BD ("Fès"), pas sur le fichier ("Fes")
        'Maison de Jeunes Avenue de Fès'         => 'images/Maison de Jeunes Avenue de Fes.jpg',

        // ⚠ apostrophe dans le nom — double-quotes pour PHP
        "Complexe de Jeunes Route de l'Aéroport" => "images/Complexe de Jeunes Route de l'Aéroport.jpg",

        'Maison de Jeunes 15 Octobre Bizerte'    => 'images/Maison de Jeunes 15 Octobre Bizerte.jpg',
        'Complexe de Jeunes du Kef'              => 'images/Complexe de Jeunes du Kef.jpg',
        'Maison de Jeunes Hajeb Ayoun'           => 'images/Maison de Jeunes Hajeb Ayoun.png',
        'Maison de Jeunes Haïdra'                => 'images/Maison de Jeunes Haïdra.jpg',
        'Complexe de Jeunes Ali Skhiri'          => 'images/Complexe de Jeunes Ali Skhiri.png',
        'Complexe de Jeunes Nabeul'              => 'images/Complexe de Jeunes Nabeul.jpg',
        'Maison de Jeunes Dahmani'               => 'images/Maison de Jeunes Dahmani.png',
        'Maison de Jeunes Nasrallah'             => 'images/Maison de Jeunes Nasrallah.png',
        'Maison de Jeunes Kélibia'               => 'images/Maison de Jeunes Kélibia.png',
        'Maison de Jeunes Korba'                 => 'images/Maison de Jeunes Korba.png',
        'Maison de Jeunes Nefta'                 => 'images/Maison de Jeunes Nefta.png',

        // ─── 12 hostels SANS photo pour le moment ─────────────────────────
        //  Décommente et complète le chemin quand tu fourniras les images :
        //
        // 'Complexe de Jeunes La Marsa'         => 'images/Complexe de Jeunes La Marsa.jpg',
        // 'Complexe de Jeunes de Béja'          => 'images/Complexe de Jeunes de Béja.jpg',
        // 'Complexe de Jeunes Sousse'           => 'images/Complexe de Jeunes Sousse.jpg',
        // 'Complexe de Jeunes Houmet Essouk'    => 'images/Complexe de Jeunes Houmet Essouk.jpg',
        // 'Maison de Jeunes Kébili'             => 'images/Maison de Jeunes Kébili.jpg',
        // 'Complexe de Jeunes Hammamet'         => 'images/Complexe de Jeunes Hammamet.jpg',
        // 'Maison de Jeunes Aïn Drahem'         => 'images/Maison de Jeunes Aïn Drahem.jpg',
        // 'Complexe de Jeunes Saniet El Bey'    => 'images/Complexe de Jeunes Saniet El Bey.jpg',
        // 'Maison de Jeunes Sbeïtla'            => 'images/Maison de Jeunes Sbeïtla.jpg',
        // 'Complexe de Jeunes de Kasserine'     => 'images/Complexe de Jeunes de Kasserine.jpg',
        // 'Complexe de Jeunes de Gafsa'         => 'images/Complexe de Jeunes de Gafsa.jpg',
        // 'Complexe de Jeunes de Kairouan'      => 'images/Complexe de Jeunes de Kairouan.jpg',
    ];

    public function run(): void
    {
        $this->command->info('🖼  [DemoHostelImagesSeeder] Assignation des images aux hostels...');
        $this->command->info('');

        $now = now();
        $updated = 0;
        $notFoundDb = [];        // Hostels du mapping introuvables en BD
        $missingFiles = [];      // Fichiers physiques absents du disque
        $totalInMapping = count($this->mapping);

        foreach ($this->mapping as $hostelName => $imagePath) {

            // 1. Vérification existence physique du fichier (warning, non bloquant)
            $absolutePath = public_path($imagePath);
            if (!file_exists($absolutePath)) {
                $missingFiles[] = $imagePath;
            }

            // 2. UPDATE par nom (correspond exactement aux noms semés)
            $count = DB::table('hostels')
                ->where('name', $hostelName)
                ->update([
                    'cover_image' => $imagePath,
                    'updated_at'  => $now,
                ]);

            if ($count === 0) {
                $notFoundDb[] = $hostelName;
                $this->command->line("   ❌ '$hostelName' → introuvable en BD (UPDATE ignoré)");
            } else {
                $updated++;
                $this->command->line("   ✓ $hostelName  →  $imagePath");
            }
        }

        // ─── Récap ──────────────────────────────────────────────────────────
        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info("  📊 $updated / $totalInMapping hostels mis à jour");
        $this->command->info('═══════════════════════════════════════════════════════');

        // Hostels du mapping sans match en BD
        if (!empty($notFoundDb)) {
            $this->command->warn('');
            $this->command->warn('⚠  Hostels du mapping NON trouvés en BD :');
            foreach ($notFoundDb as $name) {
                $this->command->warn("   - $name");
            }
            $this->command->warn('  → Vérifie le nom EXACT dans la table hostels (accents, casse).');
        }

        // Fichiers physiques manquants
        if (!empty($missingFiles)) {
            $this->command->warn('');
            $this->command->warn('⚠  Fichiers physiques absents (BD mise à jour quand même) :');
            foreach ($missingFiles as $path) {
                $this->command->warn("   - public/$path");
            }
            $this->command->warn('  → Copie les fichiers manquants dans public/images/');
        }

        // Hostels en BD sans photo (les 12 manquants)
        $remaining = DB::table('hostels')
            ->whereNull('cover_image')
            ->pluck('name')
            ->toArray();

        if (!empty($remaining)) {
            $this->command->info('');
            $this->command->info('📷 Hostels SANS photo (en attente) :');
            foreach ($remaining as $name) {
                $this->command->line("   - $name");
            }
            $this->command->info('  → Quand tu auras les images, ajoute des lignes au tableau $mapping et relance ce seeder.');
        }

        $this->command->info('');
    }
}