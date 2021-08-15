<?php $this->load->view('emailer/email-header');?>
        <p style="font-size:16px; color:#000000;">Hello <span style="color:#f61400;font-weight: 600;"><?php echo ucfirst(strtolower($name)); ?></span>,</p>
        <p style="font-size:16px; color:#000000;"><?php echo $broadcast_message;?></p>
<?php $this->load->view('emailer/email-footer');?>