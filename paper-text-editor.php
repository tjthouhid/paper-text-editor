<?php
/* 
Plugin Name: Paper Text Editor 
Plugin URI: https://github.com/tjthouhid/paper-text-editor/
Description: This Plugin paper text editor
Version: 1.0.1 
Author: Tj Thouhid 
Author URI: https://www.tjthouhid.me/
License: GPLv2 or later 
*/


function paper_text_editor_view($atts = array())
{

    global $wpdb;
    extract(shortcode_atts(array(
      	'buttontext1' => 'Send',
      	'buttontext2' => 'Send',
      	'longtext' => 'Your Text Here.',
	
   ), $atts));
    $tx_compay_email = get_option('tx_compay_email');
	$tx_image_id = get_option('tx_image_id');
	$tx_fonts = get_option('tx_fonts');
	$tx_btn1_color = get_option('tx_btn1_color');
	$tx_btn2_color = get_option('tx_btn2_color');
    ob_start();
    include 'templates/view.php';
    $content = ob_get_clean();
    return $content;
}
add_shortcode('paper-text-editor', 'paper_text_editor_view');

add_action("wp_ajax_send_user_text_email", "send_user_text_email");
add_action("wp_ajax_nopriv_send_user_text_email", "send_user_text_email");
function send_user_text_email() {
	$name = $_REQUEST["name"];
	$email = $_REQUEST["email"];
	$phone = $_REQUEST["phone"];
	$text_value = $_REQUEST["text_value"];
	$tx_compay_email = get_option('tx_company_email');

	$message = "Name : ".$name. "\r\n";
	$message .= "Email : ".$email. "\r\n";
	if($phone!=""){
		$message .= "Phone : ".$phone. "\r\n";
	}
	$message .= "Text : ".$text_value. "\r\n";

	$to = $tx_compay_email;
	$subject = get_option('tx_company_email_subject');
	$headers = 'From: '. $email . "\r\n" .
    'Reply-To: ' . $email . "\r\n";
    $sent = wp_mail($to, $subject, strip_tags($message), $headers);
      if($sent) {
      	$result['type'] = "success";
      	$result['msg'] = get_option('tx_success_msg');;
      }//message sent!
      else  {
      	$result['type'] = "error";
      	$result['msg'] = "Mail Sent Failed";
      }//message wasn't sent

      $result = json_encode($result);
      echo $result;
      die();
}

add_action( 'admin_menu', 'paper_text_setting' );

