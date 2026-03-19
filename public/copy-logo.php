<?php
$src = 'C:\\Users\\khrij\\Downloads\\1.jpg';
$dest_dir = __DIR__ . '/images';
$dest = $dest_dir . '/logo.jpg';

if (!is_dir($dest_dir)) {
    mkdir($dest_dir, 0755, true);
}

if (copy($src, $dest)) {
    echo 'Logo copié avec succès vers: ' . $dest;
} else {
    echo 'Erreur lors de la copie. Vérifiez que le fichier source existe: ' . $src;
}
