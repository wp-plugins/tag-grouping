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