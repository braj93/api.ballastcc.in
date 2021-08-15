<?php $this->load->view('emailer/email-header');?>
        <p style="font-size:16px; color:#000000;">Hello,</p>
        <p style="font-size:16px; color:#000000;">Thank you so much for trusting us with your marketing needs.</p>

        <p style="font-size:16px; color:#000000;">You have been invited to join our Small Business Marketing Platform!</p>
        <p style="font-size:16px; color:#000000;">We’ve started your subscription and excited to help you get started right away.</p>
        <p style="font-size:16px; color:#000000;">Click the Complete Subscription Button below and you will have immediate access to the tools needed to take your business marketing to a whole new level!</p>
        <p style="font-size:16px; color:#000000;">Your email registered with the system is <?php echo $email;?></p>
        <p style="background-color: #f61400;padding: 10px 0px 10px;text-align: center;border-radius: 33px;">
            <a href="<?php echo SITE_ADDR . "users/signup-agency-member/$organization_member_guid?plan_id=$plan_guid" ?>" class="get-startedbtn" style="text-decoration:none;font-size: 23px; color: #fff;display: block;">COMPLETE SUBSCRIPTION</span></a>
        </p>
        <p style="font-size:16px; color:#000000;">After logging in, please review our Knowledge base where you will find several helpful videos on how to use the system and maximize your results.</p>
        <p style="font-size:16px; color:#000000;">Our solution is built based on helpful comments from subscribers like you.  We would very much welcome any and all feedback you have to share.</p>
<?php $this->load->view('emailer/email-footer');?>