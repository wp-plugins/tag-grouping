<script type="text/javascript" src="grouptags_return_group_tags.php"></script>

function toggleCheckboxes(id) {
    if (!document.getElementById){
        return;
    }
    if (!document.getElementsByTagName){
        return;
    }
    var inputs = document.getElementById(id).getElementsByTagName("input");
    for(var x=0; x < inputs.length; x++) {
        if (inputs[x].type == 'checkbox'){
            inputs[x].checked = !inputs[x].checked;
        }
    }
}
function displayDiv()
{
    var divstyle = new String();
    divstyle = document.getElementById("instructions").style.display;
    if(divstyle.toLowerCase()=="block" || divstyle == "")
    {
        document.getElementById("instructions").style.display = "none";
    }
    else
    {
        document.getElementById("instructions").style.display = "block";
    }
}
//function disp_tags(){
//    var w = document.manage_group.groupDropDown.selectedIndex;
//    var selected_text = document.manage_group.groupDropDown.options[w].text;
//
//    var groupID = <?php echo fetch_groupID(selected_text) ?>;
//    alert(groupID);
//    var groupTags = new Array(<?php echo fetch_group_terms(groupID) ?>);
//
//    alert(groupTags.length);
//
//    for (var i = 0; i < groupTags.length; i++){
//        alert (groupTags[i]);
//    }
//    //alert(selected_text);
//}

