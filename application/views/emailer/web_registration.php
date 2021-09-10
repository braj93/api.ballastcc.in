<?php $this->load->view('emailer/email-header');?>
    <p style="font-family:Calibri, Arial, sans-serif; font-size:16px; color:#444444;">Dear <span style="color:#444444;font-weight: 600;"><?php echo $name; ?></span>,</p>
    <p style="font-family:Calibri, Arial, sans-serif; font-size:16px; color:#444444;">Get Started. Activate your Account!!!</p>
    <p style="font-family:Calibri, Arial, sans-serif; font-size:16px; color:#444444;">Welcome to Marketing Tiki.</p>

    <p style="margin: 0 0 10px 0; padding: 0;padding: 0px 10px; text-align: center;border-radius: 33px;height: 52px; line-height: 52px;" class="get-start-btn">
    <?php echo SITE_ADDR.'/verify-email/'.$unique_code; ?>
        <a href="<?php echo SITE_ADDR.'/verify-email/'.$unique_code; ?>" class="get-startedbtn" style="outline: none; border:0px; display: block;">
            Verify Email
        </a>
    </p>
<?php $this->load->view('emailer/email-footer');?>