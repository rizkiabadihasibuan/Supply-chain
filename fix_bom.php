<?php
$dir = new RecursiveDirectoryIterator('.');
$ite = new RecursiveIteratorIterator($dir);
$count = 0;
foreach($ite as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), '.php')) {
        $content = file_get_contents($file->getPathname());
        $bom = pack('H*','EFBBBF');
        if (preg_match("/^$bom+/", $content, $matches)) {
            $content = preg_replace("/^$bom+/", '', $content);
            file_put_contents($file->getPathname(), $content);
            echo "Removed BOM from " . $file->getPathname() . PHP_EOL;
            $count++;
        }
    }
}
echo "Total files fixed: $count\n";
