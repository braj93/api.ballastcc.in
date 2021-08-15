<?php $this->load->view('emailer/email-header');?>
    <p style="font-family:Calibri, Arial, sans-serif; font-size:16px; color:#444444;">Hello <span style="color:#444444;font-weight: 600;"><?php echo ucfirst(strtolower($member)); ?></span>,</p>
	<div style="margin-bottom: 10px;">
	    <button style="background-color: #0550A2; color: #fff; font-size: 17px; width: 100%; outline: none; border: none; padding: 13px 0; border-radius: 5px;">
	        <a href="<?php echo SITE_ADDR ?>" style="outline: none; border:0px; display: block; color: #fff; text-decoration: none;">Sign in!</a>
	    </button>
	</div>

<?php $this->load->view('emailer/email-footer');?>