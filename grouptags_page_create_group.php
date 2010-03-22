<?php

function group_tag_admin_menu(){
    add_menu_page('Manage your Tags', 'Group Tags', 'administrator', 'taggroup', 'group_tag_admin', get_bloginfo('siteurl')."/wp-content/plugins/tag-grouping/images/Group-icon.png");
    add_submenu_page('taggroup', 'Edit a Group of Tags', 'Edit Group', 'administrator', 'taggroup', 'group_tag_admin');//, get_bloginfo('siteurl')."/wp-content/plugins/tag-grouping/images/Group-icon.png");
    add_submenu_page( 'taggroup', 'Create a Group of Tags', 'Create Group', 'administrator', 'create_group', 'group_tag_create_page');
    add_submenu_page( 'taggroup', 'Delete a Group of Tags', 'Delete Groups', 'administrator', 'delete_group', 'group_tag_delete_page');
}

function group_tag_create_page(){
    ?>
<div class="wrap nosubsub">
    <div class="icon32">
        <img src="<?php echo get_bloginfo('siteurl')?>/wp-content/plugins/tag-grouping/images/Group.png" height=36 width=36>
        </img>
    </div>
<h2>Create a Group <input type="button" class="button-secondary" value="Instructions" onclick="displayDiv()" /></h2></div>

<p id="instructions" class="popular-tags" style="display: none">
    <b>1. </b>Type the Group Name into the Group Name textfield.
    <BR>
    <B>2. </B>To add tags to your Group, do one of the following:
    <BR>
    Select the tags you would like to assign to the Group by enabling the checkboxes below. Click "Check all Tags" to select all tags in the list.
    <BR><i> - OR - </i>
    <BR>
    Type the tags you wish to add to the Group into the textfield below, separated by commas.
    <BR>
    <b>3. </b>Click the Submit button when you are finished.</p>

    NOTE: When you are creating or editting a Post, the <B>Tag Groups</B> are listed in the bottom-right of the screen.
<div width=50%; align=left>
    <form method="post" name="tag_selections">
    <table class="form-table"><BR>
        <tr>
            <td width=15%; align=left>
            Group Name: <input type="text" name="groupName" id="groupName" />
            <p class="howto">Input your Group name.</p>
            </td>
            <td width=15%; align=left>
                <?php echo createTagDisplayObject();?>
            </td>
            <td></td>
        </tr>
    </table>
</div>
<?php
$results = fetch_all_tags();
?>
<div class="wrap">
    <table class="widefat page fixed">
        <thead>
            <tr>
                <th scope="col" id="cb" class="widefat page fixed" width=30%>
                    <input type="button" class="button-secondary" style="width:100px" value="<?php _e('Check all Tags')?>" onClick="toggleCheckboxes('parent_box')">
                </th>
            </tr>
        </thead>
        <tbody id="parent_box" style="height: 250px; overflow: auto">
            <?php
            foreach ($results as $result){
                ?>
            <tr>
                <td>
                    <input type="checkbox" name="<?php echo $result->term_id ?>" value="yes" />
                    <?php
                    echo $result->name;
                    ?>
                </td>
            </tr>
            <?php
        }
        ?></tbody>
        <tfoot>
            <tr>
                <th scope="col" id="cb" class="widefat page fixed" width=30%>
                </th>
            </tr>
        </tfoot>
    </table>
    <BR>
    <input type="submit" name="create_group" id ="create_group" class="button-primary" value="<?php _e('Submit') ?>" />
</div>
</form>

    <SCRIPT LANGUAGE="JavaScript">
        function toggleCheckboxes(id) {
            if (!document.getElementById){ return; }
            if (!document.getElementsByTagName){ return; }
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
    </SCRIPT>

    <?php
    if(isset($_POST['create_group'])){
        $groupName = $_POST['groupName'];
        $newtag = $_POST['newtag'];
        
        if (($groupName != "") && ($groupName != NULL)){
            $result = write_group($_POST['groupName']);
            $groupID = fetch_groupID($groupName);
            $tagArray=NULL;
        
            if (($result == false))
            {
                $i=0;
                $tags = fetch_all_tags();
                foreach ($tags as $tag){
                    if(isset($_POST[$tag->term_id])){
                        $tagArray[$i] = $tag->term_id;
                        $groupArray[$i] = $groupID;
                        $i++;
                    }
                }

                if (($newtag != '') && ($newtag != NULL)){
                    updateTagsFromText($groupID, $newtag, $tagArray);
                }
                if (sizeof($tagArray) > 0)
                {
                    write_group_tags($groupArray, $tagArray);
                }
                echo "<div id='message' class='updated fade below-h2'>Group created.</div>";
            }
        }
        else{
            echo "<div id='error' class='error'>Group Name cannot be blank. Please try another.</div>";
        }
    }
}