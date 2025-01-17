<?php

require __DIR__ . '/vendor/autoload.php';

use Lsv\Wasl\Generator\Command\GenerateWadlTemplateCommand;
use Symfony\Component\Console\Application;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$twig = new Environment(
    new FilesystemLoader([__DIR__ . '/templates'])
);

$command = new GenerateWadlTemplateCommand($twig);

$app = new Application('WASL generator', '1.0');
$app->add($command);
$app->setDefaultCommand($command->getName(), true);
$app->run();