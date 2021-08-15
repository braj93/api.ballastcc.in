<?php $this->load->view('emailer/email-header');?>
        <p style="font-size:16px; color:#000000;">Hello,</p>
        <p style="font-size:16px; color:#000000;"><?php echo $agency_name; ?> has added you as a new team member for Marketing Tiki, please click the below link to finish the registration process</p>
        <p style="background-color: #f61400;padding: 10px 0px 10px;text-align: center;border-radius: 33px;">
            <a href="<?php echo SITE_ADDR . "users/signup-team-member/$organization_member_guid" ?>" class="get-startedbtn" style="text-decoration:none;font-size: 23px; color: #fff;display: block;">CLICK HERE</span></a>
        </p>
<?php $this->load->view('emailer/email-footer');?>.