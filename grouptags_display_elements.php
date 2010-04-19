<?php

function checkableTagElement($tagId, $tagName, $isChecked){
    $CB_tagGroupName = "TG_CheckBox";
    
    $return = '<tr value ="'.$tagName.'" id="'.$tagId.'" ';
    $return .= 'onmouseover="this.style.cursor=\'pointer\';this.style.opacity=1;this.filters.alpha.opacity=100;" ';
    $return .= 'onmouseout="this.style.opacity=0.8;this.filters.alpha.opacity=40" ';
    $return .= ' style="background-color: #fff;opacity:0.8" >';

    $return .= "<td value ='". $tagName ."' id='taggroup". $tagId . "' onClick='toggleCheckbox(\"".$CB_tagGroupName.$tagId."\")'>";

    $return .= checkboxTagElement($tagId, $tagName, $isChecked);
    $return .= "</td></tr>";

    return $return;
}

function checkboxTagElement($tagId,$tagName,$isChecked){
    $CB_tagGroupName = "TG_CheckBox";

    if (($isChecked == 'false') || ($isChecked == false)){
        $return = "<input type='checkbox' id='". $CB_tagGroupName . $tagId ."' name='". $CB_tagGroupName . $tagId."' value='yes' onClick='toggleCheckbox(\"".$CB_tagGroupName.$tagId."\")' />" . $tagName;
    }
    else{
        $return = "<input type='checkbox' id='". $CB_tagGroupName . $tagId ."' name='". $CB_tagGroupName . $tagId."' value='yes' onClick='toggleCheckbox(\"".$CB_tagGroupName.$tagId."\")' CHECKED />" . $tagName;
    }
    return $return;
}

function tagTableRowAction(){

    $tagId = $_POST['tag_id'];
    $tagName = $_POST['tag_name'];
    $isChecked = $_POST['isChecked'];

    $response = array('tableElements' => checkboxTagElement($tagId, $tagName, $isChecked), 'tagId' => $tagId);

    echo json_encode($response);

    die();
}