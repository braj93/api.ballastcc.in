<?php $this->load->view('emailer/email-header');?>
        <p style="font-size:16px; color:#000000;">Hello <span style="color:#f61400;font-weight: 600;"><?php echo ucfirst(strtolower($name)); ?></span>,</p>
        <p style="font-size:16px; color:#000000;">We’ve received a request to reset the password for the Marketing Tiki account associated with <?php echo $email;?>. No changes have been made to your account yet.</p>
        <p style="font-size:16px; color:#000000;">You can reset your password by clicking the link below:</p>
        <p style="background-color: #f61400;padding: 10px 0px 10px;text-align: center;border-radius: 33px;">
            <a href="<?php echo SITE_ADDR.'users/set-new-password/'.$unique_code; ?>" class="get-startedbtn" style="text-decoration:none;font-size: 23px; color: #fff;display: block;">RESET</span></a>
        </p>
        <p style="font-size:16px; color:#000000;">If you did not request a new password, please let us know immediately by replying to this email.</p>
<?php $this->load->view('emailer/email-footer');?>