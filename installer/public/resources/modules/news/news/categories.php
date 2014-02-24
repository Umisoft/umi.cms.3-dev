<?php

$json = json_decode(file_get_contents("models.json"));

$category = array();
if(array_key_exists('root', $_GET) && $_GET['root']){
//    foreach($json->category as $record){
//        if($record->root){
//            $category[] = $record;
//        }
//    }
    $res = array('category'=>$json->category);
    echo json_encode($res);
    return;
}


$category = array();
if(array_key_exists('parent', $_GET)){
    foreach($json->category as $record){
        if($record->parent == $_GET['parent']){
            $category[] = $record;
        }
    }
    $res = array('category'=>$category);
    echo json_encode($res);
    return;
}



$news = array();
$category = array();
if((array_key_exists('guid', $_GET) && $_GET['guid'] == 'lj234-34l5jl-dfigj-dlkljl-dkgkl') && (array_key_exists('mode', $_GET) && $_GET['mode'] == 'child-pages')){
    foreach($json->category as $record){
        if($record->guid == $_GET['guid']){
            $category[] = $record;
        }
    }
    $res = array('news' => $json->news, 'category' => $category);
    echo json_encode($res);
    return;
}




$category = array();
if((array_key_exists('guid', $_GET) && $_GET['guid'] == 'lj234-34l5jl-dfigj-dlkljl-dkgkl') && (array_key_exists('mode', $_GET) && $_GET['mode'] == 'edit')){
    foreach($json->category as $record){
        if($record->guid == "lj234-34l5jl-dfigj-dlkljl-dkgkl"){
            $category[] = $record;
        }
    }
    $res = array('category'=>$category);
    echo json_encode($res);
    return;
}


$news = array();
$category = array();
if((array_key_exists('guid', $_GET) && $_GET['guid'] == 'lj234-34l5jl-dfigj-dlkljl-dkgdf') && (array_key_exists('mode', $_GET) && $_GET['mode'] == 'child-pages')){
    foreach($json->category as $record){
        if($record->guid == $_GET['guid']){
            $category[] = $record;
        }
    }
    $res = array('news' => $json->news, 'category' => $category);
    echo json_encode($res);
    return;
}




$news = array();
if((array_key_exists('guid', $_GET) && $_GET['guid'] == 'lj234-34l5jl-dfigj-dlkljl-dk212') && (array_key_exists('mode', $_GET) && $_GET['mode'] == 'edit')){
    foreach($json->news as $record){
        if($record->guid == "lj234-34l5jl-dfigj-dlkljl-dk212"){
            $news[] = $record;
        }
    }
    $res = array('news'=>$news);
    echo json_encode($res);
    return;
}