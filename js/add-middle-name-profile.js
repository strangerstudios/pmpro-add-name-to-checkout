jQuery(function($) {
	var html = '<tr class="user-middle-name-wrap">';
	html += '<th><label for="middle_name">' + pmproan2c.middle_name_string + '</label></th>';
	html += '<td><input type="text" name="middle_name" id="middle_name" value="' + pmproan2c.middle_name + '" class="regular-text"></td>';
	html += '</tr>';
	$('.user-first-name-wrap').after( html );
});