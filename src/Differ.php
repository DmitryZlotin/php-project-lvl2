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
    $resultArray = getResultArray($keys, $fileBefore, $fileAfter);
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
            $value = getBoolString($value);
        }
        $result .= $state . $key . ": " . $value . "\n";
    }
    $result .= "}\n";
    return $result;
}

function getResultArray($keys, $before, $after)
{
    $result = array_reduce($keys, function ($acc, $key) use ($before, $after) {
        $state = getState($key, $before, $after);
        switch ($state) {
            case 'unchanged':
                $acc[] = [UNCHANGED, $key, $before[$key]];
                break;
            case 'changed':
                $acc[] = [DELETED, $key, $before[$key]];
                $acc[] = [ADDED, $key, $after[$key]];
                break;
            case 'deleted':
                $acc[] = [DELETED, $key, $before[$key]];
                break;
            case 'added':
                $acc[] = [ADDED, $key, $after[$key]];
                break;
        }
        return $acc;
    }, []);
    return $result;
}

function getBoolString($bool)
{
    return $bool ? "true" : "false";
}

function getState($key, $before, $after)
{
    if (key_exists($key, $before)) {
        if (key_exists($key, $after)) {
            if ($before[$key] === $after[$key]) {
                return "unchanged";
            } else {
                return "changed";
            }
        } else {
            return "deleted";
        }
    } else {
        return "added";
    }
}
