<?php
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('app/Http/Controllers'));
$count = 0;
foreach ($files as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getPathname();
        $content = file_get_contents($path);
        if (strpos($content, "guard('staff')") !== false) {
            $content = str_replace("guard('staff')", "guard('user')", $content);
            file_put_contents($path, $content);
            echo "Fixed $path\n";
            $count++;
        }
    }
}
echo "Replaced in $count files.\n";
