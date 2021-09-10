<div class="retail-template">
    <div class="landing-template">
        <div class="header-banner">
            <div class="image-holder-block">
                <img src="<?php echo  $campaign['template_values']['banner_background_image']['image_url'] == '' ? site_url('assets/img/cover.jpeg') :  $campaign['template_values']['banner_background_image']['image_url']; ?>">
            </div>
            <div class="top-content-bar">
                <h2 class="page-title">
                    <div><?php echo $campaign['template_values']['header']['title']['value']; ?></div>
                </h2>
                <a href="tel:<?php echo $campaign['template_values']['header']['number']['value']; ?>" class="call-number add-call"><span><img src="<?php echo  site_url('assets/img/landing-page/call.svg'); ?>" /></span>
                    <div><?php echo $campaign['template_values']['header']['number']['value']; ?></div>
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
    
        <div class="events-listing-section l-padding-md bg-dark">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="landing-title-block text-center mb-5">
                            <div>
                                <h2><?php echo $campaign['template_values']['three_column_content_promo']['title']['value']; ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="event-block">
                            <div class="event-image">
                                <img src="<?php echo  $campaign['template_values']['three_column_content_promo']['one']['image']['image_url'] == '' ? site_url('assets/img/faces/marc.jpg') :  $campaign['template_values']['three_column_content_promo']['one']['image']['image_url']; ?>">
                            </div>
                            <span class="tag-name"><?php echo $campaign['template_values']['three_column_content_promo']['one']['category']['value']; ?></span>
                            <div class="event-content">
                                <!-- <span class="date">February 3, 2017</span> -->
                                <h6><?php echo $campaign['template_values']['three_column_content_promo']['one']['title']['value']; ?></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="event-block">
                            <div class="event-image">
                                <img src="<?php echo  $campaign['template_values']['three_column_content_promo']['two']['image']['image_url'] == '' ? site_url('assets/img/faces/marc.jpg') :  $campaign['template_values']['three_column_content_promo']['two']['image']['image_url']; ?>">
                            </div>
                            <span class="tag-name"><?php echo $campaign['template_values']['three_column_content_promo']['two']['category']['value']; ?></span>
                            <div class="event-content">
                                <!-- <span class="date">February 3, 2017</span> -->
                                <h6><?php echo $campaign['template_values']['three_column_content_promo']['two']['title']['value']; ?></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="event-block">
                            <div class="event-image">
                                <img src="<?php echo $campaign['template_values']['three_column_content_promo']['three']['image']['image_url'] == '' ? site_url('assets/img/faces/marc.jpg') : $campaign['template_values']['three_column_content_promo']['three']['image']['image_url']; ?>">
                            </div>
                            <span class="tag-name"><?php echo $campaign['template_values']['three_column_content_promo']['three']['category']['value']; ?></span>
                            <div class="event-content">
                                <!-- <span class="date">February 3, 2017</span> -->
                                <h6><?php echo $campaign['template_values']['three_column_content_promo']['three']['title']['value']; ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="video-section">
            <div class="video-img image-container-full">
                <img src="<?php echo  $campaign['template_values']['video']['image']['image_url'] == '' ? site_url('assets/img/faces/marc.jpg') :  $campaign['template_values']['video']['image']['image_url']; ?>">
            </div>
            <div class="container">
                <div class="row">
                    <div class="video-content">
                        <span><?php echo $campaign['template_values']['video']['title']['value']; ?></span>
                        <h2><?php echo $campaign['template_values']['video']['sub_title']['value']; ?></h2>
                        <div class="play-btn">
                            <a target="_blank" href="<?php echo $campaign['template_values']['video']['cta']['link']; ?>"><span class="play"></span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="events-widget-area l-padding-md bg-dark">
            <div class="container">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="widget-block">
                            <h3><?php echo $campaign['template_values']['concert']['title']['value']; ?></h3>
                            <ul>
                                <li>
                                    <div class="widget-item">
                                        <div class="image-container-full">
                                            <img src="https://media.gettyimages.com/photos/what-a-great-speech-picture-id511305456?s=2048x2048" />
                                            <img src="<?php echo  $campaign['template_values']['concert']['concert_one']['image']['image_url'] == '' ? site_url('assets/img/faces/marc.jpg') :  $campaign['template_values']['concert']['concert_one']['image']['image_url']; ?>">
                                        </div>
                                        <h5><?php echo $campaign['template_values']['concert']['concert_one']['title']['value']; ?></h5>
                                    </div>
                                </li>
                                <li>
                                    <div class="widget-item">
                                        <div class="image-container-full">
                                            <img src="https://media.gettyimages.com/photos/what-a-great-speech-picture-id511305456?s=2048x2048" />
                                            <img src="<?php echo  $campaign['template_values']['concert']['concert_two']['image']['image_url'] == '' ? site_url('assets/img/faces/marc.jpg') :  $campaign['template_values']['concert']['concert_two']['image']['image_url']; ?>">
                                        </div>
                                        <h5><?php echo $campaign['template_values']['concert']['concert_two']['title']['value']; ?></h5>
                                    </div>
                                </li>
                                <li>
                                    <div class="widget-item">
                                        <div class="image-container-full">
                                            <img src="https://media.gettyimages.com/photos/what-a-great-speech-picture-id511305456?s=2048x2048" />
                                            <img src="<?php echo  $campaign['template_values']['concert']['concert_three']['image']['image_url'] == '' ? site_url('assets/img/faces/marc.jpg') :  $campaign['template_values']['concert']['concert_three']['image']['image_url']; ?>">
                                        </div>
                                        <h5><?php echo $campaign['template_values']['concert']['concert_three']['title']['value']; ?></h5>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-container text-left">
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
                    <div class="col-sm-3">
                        <div class="widget-block">
                            <h3><?php echo $campaign['template_values']['event']['title']['value']; ?></h3>
                            <ul>
                                <li>
                                    <div class="widget-item">
                                        <div class="image-container-full">
                                            <img src="https://media.gettyimages.com/photos/what-a-great-speech-picture-id511305456?s=2048x2048" />
                                            <img src="<?php echo  $campaign['template_values']['event']['event_one']['image']['image_url'] == '' ? site_url('assets/img/faces/marc.jpg') :  $campaign['template_values']['event']['event_one']['image']['image_url']; ?>">
                                        </div>
                                        <h5><?php echo $campaign['template_values']['event']['event_one']['title']['value']; ?></h5>
                                    </div>
                                </li>
                                <li>
                                    <div class="widget-item">
                                        <div class="image-container-full">
                                            <img src="https://media.gettyimages.com/photos/what-a-great-speech-picture-id511305456?s=2048x2048" />
                                            <img src="<?php echo  $campaign['template_values']['event']['event_two']['image']['image_url'] == '' ? site_url('assets/img/faces/marc.jpg') :  $campaign['template_values']['event']['event_two']['image']['image_url']; ?>">
                                        </div>
                                        <h5><?php echo $campaign['template_values']['event']['event_two']['title']['value']; ?></h5>
                                    </div>
                                </li>
                                <li>
                                    <div class="widget-item">
                                        <div class="image-container-full">
                                            <img src="https://media.gettyimages.com/photos/what-a-great-speech-picture-id511305456?s=2048x2048" />
                                            <img src="<?php echo  $campaign['template_values']['event']['event_three']['image']['image_url'] == '' ? site_url('assets/img/faces/marc.jpg') :  $campaign['template_values']['event']['event_three']['image']['image_url']; ?>">
                                        </div>
                                        <h5><?php echo $campaign['template_values']['event']['event_three']['title']['value']; ?></h5>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>