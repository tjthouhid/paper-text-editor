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
      	'buttontext' => 'Find',
	    'labeltext' => 'Zip Code',
	    'phtext' => 'Type Your Zip Code Here...',
   ), $atts));
    if(isset($_POST['check_zip'])){
    	$zip = $_POST['zipcode'];
    	
    	$table_name = $wpdb->prefix . 'zip_list';
	    $result = $wpdb->get_results( "SELECT * FROM $table_name WHERE zip='$zip'" );
	    if(count($result)>0){
	        $success_url = get_option('zip_success_url');
	        ?>
	        <script type="text/javascript">
				var url = "<?php echo $success_url;?>";
				window.location.href = url;
			</script>
			<?php 
	    }else{
	        $failure_url = get_option('zip_failure_url');
	        ?>
	        <script type="text/javascript">
				var url = "<?php echo $failure_url;?>";
				window.location.href = url;
			</script>
			<?php 
	    }
    	exit;
    }
    ob_start();
    include 'templates/view.php';
    $content = ob_get_clean();
    return $content;
}
add_shortcode('paper-text-editor', 'paper_text_editor_view');