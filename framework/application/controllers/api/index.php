<?php /* framework/application/controllers/api/index.php */

# Get the API Class.
require_once Utility::locateFile(MODULES.'API'.DS.'API.php');
# Create a new API object.
$api_obj=new API();

$api_key=(isset($_REQUEST['api_key']) ? $_REQUEST['api_key'] : '');
$api_obj->validateServerAPIKey($api_key);
$context=(isset($_REQUEST['api_key']) ? $_REQUEST['api_context'] : '');

# Insert application specific code
/*
Example to change content in a <div> box using drop-down boxes:

# PHP (this file):
	if($context=='change_div_content')
	{
		$drop_down_box1=$_REQUEST['drop_down_box1'];
		$callback=$_REQUEST['callback'];
		$api_obj->changeContent($drop_down_box1, $callback);
	}

# JS (javascript file):
    function changeContent() {
        var drop_down_box1_sel = $('#drop_down_box1 option:selected').val();
        var api_context = 'changeContent';
        $.ajax({
            type: 'POST',
            url: '".APPLICATION_URL."api/index.php',
            dataType: 'jsonp',
            jsonpCallback: 'callback_changeContent',
            data: {
                api_key: 'API_KEY',
                drop_down_box1: drop_down_box1_sel,
                api_context: api_context
            },
            success: function (data) {
                $('.content-box').html(data.response.content_box);
                Success = true;
            },
            error: function (textStatus, errorThrown) {
                Success = false;
            }
        });
    };

# HTML (form/controller files):
	<form>
	  <select name="drop_down_box1" id="drop_down_box1">
	    <option></option>
	  </select>
	</form>
	<div class="content-box"></div>
*/