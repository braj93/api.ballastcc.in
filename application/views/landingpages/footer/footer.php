<?php if(!$campaign['preview']){ echo $campaign['page_script']; };?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<script type="text/javascript">
    $('form').submit(function(e) {
        e.preventDefault();
	   var campaign_guid = "<?php echo $campaign['campaign_guid']; ?>";
       var name = $("input[name='name']").val();
       var email = $("input[name='email']").val();
       var phone = $("input[name='phone']").val();
       var message = $("input[name='message']").val();
       var is_sent_notification = $("input[name='is_sent_notification']").is(":checked");
       var subject = "<?php echo $campaign['campaign_subject']; ?>";
       var is_qr_code = "<?php echo $campaign['is_qr_code']; ?>";
       $(".loader-block").css('display','inline-block');
        $.ajax({
           url: "<?php echo base_url('landingpage/cantactus_submit'); ?>",
           type: 'POST',
           data: {name: name, email: email, phone:phone, message:message, subject:subject, campaign_guid:campaign_guid, is_qr_code:is_qr_code, is_sent_notification:is_sent_notification},
           error: function() {
            // $("#cform")[0].reset();
            $(".error-message").css('display','block');
            $(".loader-block").css('display','none');
            $(".success-message").css('display','none');
           },
           success: function(data) {
            $(".success-message").css('display','block');
            $(".loader-block").css('display','none');
            $(".error-message").css('display','none');
            $("#cform")[0].reset();
           }
        });
    });
</script>
</body>
</html>