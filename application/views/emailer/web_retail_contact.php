<?php $this->load->view('emailer/email-header');?>
    <p style="font-family:Calibri, Arial, sans-serif; font-size:16px; color:#444444;">Hello,</p>
    <p style="font-size:16px; color:#000000;">Name: <span style="color:#f61400;font-weight: 600;"><?php echo ucfirst(strtolower($name)); ?></span>,</p>
    <p style="font-size:16px; color:#000000;">Email: <span style="color:#f61400;font-weight: 600;"><?php echo $email; ?></span>,</p>
    <p style="font-size:16px; color:#000000;">Phone: <span><?php echo $phone; ?></span>,</p>
    <p style="font-size:16px; color:#000000;">Message: <span><?php echo $contact_message; ?></span>,</p>
<?php $this->load->view('emailer/email-footer');?>