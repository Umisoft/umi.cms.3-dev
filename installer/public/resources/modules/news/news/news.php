<?php

$json = json_decode(file_get_contents("models.json"));


$news = array();
if ((array_key_exists('guid', $_GET) && $_GET['guid'] == 'lj234-34l5jl-dfigj-dlkljl-dkgkl') && (array_key_exists('mode', $_GET) && $_GET['mode'] == 'edit')) {
    foreach ($json->news as $record) {
        if ($record->guid == "lj234-34l5jl-dfigj-dlkljl-dkgkl") {
            $news[] = $record;
        }
    }
    $res = array('news' => $news);
    echo json_encode($res);
    return;
}

$news = array();
if ((array_key_exists('guid', $_GET) && $_GET['guid'] == 'lj234-34l5jl-dfigj-dlkljl-dk34d') && (array_key_exists('mode', $_GET) && $_GET['mode'] == 'edit')) {
    foreach ($json->news as $record) {
        if ($record->guid == "lj234-34l5jl-dfigj-dlkljl-dk34d") {
            $news[] = $record;
        }
    }
    $res = array('news' => $news);
    echo json_encode($res);
    return;
}

$news = array();
if ((array_key_exists('guid', $_GET) && $_GET['guid'] == 'lj234-34l5jl-dfigj-dlkljl-dk232') && (array_key_exists('mode', $_GET) && $_GET['mode'] == 'edit')) {
    foreach ($json->news as $record) {
        if ($record->guid == "lj234-34l5jl-dfigj-dlkljl-dk232") {
            $news[] = $record;
        }
    }
    $res = array('news' => $news);
    echo json_encode($res);
    return;
}

$news = array();
if ((array_key_exists('guid', $_GET) && $_GET['guid'] == 'lj234-34l5jl-dfigj-dlkljl-dk212') && (array_key_exists('mode', $_GET) && $_GET['mode'] == 'edit')) {
    foreach ($json->news as $record) {
        if ($record->guid == "lj234-34l5jl-dfigj-dlkljl-dk212") {
            $news[] = $record;
        }
    }
    $res = array('news' => $news);
    echo json_encode($res);
    return;
}

$news = array();
if ((array_key_exists('guid', $_GET) && $_GET['guid'] == 'lj234-34l5jl-dfigj-dlkljl-ddw13') && (array_key_exists('mode', $_GET) && $_GET['mode'] == 'edit')) {
    foreach ($json->news as $record) {
        if ($record->guid == "lj234-34l5jl-dfigj-dlkljl-ddw13") {
            $news[] = $record;
        }
    }
    $res = array('news' => $news);
    echo json_encode($res);
    return;
}

$news = array();
if (array_key_exists('ids', $_GET)) {
    foreach ($json->news as $record) {
        if (in_array($record->guid, $_GET['ids'])) {
            $news[] = $record;
        }
    }
    $res = array('news' => $news);
    echo json_encode($res);
    return;
}