
<div class="landing-template">
    <div class="header-banner">
        <div class="image-holder-block">
            <img src="<?php echo  $campaign['template_values']['banner_background_image']['image_url'] == '' ? site_url('assets/img/cover.jpeg') :  $campaign['template_values']['banner_background_image']['image_url']; ?>">
        </div>
        <div class="top-content-bar">
            <h2 class="page-title">
                <div><?php echo $campaign['template_values']['header']['title']['value']; ?></div>
            </h2>
            <a class="call-number add-call" href="tel:<?php echo $campaign['template_values']['header']['number']['value']; ?>"><span><img src="<?php echo  site_url('assets/img/landing-page/call.svg'); ?>" /></span>
                <?php echo $campaign['template_values']['header']['number']['value']; ?>
            </a>
        </div>
        <div class="landing-banner-content">
            <div class="banner-text-block text-center">
                <div>
                    <h2><?php echo $campaign['template_values']['banner']['title']['value']; ?></h2>
                </div>
                <div>
                    <p><?php echo $campaign['template_values']['banner']['description']['value']; ?></p>
                </div>
                <div>
                    <a target="_blank" href="<?php echo $campaign['template_values']['banner']['cta']['link']; ?>" class="btn btn-primary landing-btn"><?php echo $campaign['template_values']['banner']['cta']['value']; ?></a>
                </div>
            </div>
        </div>
    </div>

    <div class="feature-news-feed-section l-padding-md bg-grey">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="landing-title-block text-center mb-5">
                        <div>
                            
                            <h2>Section Title</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8">
                    <div class="feature-news-section">
                        <div class="featured-img">
                            <img src="https://rioaggregate.com/wp-content/uploads/sites/7/2019/10/about-background.png" />
                        </div>
                        <div class="text-block">
                            <span><?php echo $campaign['template_values']['featured']['title']['value']; ?></span>
                            <h4><?php echo $campaign['template_values']['featured']['title']['value']; ?></h4>
                            <a target="_blank" href="<?php echo $campaign['template_values']['featured']['cta']['link']; ?>" class="inline-btn"><?php echo $campaign['template_values']['featured']['cta']['value']; ?></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="sidebar-feed pl-sm-5">
                        <div>
                            <h3><?php echo $campaign['template_values']['feeds']['title']['value']; ?></h3>
                        </div>
                        <ul>
                            <li>
                                <span><?php echo $campaign['template_values']['feeds']['one']['category']['value']; ?></span>
                                <a><?php echo $campaign['template_values']['feeds']['one']['title']['value']; ?></a>
                            </li>
                            <li>
                                <span><?php echo $campaign['template_values']['feeds']['two']['category']['value']; ?></span>
                                <a><?php echo $campaign['template_values']['feeds']['two']['title']['value']; ?></a>
                            </li>
                            <li>
                                <span><?php echo $campaign['template_values']['feeds']['three']['category']['value']; ?></span>
                                <a><?php echo $campaign['template_values']['feeds']['three']['title']['value']; ?></a>
                            </li>
                            <li>
                                <span><?php echo $campaign['template_values']['feeds']['four']['category']['value']; ?></span>
                                <a><?php echo $campaign['template_values']['feeds']['four']['title']['value']; ?></a>
                            </li>
                            <li>
                                <span><?php echo $campaign['template_values']['feeds']['five']['category']['value']; ?></span>
                                <a><?php echo $campaign['template_values']['feeds']['five']['title']['value']; ?></a>
                            </li>
                            <li>
                                <span><?php echo $campaign['template_values']['feeds']['six']['category']['value']; ?></span>
                                <a><?php echo $campaign['template_values']['feeds']['six']['title']['value']; ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-section-block l-padding-md">
        <div class="container">
            <div class="form-container text-center">
                <div class="landing-title-block">
                    <div>
                        <h2><?php echo $campaign['template_values']['contact_us']['title']['value']; ?></h2>
                    </div>
                    <form id="cform" method="POST">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="field-block">
								<input type="text" class="landing-input" placeholder="Name" name="name" required>
								<?php echo "<span style='color:red'>".form_error('name')."</span>"; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-block">
								<input type="email" class="landing-input" placeholder="Email" name="email" required>
								<?php echo "<span style='color:red'>".form_error('email')."</span>"; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-block">
								<input type="tel" class="landing-input" placeholder="Phone" name="phone" required>
								<?php echo "<span style='color:red'>".form_error('phone')."</span>"; ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="field-block">
                                <input type="text" class="landing-input" placeholder="Message" name="message" required>
								<?php echo "<span style='color:red'>".form_error('message')."</span>"; ?>
                            </div>
                        </div>
                        
                        <div class="push-notification col-sm-12">
                            <input id="is_sent_notification_id" type="checkbox" name="is_sent_notification" checked/>
                            <label for="is_sent_notification_id">
                            Send Push Notification upon lead submission
                            </label>
                        </div>

                        <div class="col-md-12">
                            <div class="submit-btn-block mt-4">
                                <div>
								<button class="landing--submit-btn" <?php if($campaign['preview']){ echo 'disabled';};?>><?php echo $campaign['template_values']['contact_us']['button']['value']; ?></button>
								<span class="loader-block ml-2"  style="display:none;"><img src="http://i.stack.imgur.com/FhHRx.gif" alt="Loading"></span>
								<h6 class="success-message mt-4 text-center" style="display:none; color:#4BB543" >Message Sent Successfully</h6>
								<h6 class="error-message mt-4" style="display:none; color:#dc3545" >Something Went Wrong</h6>
								</div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>