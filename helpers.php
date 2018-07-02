<?php

// function objectToArray($obj) {

//     if(is_object($obj)) {
//         $obj = (array) $obj;
//         $aobj = array();

//         foreach ($obj as $key=>$value) {
//             $aobj[__cleanStr($key)] = $value; //sanitize the str for null chars
//         }
//             $obj = $aobj;
//     }

//     if(is_array($obj)) {
//         $new = array();
//         foreach($obj as $key => $val) {
//             $new[__cleanStr($key)] = objectToArray($val);
//         }
//     }
//     else $new = $obj;
//         return $new;
//     }

//     function __cleanStr($str) {
//         $str = str_replace("\0", "", $str); //remove null chars
//         return $str;
//     }

