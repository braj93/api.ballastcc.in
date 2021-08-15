<header>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-6">
                  <div>
                    <a>
                    <img class="logo-size" src="<?php echo  $campaign['template_values']['header']['image']['image_url'] == '' ? site_url('assets/img/landing-page/dummy_logo.png') :  $campaign['template_values']['header']['image']['image_url']; ?>">
                    </a>
                  </div>
                </div>
                <div class="col-6 text-right call-info">
                  <div>
                    <a class="add-call" href="tel:<?php echo $campaign['template_values']['header']['number']['value']; ?>">
                        <img src="<?php echo  site_url('assets/img/landing-page/phone.svg'); ?>">
                        <span><?php echo $campaign['template_values']['header']['number']['value']; ?></span>
                    </a>
                  </div>
                </div>
            </div>
        </div>
    </header>

    <section class="banner-section">
      <div class="banner-img">
        <img src="<?php echo  $campaign['template_values']['banner_background_image']['image_url'] == '' ? site_url('assets/img/cover.jpeg') :  $campaign['template_values']['banner_background_image']['image_url']; ?>">
      </div>
      <div class="container">
        <div class="row align-items-center">
          <div class="col-sm-5">
            <form id="cform" method="POST">
              <div class="banner-form">
                <div>
                  <h3><?php echo $campaign['template_values']['contact_us']['title']['value']; ?></h3>
                </div>
                <div class="form-container">
                <input type="text" placeholder="Name" name="name" required>
                    <?php echo "<span style='color:red'>".form_error('name')."</span>"; ?>

                    <input type="email" placeholder="Email" name="email" required>
                    <?php echo "<span style='color:red'>".form_error('email')."</span>"; ?>

                    <input type="tel" placeholder="Phone" name="phone" required>
                    <?php echo "<span style='color:red'>".form_error('phone')."</span>"; ?>

                    <input type="text" placeholder="Message" name="message" required>
                    <?php echo "<span style='color:red'>".form_error('message')."</span>"; ?>

                    <div class="push-notification">
                    <input id="is_sent_notification_id" type="checkbox" name="is_sent_notification" checked/>
                    <label for="is_sent_notification_id">
                    Send Push Notification upon lead submission
                    </label>
                    </div>
                    
                    <button type="submit" <?php if($campaign['preview']){ echo 'disabled';};?>><?php echo $campaign['template_values']['contact_us']['button']['value']; ?></button>
                    <span class="loader-block ml-2"  style="display:none;"><img src="http://i.stack.imgur.com/FhHRx.gif" alt="Loading"></span>
                    <h6 class="success-message mt-4 text-center" style="display:none; color:#4BB543" >Message Sent Successfully</h6>
                    <h6 class="error-message mt-4" style="display:none; color:#dc3545" >Something Went Wrong</h6>
                </div>
              </div>
            </form>
            <div class="form-call-text">
              <div>
                <span><?php echo $campaign['template_values']['contact_us']['description']['value']; ?></span>
              </div>
              <div>
                <a class="add-call" href="tel:<?php echo $campaign['template_values']['contact_us']['number']['value']; ?>"><?php echo $campaign['template_values']['contact_us']['number']['value']; ?></a>
              </div>
            </div>
          </div>
          <div class="col-sm-7">
            <div class="banner-content">
              <div>
                <h1><?php echo $campaign['template_values']['banner']['title']['value']; ?></h1>
              </div>
              <div>
                <p><?php echo $campaign['template_values']['banner']['description']['value']; ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="paragraph-content padding-md">
      <div class="container">
        <div class="row">
          <div class="col-sm-6">
            <div class="pr-sm-3 text-block">
              <div>
                <h4><?php echo $campaign['template_values']['two_section_component']['left']['title']['value']; ?></h4>
              </div>
              <div>
                <p><?php echo $campaign['template_values']['two_section_component']['left']['description']['value']; ?></p>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="pr-sm-3 text-block">
              <div>
                <h4><?php echo $campaign['template_values']['two_section_component']['right']['title']['value']; ?></h4>
              </div>
              <div>
                <p><?php echo $campaign['template_values']['two_section_component']['right']['description']['value']; ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>