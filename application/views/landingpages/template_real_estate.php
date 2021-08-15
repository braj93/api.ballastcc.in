<div class="landing-template">
	<div class="header-banner">
		<div class="image-holder-block">
			<img src="<?php echo  $campaign['template_values']['banner_background_image']['image_url'] == '' ? site_url('assets/img/cover.jpeg') :  $campaign['template_values']['banner_background_image']['image_url']; ?>">
		</div>
		<div class="top-content-bar">                                            
			<h2 class="page-title">
				<div><?php echo $campaign['template_values']['header']['title']['value']; ?></div>
			</h2>
			<a class="call-number add-call" href="tel:<?php echo $campaign['template_values']['header']['number']['value']; ?>"><span>
				<img src="<?php echo  site_url('assets/img/landing-page/call.svg'); ?>" />
			</span><div><?php echo $campaign['template_values']['header']['number']['value']; ?></div></a>
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
	<div class="about-us-section l-padding-lg">
		<div class="about-us-content">
			<div class="col-sm-6 about-us-image-block">
				<div class="image-landing-container">
					<div class="image-holder-block">
					<img src="<?php echo  $campaign['template_values']['aboutus_component']['image']['image_url'] == '' ? site_url('assets/img/cover.jpeg') :  $campaign['template_values']['aboutus_component']['image']['image_url']; ?>">
					</div>
				</div>
			</div>
			<div class="container">
				<div class="row">
					<div class="col-sm-6">
						<div class="text-block about-content">
							<div>
								<div class="landing-title-block">
									<h2><?php echo $campaign['template_values']['aboutus_component']['title']['value']; ?></h2>
								</div>
							</div>
							<div>
								<p><?php echo $campaign['template_values']['aboutus_component']['description']['value']; ?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="products-block-section l-padding-md">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="landing-title-block text-center mb-5">
						<div>
							<h2><?php echo $campaign['template_values']['two_section_component']['title']['value']; ?></h2>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="products-block">
						<div class="product-img">
							<div class="image-landing-container">
								<div class="image-holder-block">
								<img src="<?php echo  $campaign['template_values']['two_section_component']['left']['image']['image_url'] == '' ? site_url('assets/img/cover.jpeg') :  $campaign['template_values']['two_section_component']['left']['image']['image_url']; ?>">
								</div>
							</div>
						</div>
						<div class="product-title">
							<div>
							<h3><?php echo $campaign['template_values']['two_section_component']['left']['title']['value']; ?></h3>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="products-block">
						<div class="product-img">
							<div class="image-landing-container">
								<div class="image-holder-block">
								<img src="<?php echo  $campaign['template_values']['two_section_component']['right']['image']['image_url'] == '' ? site_url('assets/img/cover.jpeg') :  $campaign['template_values']['two_section_component']['right']['image']['image_url']; ?>">
								</div>
							</div>
						</div>

						<div class="product-title">
							<div>
								<h3><?php echo $campaign['template_values']['two_section_component']['right']['title']['value']; ?></h3>
							</div>
						</div>
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
	<div class="clients-logo-section l-padding-md bg-light">
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
			<div class="logo-slider-block">
				<ul class="logo-slider">
					<li>
						<img src="<?php echo  $campaign['template_values']['three_column_content_promo']['one']['image']['image_url'] == '' ? site_url('assets/img/faces/marc.jpg') :  $campaign['template_values']['three_column_content_promo']['one']['image']['image_url']; ?>">
					</li>
					<li>
						<img src="<?php echo  $campaign['template_values']['three_column_content_promo']['two']['image']['image_url'] == '' ? site_url('assets/img/faces/marc.jpg') :  $campaign['template_values']['three_column_content_promo']['two']['image']['image_url']; ?>">
					</li>
					<li>
						<img src="<?php echo $campaign['template_values']['three_column_content_promo']['three']['image']['image_url'] == '' ? site_url('assets/img/faces/marc.jpg') : $campaign['template_values']['three_column_content_promo']['three']['image']['image_url']; ?>">
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
