<html>
<head>
	<meta charset="UTF-8">
	<meta name="description" content="" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <style type="text/css">
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700;800;900&family=Roboto:wght@300;400;500;700;900&display=swap');s
.landing-template h1,
.landing-template h2,
.landing-template h3,
.landing-template h4,
.landing-template h5,
.landing-template h6,
.btn.landing-btn {
    font-family: 'Montserrat', sans-serif;
}

.bg-light {
    background-color: #f4f4f4;
}

ul {
    list-style: none;
}

body {
    font-family: 'Roboto', sans-serif;
}

.text-block {
    font-family: 'Roboto', sans-serif;
    font-size: 18px;
    line-height: 34px;
}
.text-block ul {
    margin: 0 0 30px;
    padding:0;
}

.text-block ul li {
    position:relative;
    font-size:18px;
    line-height:34px;
    padding-left: 30px;
    color:#666666;
}
.text-block ul li:before {
    content:'';
    width:15px;
    height:1px;
    left:0;
    top:15px;
    background: #ff6716;
    position:absolute;
}
.landing-template {
    position: relative;
    overflow: hidden;
}

.bg-light {
    background-color: #f4f4f4; 
}

.btn.landing-btn {
    border-radius: 100px;
    border: 2px solid #ff6716;
    background-color: rgba(255, 103, 22, 0.1);
    position: relative;
    padding: 15px 22px 15px;
    min-width: 220px;
    font-size: 20px;
    text-transform: uppercase;
    letter-spacing: 2px;
    font-weight: 600;
    font-family: 'Montserrat', sans-serif;
}

.btn.landing-btn.landing-btn-blue {
    border: 2px solid #132031;
    color: #132031;
    background-color: rgba(19, 32, 49, 0.1);
}

.btn.landing-btn.landing-btn-blue:before {
    background-color: #132031;
}

.btn.btn-primary.landing-btn-sm {
    font-size:14px;
    border: none;
    background: #132031;
    min-width: auto;
    padding: 10px 20px;
    padding-left: 62px;
    opacity:0;
    visibility:hidden;
    margin-bottom: -35px;
    transition:all 0.3s ease-in-out;
}
.landing--submit-btn:active, 
.landing--submit-btn:focus{
    outline: none;
}
.landing--submit-btn {
    border-radius: 100px;
    border: 2px solid #ff6716;
    color: #ff6716;
    background-color: rgba(255, 103, 22, 0.1);
    position: relative;
    padding: 15px 22px 15px;
    min-width: 220px;
    font-size: 20px;
    text-transform: uppercase;
    letter-spacing: 2px;
    font-weight: 600;
    font-family: 'Montserrat', sans-serif;
}



.l-padding-lg {
    padding: 100px 0;
}
.l-padding-md {
    padding: 60px 0;
}

.landing-title-block h2 {
    font-size: 30px;
    font-weight: 400;
    text-transform: uppercase;
}
.landing-title-block h2 span {
    font-weight: 600;
}

.landing-template .header-banner {
    background-position: center;
    background-size: cover;
    background-attachment: fixed;
    width: 100%;
    height: 100vh;
    min-height: 650px;
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
}


