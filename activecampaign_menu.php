<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2016 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * activecampaign menu file.
 *
 */




if (!defined('e107_INIT')) { exit; }

if(!e107::isInstalled('activecampaign'))
{
	return null;
}
/*
if(!ADMIN)
{
	return null;
}*/

/*
if(!empty($parm) )
{
	$text = print_a($parm,true); // e_menu.php form data.
}*/

$apref = e107::pref('activecampaign');

$frm = e107::getForm();

$text = '<div id="form_result_message"></div>';
$text .= "<div class='ac-subscribe-menu'>";
$text .= $frm->open('ac-subscribe-form');
$text .= '<div class="mt-3">'.$frm->text('first_name', '', 80, ['placeholder'=>'First name']).'</div>';
$text .= '<div class="mt-3">'.$frm->text('last_name', '', 80, ['placeholder'=>'Last name']).'</div>';
$text .= '<div class="mt-3">'.$frm->text('email', '', 80, ['id'=>'ac-email', 'placeholder'=>'your@email.com']).'</div>';
$text .= '<div class="mt-3">'.$frm->button('subscribe',1,'button','Subscribe!',['class'=>'btn btn-dark']).'</div>'; //,
$text .= $frm->close();
$text .= "</div>";


	$js = '

		$(document).ready(function() 
		{
			
			$("#ac-subscribe-form button#subscribe").click(function() {

				var buttonLabel = $(this).text();
				$(this).text("Saving...");
				$(this).prop("disabled",true);
				

				var form_data = {};
				$("#ac-subscribe-form").each(function() {
					form_data = $(this).serialize();			
				});
				


				var geturl;
				geturl = $.ajax({
					url: "'.e_PLUGIN_ABS.'activecampaign/subscribe.php", // the URL to this page.
					type: "POST",
					//dataType: "json",
					data: form_data,
					error: function(jqXHR, textStatus, errorThrown) {
						console.log("Error: " + textStatus);
					},
					success: function(data) {
					//	console.log(data); 
						$("#form_result_message").html(data);
						$("#ac-subscribe-form *").filter(":input").each(function(){
				            $(this).val("");
						});
						$("#ac-subscribe-form button#subscribe").text(buttonLabel);
						$("#ac-subscribe-form button#subscribe").prop("disabled",false);
					}
				});

			});

		});

';

e107::js('footer-inline', $js);


e107::getRender()->tablerender(varset($apref['caption'], "Subscribe"), $text);