function paper_text_setting() {
	$page_title='Paper Text';
	$menu_title='Paper Text';
	$capability=1;
	$menu_slug='paper_text_setting';
	$function='paper_text_setting_template';
	$icon_url='';
	$position=68.9;
	
	add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
}
function paper_text_setting_template(){
	global $wpdb;
	if(isset($_POST['update'])){
        update_option('tx_company_email_subject',$_POST['tx_company_email_subject']);
        update_option('tx_company_email',$_POST['tx_company_email']);
        update_option('tx_image_id',$_POST['tx_image_id']);
        update_option('tx_success_msg',$_POST['tx_success_msg']);
        update_option('tx_fonts',$_POST['tx_fonts']);
        update_option('tx_btn1_color',$_POST['tx_btn1_color']);
        update_option('tx_btn2_color',$_POST['tx_btn2_color']);
    }
	$tx_company_email_subject = get_option('tx_company_email_subject');
	if($tx_company_email_subject ==""){
		$tx_company_email_subject = "Sent Mail From Paper Text";
	}
	$tx_company_email = get_option('tx_company_email');
	if($tx_company_email ==""){
		$tx_company_email = "tjthouhid@gmail.com";
	}
	$tx_image_id = get_option('tx_image_id');
	$tx_fonts = get_option('tx_fonts');
	$tx_btn1_color = get_option('tx_btn1_color');
	if($tx_btn1_color ==""){
		$tx_btn1_color = "#f6c9c9";
	}
	$tx_btn2_color = get_option('tx_btn2_color');
	if($tx_btn2_color ==""){
		$tx_btn2_color = "#f6c9c9";
	}
	$tx_success_msg = get_option('tx_success_msg');
	if($tx_success_msg ==""){
		$tx_success_msg = "Message sent successfully!";
	}
	
	?>
	<style type="text/css">
		.ctable{
			margin: 60px 0px;
		}
		.ctable tr{}
		.ctable tr th{
			padding: 20px 0px;
    		text-align: left;
		}
		.ctable tr td{
			padding: 20px 20px;
		}
		.ctable input{
			width: 300px;
		}
		.ctable img{
			width: 120px;
		}
		.ctable a{
			display: block;
		}
		.ctable textarea{
			width: 300px;
		    height: 160px;
		    resize: none;
		}
		.ctable button{
			background-color: #4CAF50; /* Green */
			border: none;
			color: white;
			padding: 15px 32px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 16px;
			margin-top: 20px;
		}
		.ctable button:hover{
			background-color: #64d668; /* Green */
			cursor: pointer;
		}
		.code-exm{
			background-color: #ffff;
		    display: block;
		    margin: 15px 0px;
		    color: #c12a2a;
		    font-size: 20px;
		    padding: 15px 10px;
		}
		.code-instruction{
			font-size: 18px;
		    color: #716767;
		    text-decoration: underline;
		    text-align: center;
		    display: block;
		}
	</style>
	<script type="text/javascript">
		jQuery(function($){

			// on upload button click
			$('body').on( 'click', '.misha-upl', function(e){

				e.preventDefault();

				var button = $(this),
				custom_uploader = wp.media({
					title: 'Insert image',
					library : {
						// uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
						type : 'image'
					},
					button: {
						text: 'Use this image' // button label text
					},
					multiple: false
				}).on('select', function() { // it also has "open" and "close" events
					var attachment = custom_uploader.state().get('selection').first().toJSON();
					button.html('<img src="' + attachment.url + '">').next().val(attachment.id).next().show();
				}).open();
			
			});

			// on remove button click
			$('body').on('click', '.misha-rmv', function(e){

				e.preventDefault();

				var button = $(this);
				button.prev().val(''); // emptying the hidden field
				button.hide().prev().prev().html('Upload image');
			});

		});
	</script>
	<h1>Paper Text Usage</h1>
	<code class="code-exm">
		[paper-text-editor]
	</code>
	<code class="code-exm">
		[paper-text-editor buttontext1="Send 1" buttontext2="Send 2" longtext="Your Text Here"]
	</code>
	<br>
	<span class="code-instruction"><strong>NB : </strong> buttontext1 And buttontext2 is Optional for changing buttons text on frontend. <br> longtext is also Optional for changing moodal text on frontend.</span>
	<h1>Paper Text Setting</h1>
	<form action="" method="POST">
	<table class="ctable">
		<tr>
			<th>Choose Font: </th>
			<td>
				<select name="tx_fonts">
					<option value="default">Default</option>
					<option <?php if($tx_fonts=="Allison"){ echo "selected";}?> value="Allison">Allison</option>
					<option <?php if($tx_fonts=="Klee One"){ echo "selected";}?> value="Klee One">Klee One</option>
					<option <?php if($tx_fonts=="Amatic SC"){ echo "selected";}?> value="Amatic SC">Amatic SC</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>Button 1 Color : </th>
			<td><input type="color" name="tx_btn1_color" value="<?php echo $tx_btn1_color;?>"></td>
		</tr>
		<tr>
			<th>Button 2 Color : </th>
			<td><input type="color" name="tx_btn2_color" value="<?php echo $tx_btn2_color;?>"></td>
		</tr>
		<tr>
			<th>Email Subject: </th>
			<td><input type="text" name="tx_company_email_subject" value="<?php echo $tx_company_email_subject;?>"></td>
		</tr>
		<tr>
			<th>Company Email : </th>
			<td><input type="email" name="tx_company_email" value="<?php echo $tx_company_email;?>"></td>
		</tr>
		<tr>
			<th>Stamp Image :</th>
			<td>
				<?php if( $image = wp_get_attachment_image_src( $tx_image_id ) ) {

					echo '<a href="#" class="misha-upl"><img src="' . $image[0] . '" /></a>
					      <input type="hidden" name="tx_image_id" value="' . $tx_image_id . '">
					      <a href="#" class="misha-rmv">Remove image</a>';

				} else {

					echo '<a href="#" class="misha-upl">Upload image</a>
						  <input type="hidden" name="tx_image_id" value="">
						  <a href="#" class="misha-rmv" style="display:none">Remove image</a>';

				} ?>
			</td>
		</tr>
		<tr>
			<th>Success Message: </th>
			<td><input type="text" name="tx_success_msg" value="<?php echo $tx_success_msg;?>"></td>
		</tr>
		<tr>
			<td>
			</td>
			<td>
				<button type="submit" name="update">Update</button>
			</td>
		</tr>
	</table>
	</form>
	<?php
}

function load_media_files() {
    wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'load_media_files' );