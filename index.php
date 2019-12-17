<?php

use Zend\Config\Factory;

require 'vendor/autoload.php';

function usage() {
    global $argv;

    echo $argv[0] . " [input file, [ input file, .... ] [output file]\n";
    echo "\n";
    echo "Specify one or more input files in the 1 through Nth parameter.\n";
    echo "Specify the output file as the N+1th parameter.\n";
    echo "\n";
    echo "If the input is not detected as a filename, it will be tried as a glob.\n";
    echo "\n";
    $knownPlugins = [
        'ini'            => 'Ini',
        'javaproperties' => 'JavaProperties',
        'json'           => 'Json',
        'php'            => 'PhpArray',
        'phparray'       => 'PhpArray',
        'xml'            => 'Xml',
        'yaml'           => 'Yaml',
    ];

    foreach($knownPlugins as $pluginExt => $pluginNameEnglish)
    {
        echo str_pad($pluginExt, 20, ' ');
        echo $pluginNameEnglish . "\n";
    }

}

// $aggregate = new \Zend\ConfigAggregator\ConfigAggregator();

if($argc < 3) {
    usage();
    exit(1);
}

$outputFile = $argv[array_key_last($argv)];
if(is_file($outputFile)) {
    fwrite(STDERR, "FATAL: The filename $outputFile already exists!\n");
    exit(1);
}

$config = [];
for($x = 1; $x < $argc; $x++) {
    $newConfig = is_file($argv[$x]) ? Factory::fromFile($argv[$x]) : Factory::fromFiles(glob($argv[$x]));
    $config = array_merge($config, $newConfig);
}

echo "Writing to file: ";
Factory::toFile($outputFile, $config);