.landing-template .header-banner .image-holder-block {
    position: absolute;
    height: 100%;
    width: 100%;
    left: 0;
    top: 0;
}
.landing-template .header-banner .image-holder-block:before {
    content: '';
    position: absolute;
    height: 100%;
    width: 100%;
    left: 0;
    top: 0;
    background: -moz-linear-gradient(to bottom, rgba(142, 142, 142, 0.43) 0%,rgba(0, 0, 0, 0.8) 100%);
    background: -webkit-linear-gradient(to bottom, rgba(142, 142, 142, 0.43) 0%,rgba(0, 0, 0, 0.8) 100%);
    background: linear-gradient(to bottom, rgba(142, 142, 142, 0.43) 0%,rgba(0, 0, 0, 0.8) 100%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#e3000000', endColorstr='#59000000',GradientType=0 ); /* IE6-9 */
    z-index: 1;
}
.landing-template .header-banner .image-holder-block img {
    margin: 0;
    position: absolute;
    width: 100%;
    height: 100%;
    left: 0;
    top: 0;
}

.landing-template .top-content-bar {
    max-width: 1140px;
    width: 100%;
    color: #fff;
    text-align: center;
    padding: 10px 15px;
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    z-index: 3;
}
.landing-template .top-content-bar h2 {
    display: inline-block;
}
.landing-template .top-content-bar a.call-number {
    display: flex;
    float: right;
    align-items: center;
    color: #fff;
    font-weight: 500;
    font-size: 16px;
    position: absolute;
    right: 20px;
    top: 20px;
}
.top-content-bar .call-number span {
    height: 40px;
    width: 40px;
    border-radius: 50px;
    background: #ff6716;
    text-align: center;
    margin-right: 10px;
    display: -webkit-box;
    display: flex;
    border: 4px solid rgba(156, 97, 65, 0.88);
}
.top-content-bar .call-number span img {
    max-width: 15px;
    vertical-align: middle;
    display: block;
    margin: 0 auto;
}
.landing-template .landing-banner-content {
    display: flex;
    align-items: center;
    width: 100%;
    height: 100%;
    position: relative;
    z-index: 2;
}

.logo-block img {
    max-width:200px;
}

.landing-template .banner-text-block {
    max-width: 850px;
    color: #fff;
    margin: 0 auto;
}

.banner-text-block h2 {
    font-size: 40px;
    padding-bottom: 20px;
    margin-bottom: 20px;
    position: relative;
}

.banner-text-block h2:after {
    content: '';
    width: 30%;
    height: 2px;
    background-color: #ff6716;
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
}

.landing-template .banner-text-block p {
    color: #fff;
}



.image-landing-container {
    position: relative;
    overflow: hidden;
    height: 100%;
    width: 100%;
}
.image-landing-container .image-holder-block {
    position: absolute;
    height: 100%;
    width: 100%;
    top: 0;
    left: 0;
}
.image-landing-container .image-holder-block img {
    width: 100%;
}

/*about us section style start*/

.about-us-section {
    position: relative;
}

.about-us-content {
    position: relative;
}
.about-us-content:before {
    content:'';
    width: 40%;
    height:50%;
    background:#ddd;
    position:absolute;
    left: 0;
    top: 50%;
    opacity:0.3;
    transform:translateY(-50%) skewY(-10deg);
}

.retail-template .about-us-content:before {
    width: 58%;
    left: auto;
    right: 0;
    background:#ff6716;
    opacity:1;
}

@media (max-width: 767px) {
    .retail-template .about-us-content:before {
        display: none;
    }
}

.about-us-image-block {
    position: absolute;
    right: -10px;
    top: 0;
    display: flex;
    height: 100%;
    background-size: cover;
}
.about-us-image-block:after {
    content: '';
    position: absolute;
    right: 0;
    top: 50%;
    width: 102%;
    height: 110%;
    background: #333;
    opacity: 0.1;
    border-radius: 10px;
    transform: translateY(-50%);
}
.about-us-image {
    position: absolute;
    right: 0;
    top: 0;
    height: 100%;
    width: 100%;
    background-size: cover;
    z-index: 1;
}

.about-us-content .text-block {
    padding: 60px 0;
}


.image-landing-container {
    position: relative;
    overflow: hidden;
    height: 100%;
    width: 100%;
}
.image-landing-container .image-holder-block {
    position: absolute;
    height: 100%;
    width: 100%;
    top: 0;
    left: 0;
}
.image-landing-container .image-holder-block img {
    width: auto;
    height: 100%;
    position: relative;
    left: 50%;
    transform: translate(-50%, 0);
}

.retail-template .about-us-image-holder {
    position: relative;
    overflow: hidden;
    height: 100%;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
}

.retail-template .about-us-image {
    position: relative;
}

/*about us section style endd*/



/*Products section style start*/


.form-container {
    background-color: #fff;
    max-width:750px;
    margin: 0 auto;
    padding:60px;
    border-radius:10px;
    box-shadow: 0 0 40px #33333340;
}

.landing-input {
    width: 100%;
    margin-bottom: 30px;
    border: none;
    border-bottom: 1px solid #ccc;
    min-height: 60px;
    padding: 10px;
    font-size: 16px;
    font-weight: 500;
    font-family: 'Roboto', sans-serif;
}

/*Retail product section start*/


.product-image-holder {
    position: relative;
    min-height: 300px;
    overflow: hidden;
}
.product-image-holder img {
    position: absolute;
    width: 100%;
    height: auto;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
}

.product-section {
    padding: 40px 0;
}

.product-section h4 {
    font-size: 34px;
}

.product-section {
    background: #f4f4f4;
}
.product-section:nth-child(2n+1) {
    background: #ffffff;
}


@media (min-width: 767px) {

    .product-section .col-sm-5 {
        -webkit-box-ordinal-group: 3;
        order: 2;
    }
    .product-section:nth-child(2n+1) .col-sm-7 {
        -webkit-box-ordinal-group: 3;
        order: 2;
    }

}

/*Retail product section end*/


@media (max-width: 999px) {

    .landing-template .top-content-bar h2 {
        margin-top: 10px;
    }

    .landing-template .banner-text-block {
        padding: 0 15px;
    }

    .l-padding-lg {
        padding: 50px 0;
    }

    .landing-template .header-banner {
        height: 80vh;
    }

    .banner-text-block h2 {
        font-size: 30px;
        line-height: 40px;
    }

    .product-section h4 {
        font-size: 30px;
    }

    .landing-title-block h2 {
        font-size: 34px;
    }

    .form-container {
        padding: 30px;
    }
}


@media (max-width: 767px) {

    .btn.landing-btn {
        font-size: 16px;
        padding: 15px 22px 15px 80px;
    }

    .btn.landing-btn:before {
        width: 15%;
    }

    .landing-template .top-content-bar h2 {
        margin-top: 5px;
    }

    .l-padding-lg {
        padding: 40px 0;
    }

    .landing-template .top-content-bar a.call-number {
        float: none; 
        position: relative;
        right: 0; 
        top: 0; 
        margin: 10px auto 0;
        justify-content: center;
    }
    .landing-template .header-banner {
        height: 80vh;
    }
    .landing-template .header-banner .image-holder-block img {
        width: auto;
    }
    .banner-text-block h2 {
        font-size: 30px;
        line-height: 40px;
    }

    .product-image-holder {
        min-height: 250px;
        margin-bottom: 20px;
    }
    .about-us-content .text-block {
        padding: 0;
    }
    .product-section h4 {
        font-size: 26px;
    }

    .landing-title-block h2 {
        font-size: 30px;
    }

    .form-container {
        padding: 0;
        box-shadow: none;
        margin-top: 30px;
    }
}
</style>
	<title>Rio Aggregate</title>
</head>
<body>
<div class="main-content retail-template">
<div class="template-view-edit">
    <div class="landing-template">
        <div class="header-banner">
            <div class="image-holder-block">
                <img src="https://rioaggregate.com/wp-content/uploads/sites/7/2019/10/about-background.png" />
            </div>
            <div class="top-content-bar">
                <h2 class="page-title">
                      <a href="" class="logo-block">
                      	<img src="https://rioaggregate.com/wp-content/uploads/sites/7/2019/09/rio-aggregate-logo-web.png">
                      </a>
                </h2>
                <a href="tel: 928-788-4241" class="call-number add-call"><span><img src="<?php echo  site_url('assets/img/landing-page/call.svg'); ?>"></span>
                   928-788-4241
                </a>
            </div>
            <div class="landing-banner-content">
                <div class="banner-text-block text-center">
                        <h2>Contact us to get the best pricing on delivered concrete in the tri-state area.</h2>
                        <p>No Minimum, No Hidden Cost!</p>
                        <a href="#contactUs" class="btn btn-primary landing-btn">Contact Us</a>
                </div>
            </div>
        </div>


        <div class="product-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-sm-5">
                            <div class="product-image-holder">
                                <img _ngcontent-yad-c178="" src="https://rioaggregate.com/wp-content/uploads/sites/7/2019/10/about-4-1.jpg">
                            </div>
                    </div>
                    <div class="col-sm-7">
                        <div class="text-block">
                            <h4>Concrete on Demand</h4>
                            <p>Volumetric mixed concrete is great for all types of commercial and construction projects, including sidewalks, roads, and bridges. It’s also excellent for other types of jobs like structure repair, curb and gutter work, utility work, concrete leveling, swimming pool work, hardscaping, foundation work, drainage ditch work, and other jobs that require precise positioning of small concrete loads.</p>

							<p>Suitable for short pours, readymix delivery, civil applications, mining, precast, shotcrete/gunite and specialty concrete including fast setting, coloured and pervious.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="about-us-section l-padding-lg" id="contactUs">
            <div class="about-us-content">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="text-block about-content pr-md-5">
                                    <div class="landing-title-block">
                                        <h2>Why choose us</h2>
                                    </div>
                                    <p>Streamline production and turn projects around faster with less downtime and quick clean up. Our mobile volumetric mixers provide the flexibility to produce concrete on-site in any quantity without waste. That means you only pay for what you use.</p>
                                    <ul>
                                        <li>Pay for just what you need</li>
                                        <li>Great for producing concrete in remote locations</li>
                                        <li>Great for all types of commercial and construction projects</li>
                                        <li>Less Wait, Save time</li>
                                    </ul>
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div class="form-container text-left">
                                <div class="landing-title-block">
                                        <h2>Get our Best Price <span>Now</span></h2>
                                    <form id="cform" method="POST">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="field-block">
                                                <input type="text" class="landing-input" placeholder="Name" name="name" required>
                                                <?php echo "<span style='color:red'>".form_error('name')."</span>"; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="field-block">
                                                <input type="email" class="landing-input" placeholder="Email" name="email" required>
                                                <?php echo "<span style='color:red'>".form_error('email')."</span>"; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
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
                                        <div class="col-md-12">
                                            <button type="submit" class="landing--submit-btn">Send</button>
                                            <span class="loader-block ml-2"  style="display:none;"><img src="http://i.stack.imgur.com/FhHRx.gif" alt="Loading"></span>
                                            <h6 class="success-message mt-4 text-center" style="display:none; color:#4BB543" >Message Sent Successfully</h6>
                                            <h6 class="error-message mt-4" style="display:none; color:#dc3545" >Something Went Wrong</h6>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script type="text/javascript">
    (function ($, window, document, undefined) {
    $(document).ready(function(){
        $('a[href^="#"]').on('click',function (e) {
            e.preventDefault();
            var target = this.hash;
            var $target = $(target);
            $('html, body').stop().animate({
                'scrollTop': $target.offset().top
            }, 900, 'swing', function () {
                // window.location.hash = target;
            });
        });
    });
    })(jQuery, window, document);
</script>


<script type="text/javascript">
    $('form').submit(function(e) {
        e.preventDefault();
       var name = $("input[name='name']").val();
       var email = $("input[name='email']").val();
       var phone = $("input[name='phone']").val();
       var message = $("input[name='message']").val();
       $(".loader-block").css('display','inline-block');
        $.ajax({
           url: "<?php echo base_url('site/retail_submit'); ?>",
           type: 'POST',
           data: {name: name, email: email, phone:phone, message:message},
           error: function() {
            // $("#cform")[0].reset();
            console.log('Something is wrong')
            $(".error-message").css('display','block');
            $(".loader-block").css('display','none');
           },
           success: function(data) {
            $(".success-message").css('display','block');
            $(".loader-block").css('display','none');
            $("#cform")[0].reset();
           }
        });


    });
</script>


<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src='https://tag.simpli.fi/sifitag/5807b880-88ba-0138-7673-06b4c2516bae'></script>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-161972428-2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-161972428-2');
</script>

</body>
</html>