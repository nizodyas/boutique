</div><br><br>




 <footer class="text-center" id="footer">&copy; Copyright 2013-1017 Shanta's boutique </footer>





     
    
  <script>
	jQuery(window).scroll(function()
	{
		var vscroll = jQuery(this).scrollTop();
		jQuery('#logo-text').css({
			"transform" :"translate(0px, "+vscroll/2+"px)"
		});


        var vscroll = jQuery(this).scrollTop();

        jQuery('#back-flower').css({
			"transform" :"translate(0px, "+vscroll/12+"px)"
		});

        var vscroll = jQuery(this).scrollTop();
        jQuery('#fore-flower').css({
			"transform" :"translate(0px, "+vscroll/2+"px)"
		});
	});

	function details_modal(id){
		var data= {"id" : id };

		jQuery.ajax({
            url : '/project/includes/details_modal.php',
			method : "post" ,
			data : data ,
			success : function(data){
				jQuery('body').append(data);
				jQuery('#details_modal').modal('toggle');
			},
			error : function(){
				alert("something went wrong");
			}

		});
	}

	
</script> 

</body>
</html>

