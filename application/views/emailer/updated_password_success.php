<?php $this->load->view('emailer/email-header');?>
        <p style="font-size:16px; color:#000000;">Hello <span style="color:#f61400;font-weight: 600;"><?php echo ucfirst(strtolower($name)); ?></span>,</p>
        <p style="font-size:16px; color:#000000;">We’ve received confirmation of your password change on Marketing Tiki. Your Account has been updated with your new password.</p>
        <p style="font-size:16px; color:#000000;">If you did not request a new password, please let us know immediately by replying to this email.</p>
<?php $this->load->view('emailer/email-footer');?>