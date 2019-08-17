<?php

namespace Differ;

use function \Funct\Collection\union;

const UNCHANGED = "   ";
const ADDED = " + ";
const DELETED = " - ";

function genDiff($pathToFileBefore, $pathToFileAfter, $format)
{
    $fileBefore = (array)json_decode(file_get_contents($pathToFileBefore));
    $fileAfter = (array) json_decode(file_get_contents($pathToFileAfter));
    $keys = union(array_keys($fileBefore), array_keys($fileAfter));
    $resultArray = array();
    foreach ($keys as $key) {
        if (key_exists($key, $fileBefore)) {
            if (key_exists($key, $fileAfter)) {
                if ($fileBefore[$key] === $fileAfter[$key]) {
                    $resultArray[] = [UNCHANGED, $key, $fileBefore[$key]];
                } else {
                    $resultArray[] = [DELETED, $key, $fileBefore[$key]];
                    $resultArray[] = [ADDED, $key, $fileAfter[$key]];
                }
            } else {
                $resultArray[] = [DELETED, $key, $fileBefore[$key]];
            }
        } else {
            $resultArray[] = [ADDED, $key, $fileAfter[$key]];
        }
    }
    switch ($format) {
        case 'pretty':
            return arrayToPretty($resultArray);
            break;
        default:
            return arrayToPretty($resultArray);
            break;
    }
}

function arrayToPretty($array)
{
    $result = "{\n";
    foreach ($array as $item) {
        [$state, $key, $value] = $item;
        if (is_bool($value)) {
            if ($value) {
                $value = "true";
            } else {
                $value = " false";
            }
        }
        $result .= $state . $key . ": " . $value . "\n";
    }
    $result .= "}\n";
    return $result;
}
