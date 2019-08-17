<?php

namespace Differ\Cli;

use \Docopt;

use function Differ\genDiff;

const DOC = <<<DOC
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help                     Show this screen
  -v --version                  Show version
  --format <fmt>                Report format [default: pretty]
DOC;

function run()
{
    $args = Docopt::handle(DOC, array('version' => 'Gendiff 0.0.1'));
    //var_dump($args);
    $pathToFile1 = realpath($args['<firstFile>']);
    $pathToFile2 = realpath($args['<secondFile>']);
    $format = $args['--format'];
    $diff = genDiff($pathToFile1, $pathToFile2, $format);
    print_r($diff);
}
