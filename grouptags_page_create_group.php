<?php

function group_tag_admin_menu(){
    add_menu_page('Manage your Tags', 'Group Tags', 'administrator', 'taggroup', 'group_tag_admin', get_bloginfo('siteurl')."/wp-content/plugins/tag-grouping/images/Group-icon.png");
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
<h2>Create a Group</h2></div>
<p class="wp-caption-text">Type the Group Name. Select the tags you would like to assign to the group. Click "Check all Tags" to select all tags in the list. Click Create.
<BR><BR>When you are creating or editting a Post, the <B>Tag Groups</B> are listed in the bottom-right of the screen.</p>
<div width=50%; align=left>
    <form method="post" name="tag_selections">
    <table class="form-table"><BR>
        Group Name:<input type="text" name="groupName" id="groupName" />
        <input type="submit" name="create_group" id ="create_group1" class="button-primary" value="<?php _e('Create') ?>" />
    </table>
</div>
<BR><BR>
<?php
$results = fetch_all_tags();
?>
<div class="wrap">
    <table class="widefat page fixed">
        <thead>
            <tr>
                <th scope="col" id="cb" class="widefat page fixed" style="" width=30%>
                    <input type="button" value="<?php _e('Check all Tags')?>" onClick="toggleCheckboxes('parent_box')">
                </th>
            </tr>
        </thead>
        <tbody id="parent_box">
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
            <tr><th scope="col" id="cb" class="widefat page fixed" style="" width=30%>
                    <input type="button" value="<?php _e('Check all Tags')?>" onClick="toggleCheckboxes('parent_box')">
                </th>
            </tr>
        </tfoot>
    </table>
    <BR>
    <input type="submit" name="create_group" id ="create_group" class="button-primary" value="<?php _e('Create') ?>" />
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
    </SCRIPT>

    <?php
    if(isset($_POST['create_group'])){
        $groupName = $_POST['groupName'];

        if (($groupName != "") && ($groupName != NULL)){
            $result = write_group($_POST['groupName']);
            $group = $_POST['groupName'];

            echo "<script>alert('$group')</script>";
            $groupID = fetch_groupID($group);
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