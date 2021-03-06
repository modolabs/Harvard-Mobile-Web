<?php

/****************************************************************
 *
 *  Copyright 2010 The President and Fellows of Harvard College
 *  Copyright 2010 Modo Labs Inc.
 *
 *****************************************************************/

require_once 'ArcGISServer.php';

function searchCampusMap($query) {

    $resultObj = new stdClass();
    $resultObj->results = array();
    $bldgIds = array();

    $params = array(
        'str' => $query,
        'fmt' => 'json',
        );
    
    $url = MAP_SEARCH_URL . '?' . http_build_query($params);
    $rawContent = file_get_contents($url);
    $content = json_decode($rawContent);
    
    foreach ($content->results as $resultObj) {
        if (strlen($resultObj->bld_num) && !in_array($resultObj->bld_num, $bldgIds))
            $bldgIds[] = $resultObj->bld_num;
    }

    if ($bldgIds) {
        foreach ($bldgIds as $bldgId) {
            $obj = ArcGISServer::getBldgByNumber($bldgId);
            foreach ($obj->results as $result) {
                $resultObj->results[] = $result;
            }
        }
    }

    return $resultObj;
}

// search for courses
function searchCampusMapForCourseLoc($query) {

    $resultObj = new stdClass();
    $resultObj->results = array();
    $bldgIds = array();

    $params = array(
        'str' => $query,
        'loc' => 'course',
        );

    $url = MAP_SEARCH_URL . '?' . http_build_query($params);
    $rawContent = file_get_contents($url);
    $content = json_decode($rawContent);

    foreach ($content->results as $resultObj) {
        if (!in_array($resultObj->bld_num, $bldgIds))
            $bldgIds[] = $resultObj->bld_num;
    }

    if ($bldgIds) {
        foreach ($bldgIds as $bldgId) {
            $obj = ArcGISServer::getBldgByNumber($bldgId);
            foreach ($obj->results as $result) {
                $resultObj->results[] = $result;
            }
        }
    }

    return $resultObj;
}




