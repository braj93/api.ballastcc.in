<?php $this->load->view('emailer/email-header');?>
        <p style="font-size:16px; color:#000000;">Hello <span style="color:#f61400;font-weight: 600;"><?php echo ucfirst(strtolower($name)); ?></span>,</p>
        <p style="font-size:16px; color:#000000;">Thank you so much for trusting us with your marketing needs.  We’ve received your subscription for Marketing Tiki and excited to help you get started right away.</p>
        <p style="font-size:16px; color:#000000;">You will have immediate access to the tools needed to take your business marketing to a whole new level!</p>
        <p style="font-size:16px; color:#000000;">Your email registered with the system is <?php echo $email;?></p>
        <p style="font-size:16px; color:#000000;">Your password was set during signup but can be changed any time by clicking on the forgot password link</p>
        <p style="background-color: #f61400;padding: 10px 0px 10px;text-align: center;border-radius: 33px;">
            <a href="<?php echo SITE_ADDR; ?>" class="get-startedbtn" style="text-decoration:none;font-size: 23px; color: #fff;display: block;">Login Now</span></a>
        </p>
        <p style="font-size:16px; color:#000000;">After logging in, please review our Knowledge base where you will find several helpful videos on how to use the system and maximize your results. </p>
        <p style="font-size:16px; color:#000000;">We are also available from 9 am - 6pm PST time (often much later) via chat on MarketingTiki&#46;com and ready to help you be successful.
Chat or email is often the best way to contact us and often replied to after our standard business hours.</p>
        <p style="font-size:16px; color:#000000;">Our solution is built based on our years of marketing experience in addition to the extremely helpful comments from subscribers like you.  We would very much welcome any and all feedback you have to share.</p>
        <p style="font-size:16px; color:#000000;">You can let us know your feedback by replying to this email or even on a chat session.</p>
<?php $this->load->view('emailer/email-footer');?>