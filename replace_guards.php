<?php
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator('app/Http/Controllers')) as $f) {
    if ($f->isFile() && $f->getExtension() === 'php') {
        $c = file_get_contents($f);
        $nc = str_replace("guard('staff')", "guard('user')", $c);
        if ($c !== $nc) {
            file_put_contents($f, $nc);
            echo "Updated " . $f->getPathname() . "\n";
        }
    }
}
