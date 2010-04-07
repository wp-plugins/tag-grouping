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

    <div class="inside">
<div id="post_tag" class="tagsdiv">
	<div class="jaxtag">
	<div class="nojs-tags hide-if-js">
	<p>Add or remove tags</p>
	<textarea id="tax-input[post_tag]" class="the-tags" name="tax_input[post_tag]">class="error",class="updated fade",completion message,error message,id="error",id="message",message,plug-in,plugin,submit message,update message,Wordpress,wordpress error handling,wordpress plugin</textarea></div>

	<div class="ajaxtag hide-if-no-js">
		<label for="new-tag-post_tag" class="screen-reader-text">Post Tags</label>
		<div class="taghint">Add new tag</div>
		<input type="text" value="" autocomplete="off" size="16" class="newtag form-input-tip" name="newtag[post_tag]" id="new-tag-post_tag">
		<input type="button" tabindex="3" value="Add" class="button tagadd">
	</div></div>
	<p class="howto">Separate tags with commas.</p>
	<div class="tagchecklist"><span><a class="ntdelbutton" id="post_tag-check-num-0">X</a>&nbsp;class="error"</span> <span><a class="ntdelbutton" id="post_tag-check-num-1">X</a>&nbsp;class="updated fade"</span> <span><a class="ntdelbutton" id="post_tag-check-num-2">X</a>&nbsp;completion message</span> <span><a class="ntdelbutton" id="post_tag-check-num-3">X</a>&nbsp;error message</span> <span><a class="ntdelbutton" id="post_tag-check-num-4">X</a>&nbsp;id="error"</span> <span><a class="ntdelbutton" id="post_tag-check-num-5">X</a>&nbsp;id="message"</span> <span><a class="ntdelbutton" id="post_tag-check-num-6">X</a>&nbsp;message</span> <span><a class="ntdelbutton" id="post_tag-check-num-7">X</a>&nbsp;plug-in</span> <span><a class="ntdelbutton" id="post_tag-check-num-8">X</a>&nbsp;plugin</span> <span><a class="ntdelbutton" id="post_tag-check-num-9">X</a>&nbsp;submit message</span> <span><a class="ntdelbutton" id="post_tag-check-num-10">X</a>&nbsp;update message</span> <span><a class="ntdelbutton" id="post_tag-check-num-11">X</a>&nbsp;Wordpress</span> <span><a class="ntdelbutton" id="post_tag-check-num-12">X</a>&nbsp;wordpress error handling</span> <span><a class="ntdelbutton" id="post_tag-check-num-13">X</a>&nbsp;wordpress plugin</span> </div>
</div>
<p class="hide-if-no-js"><a id="link-post_tag" class="tagcloud-link" href="#titlediv">Choose from the most used tags in Post Tags</a></p>
</div>

    <input type="submit" name="create_group" id ="create_group" class="button-primary" value="<?php _e('Submit') ?>" />
</div>
</form>
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
                echo "<div id='message' class='updated fade'>Group created.</div>";
            }
        }
        else{
            echo "<div id='error' class='error'>Group Name cannot be blank. Please try another.</div>";
        }
    }
}