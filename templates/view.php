<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url(__FILE__)."css/view.css";?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<?php if($tx_fonts=="Allison"){ ?>
	<link href="https://fonts.googleapis.com/css2?family=Allison&display=swap" rel="stylesheet">
	<style type="text/css">.paper textarea{font-family: 'Allison', cursive;}</style>
<?php } else if($tx_fonts=="Klee One"){ ?>
	<link href="https://fonts.googleapis.com/css2?family=Klee+One&display=swap" rel="stylesheet">
	<style type="text/css">.paper textarea{font-family: 'Klee One', cursive;}</style>
<?php } else if($tx_fonts=="Amatic SC"){ ?>
	<link href="https://fonts.googleapis.com/css2?family=Amatic+SC&display=swap" rel="stylesheet">
	<style type="text/css">.paper textarea{font-family: 'Amatic SC', cursive;}</style>
<?php } else { }?>


<div class="view-container">
	<form action="" method="post">
		<div class="paper">
		    <div class="holes"></div>
		    <textarea placeholder="Please Type Here" id="text_value"></textarea>
		</div>
		<button id="openForm" type="button" class="t-button" style="background-color: <?php echo $tx_btn1_color;?>"><?php echo $buttontext1;?></button>
	</form>
	<!-- The Modal -->
	<div id="sendText" class="t-modal">
		<!-- Modal content -->
	  	<div class="t-modal-content">
	    	<span class="t-close">&times;</span>
	    	<div class="modal-form">
	    		<?php if( $image = wp_get_attachment_image_src( $tx_image_id ) ) { ?>
	    			<img src="<?php echo $image[0];?>">
	    		<?php }?>
	    		<form action="" method="post">
	    			<p><?php echo $longtext;?></p>
	    			<label>Name</label>
	    			<input type="text" name="name" id="name">
	    			<label>Email</label>
	    			<input type="email" name="email" id="email">
	    			<label>Phone <span>(Optional)</span></label>
	    			<input type="text" name="phone" id="phone">
	    			<button id="send" type="button" class="t-button" style="background-color: <?php echo $tx_btn1_color;?>"><?php echo $buttontext2;?></button>

	    		</form>
	    	</div>
	  	</div>
	</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
	var ajaxUrl = "<?php echo admin_url( 'admin-ajax.php' );?>";
jQuery(function($){
	$("#send").on("click",function(e){
		e.preventDefault();
		var text_value = $("#text_value");
		var name = $("#name");
		var email = $("#email");
		var phone = $("#phone");
		if(name.val() == ""){
			alert("Please Enter Name");
			return false;
		}
		if(email.val() == ""){
			alert("Please Enter Email");
			return false;
		}
		if(!validateEmail(email.val())){
			alert("Please Enter Valid Email");
			return false;
		}

		$.ajax({
			type : "post",
		    dataType : "json",
		    url : ajaxUrl,
		    data : {
		    	action: "send_user_text_email", 
		    	name : name.val(), 
		    	email : email.val(), 
		    	phone : phone.val(),
		    	text_value : text_value.val()
		    },
		    success: function(response) {
		    	if(response.type == "success") {
		    		alert(response.msg)
		        }else{
		        	alert(response.msg);
		        }
		        location.reload();
		    }
		});   

	});

});
function validateEmail(email) {
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}
	// Get the modal
var modal = document.getElementById("sendText");

// Get the button that opens the modal
var btn = document.getElementById("openForm");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("t-close")[0];


// When the user clicks on the button, open the modal
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>