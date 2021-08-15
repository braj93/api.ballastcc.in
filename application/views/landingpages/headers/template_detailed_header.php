<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo $campaign['page_title']; ?></title>
	<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<style type="text/css">
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700;800;900&family=Roboto:wght@300;400;500;700;900&display=swap');
body {
	padding: 0;
	margin: 0;
	overflow-x: hidden; 
	font-family: 'Montserrat';
}

/* montserrat-300italic - latin */
@font-face {
  font-family: 'Montserrat';
  font-style: italic;
  font-weight: 300;
  src: url('../fonts/montserrat-v14-latin-300italic.eot'); /* IE9 Compat Modes */
  src: url('../fonts/montserrat-v14-latin-300italic.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
       url('../fonts/montserrat-v14-latin-300italic.woff2') format('woff2'), /* Super Modern Browsers */
       url('../fonts/montserrat-v14-latin-300italic.woff') format('woff'), /* Modern Browsers */
       url('../fonts/montserrat-v14-latin-300italic.ttf') format('truetype'), /* Safari, Android, iOS */
       url('../fonts/montserrat-v14-latin-300italic.svg#Montserrat') format('svg'); /* Legacy iOS */
}
/* montserrat-regular - latin */
@font-face {
  font-family: 'Montserrat';
  font-style: normal;
  font-weight: 400;
  src: url('../fonts/montserrat-v14-latin-regular.eot'); /* IE9 Compat Modes */
  src: url('../fonts/montserrat-v14-latin-regular.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
       url('../fonts/montserrat-v14-latin-regular.woff2') format('woff2'), /* Super Modern Browsers */
       url('../fonts/montserrat-v14-latin-regular.woff') format('woff'), /* Modern Browsers */
       url('../fonts/montserrat-v14-latin-regular.ttf') format('truetype'), /* Safari, Android, iOS */
       url('../fonts/montserrat-v14-latin-regular.svg#Montserrat') format('svg'); /* Legacy iOS */
}
/* montserrat-500 - latin */
@font-face {
  font-family: 'Montserrat';
  font-style: normal;
  font-weight: 500;
  src: url('../fonts/montserrat-v14-latin-500.eot'); /* IE9 Compat Modes */
  src: url('../fonts/montserrat-v14-latin-500.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
       url('../fonts/montserrat-v14-latin-500.woff2') format('woff2'), /* Super Modern Browsers */
       url('../fonts/montserrat-v14-latin-500.woff') format('woff'), /* Modern Browsers */
       url('../fonts/montserrat-v14-latin-500.ttf') format('truetype'), /* Safari, Android, iOS */
       url('../fonts/montserrat-v14-latin-500.svg#Montserrat') format('svg'); /* Legacy iOS */
}
/* montserrat-600 - latin */
@font-face {
  font-family: 'Montserrat';
  font-style: normal;
  font-weight: 600;
  src: url('../fonts/montserrat-v14-latin-600.eot'); /* IE9 Compat Modes */
  src: url('../fonts/montserrat-v14-latin-600.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
       url('../fonts/montserrat-v14-latin-600.woff2') format('woff2'), /* Super Modern Browsers */
       url('../fonts/montserrat-v14-latin-600.woff') format('woff'), /* Modern Browsers */
       url('../fonts/montserrat-v14-latin-600.ttf') format('truetype'), /* Safari, Android, iOS */
       url('../fonts/montserrat-v14-latin-600.svg#Montserrat') format('svg'); /* Legacy iOS */
}
/* montserrat-700 - latin */
@font-face {
  font-family: 'Montserrat';
  font-style: normal;
  font-weight: 700;
  src: url('../fonts/montserrat-v14-latin-700.eot'); /* IE9 Compat Modes */
  src: url('../fonts/montserrat-v14-latin-700.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
       url('../fonts/montserrat-v14-latin-700.woff2') format('woff2'), /* Super Modern Browsers */
       url('../fonts/montserrat-v14-latin-700.woff') format('woff'), /* Modern Browsers */
       url('../fonts/montserrat-v14-latin-700.ttf') format('truetype'), /* Safari, Android, iOS */
       url('../fonts/montserrat-v14-latin-700.svg#Montserrat') format('svg'); /* Legacy iOS */
}
/* montserrat-800 - latin */
@font-face {
  font-family: 'Montserrat';
  font-style: normal;
  font-weight: 800;
  src: url('../fonts/montserrat-v14-latin-800.eot'); /* IE9 Compat Modes */
  src: url('../fonts/montserrat-v14-latin-800.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
       url('../fonts/montserrat-v14-latin-800.woff2') format('woff2'), /* Super Modern Browsers */
       url('../fonts/montserrat-v14-latin-800.woff') format('woff'), /* Modern Browsers */
       url('../fonts/montserrat-v14-latin-800.ttf') format('truetype'), /* Safari, Android, iOS */
       url('../fonts/montserrat-v14-latin-800.svg#Montserrat') format('svg'); /* Legacy iOS */
}
/* montserrat-900 - latin */
@font-face {
  font-family: 'Montserrat';
  font-style: normal;
  font-weight: 900;
  src: url('../fonts/montserrat-v14-latin-900.eot'); /* IE9 Compat Modes */
  src: url('../fonts/montserrat-v14-latin-900.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
       url('../fonts/montserrat-v14-latin-900.woff2') format('woff2'), /* Super Modern Browsers */
       url('../fonts/montserrat-v14-latin-900.woff') format('woff'), /* Modern Browsers */
       url('../fonts/montserrat-v14-latin-900.ttf') format('truetype'), /* Safari, Android, iOS */
       url('../fonts/montserrat-v14-latin-900.svg#Montserrat') format('svg'); /* Legacy iOS */
}
/* roboto-regular - latin */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: 400;
  src: url('../fonts/roboto-v20-latin-regular.eot'); /* IE9 Compat Modes */
  src: url('../fonts/roboto-v20-latin-regular.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
       url('../fonts/roboto-v20-latin-regular.woff2') format('woff2'), /* Super Modern Browsers */
       url('../fonts/roboto-v20-latin-regular.woff') format('woff'), /* Modern Browsers */
       url('../fonts/roboto-v20-latin-regular.ttf') format('truetype'), /* Safari, Android, iOS */
       url('../fonts/roboto-v20-latin-regular.svg#Roboto') format('svg'); /* Legacy iOS */
}
/* roboto-300 - latin */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: 300;
  src: url('../fonts/roboto-v20-latin-300.eot'); /* IE9 Compat Modes */
  src: local('Roboto Light'), local('Roboto-Light'),
       url('../fonts/roboto-v20-latin-300.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
       url('../fonts/roboto-v20-latin-300.woff2') format('woff2'), /* Super Modern Browsers */
       url('../fonts/roboto-v20-latin-300.woff') format('woff'), /* Modern Browsers */
       url('../fonts/roboto-v20-latin-300.ttf') format('truetype'), /* Safari, Android, iOS */
       url('../fonts/roboto-v20-latin-300.svg#Roboto') format('svg'); /* Legacy iOS */
}
/* roboto-500 - latin */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: 500;
  src: url('../fonts/roboto-v20-latin-500.eot'); /* IE9 Compat Modes */
  src: url('../fonts/roboto-v20-latin-500.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
       url('../fonts/roboto-v20-latin-500.woff2') format('woff2'), /* Super Modern Browsers */
       url('../fonts/roboto-v20-latin-500.woff') format('woff'), /* Modern Browsers */
       url('../fonts/roboto-v20-latin-500.ttf') format('truetype'), /* Safari, Android, iOS */
       url('../fonts/roboto-v20-latin-500.svg#Roboto') format('svg'); /* Legacy iOS */
}
/* roboto-700 - latin */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: 700;
  src: url('../fonts/roboto-v20-latin-700.eot'); /* IE9 Compat Modes */
  src: url('../fonts/roboto-v20-latin-700.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
       url('../fonts/roboto-v20-latin-700.woff2') format('woff2'), /* Super Modern Browsers */
       url('../fonts/roboto-v20-latin-700.woff') format('woff'), /* Modern Browsers */
       url('../fonts/roboto-v20-latin-700.ttf') format('truetype'), /* Safari, Android, iOS */
       url('../fonts/roboto-v20-latin-700.svg#Roboto') format('svg'); /* Legacy iOS */
}
/* roboto-900 - latin */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: 900;
  src: url('../fonts/roboto-v20-latin-900.eot'); /* IE9 Compat Modes */
  src: url('../fonts/roboto-v20-latin-900.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
       url('../fonts/roboto-v20-latin-900.woff2') format('woff2'), /* Super Modern Browsers */
       url('../fonts/roboto-v20-latin-900.woff') format('woff'), /* Modern Browsers */
       url('../fonts/roboto-v20-latin-900.ttf') format('truetype'), /* Safari, Android, iOS */
       url('../fonts/roboto-v20-latin-900.svg#Roboto') format('svg'); /* Legacy iOS */
}


/*Edit style css start*/

.landing-edit-block {
    position: relative;
    outline:transparent;
}
.landing-edit-block:hover {
    position: relative;
    outline:1px dashed #333;
}
.landing-edit-block .edit-element-btn {
    display: none;
    position: absolute;
    top:0;
    left:0;
    background:#ff0000;
    border: none;
    height:30px;
    width:30px;
    color: #fff;
    border-radius: 50px;
    z-index: 99;
    font-size: 13px;
    text-align: center;
    line-height: 30px;
}
.landing-edit-block:hover .edit-element-btn {
    display:block;
}

.image-update-btn-edit {
    position: absolute;
    top:0;
    left:0;
    background:#ff0000;
    border: none;
    height:30px;
    width:30px;
    color: #fff;
    border-radius: 50px;
    z-index: 99;
    display: none;
}

.image-update-btn-edit.image-update-btn-edit-right{
    left:auto;
    right: 0;
}

.image-edit-block {
    position: relative;
    outline:transparent;
}
.image-edit-block:hover {
    position: relative;
    outline:1px dashed #333;
}
.image-edit-block:hover .image-update-btn-edit {
    display:block;
}

.image-holder-block .image-update-btn-edit {
    display: block;
}

/*Edit style css end*/


.padding-md {
  padding: 60px 0;
}

p {
  font-family: 'Roboto';
  font-size: 16px;
  line-height: 26px;
}

/*Header style start*/

header {
    position: absolute;
    width: 100%;
    background: #fff;
    padding: 10px 0;
    z-index: 5;
    box-shadow: 0 5px 15px rgb(0 0 0 / 0.2);
}

header .logo {
	max-width: 200px;
}

header .call-info img {
    max-width: 20px;
}

.head-block .image-holder-block img {
    min-height: 100px;
    max-height: 100px;
}

header .call-info a {
    color: #f74b4b;
    font-size: 14px;
    font-weight: 600;
    border: 2px solid  #f74b4b;
    border-radius: 50px;
    padding: 10px 25px;
}

header .call-info a span {
    margin-left:10px;
}

/*Header style end*/


/*Banner section design start*/

.banner-section {
    height: 100vh;
    padding-top: 80px;
    min-height: 750px;
    position: relative;
}

.banner-section .banner-img {
    position:absolute;
    height: 100%;
    width: 100%;
    left:0;
    top: 0;
}

.banner-section .banner-img img {
    position:absolute;
    height: 100%;
    width: 100%;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
}

.banner-section .banner-img:before {
    content:'';
    background:#000;
    opacity: 0.5;
    position:absolute;
    height: 100%;
    width: 100%;
    left: 0;
    top: 0;
    z-index: 1;
}

.banner-section .container,
.banner-section .row {
    height: 100%;
    position:relative;
}

.banner-section .banner-content {
    position:relative;
    z-index: 2;
    color:#fff;
}

.banner-section .banner-content h1 {
	font-size: 40px;
	padding: 20px 0 20px; 
	font-weight: 500;
	position:relative;
}

.banner-section .banner-content h1:before {
	content: '';
	width: 70px;
	background:#f74b4b;
	height: 1px;
	top:0;
	position:absolute;
}

.banner-section .banner-form {
    position:relative;
    z-index: 2;
    background:#fff;
    text-align:center;
}

.banner-section .banner-form button[type="submit"] {
    color: #fff;
    font-size: 18px;
    background:#f74b4b;
    font-weight: 600;
    border: 2px solid #f74b4b;
    border-radius: 50px;
    padding: 8px 30px;
    display: block;
    width: 100%;
}

.banner-section h3 {
    color: #fff;
    padding: 15px 5px;
    background:#f74b4b;
    margin-bottom:0;
    font-size: 24px;
    font-weight: 600;
}

.banner-section .form-container {
    padding: 40px;
}

.form-call-text {
    position:relative;
    z-index:2;
    background: #fff;
    text-align: center;
    padding-bottom: 30px;
}

.form-call-text span {
    display: block;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: 500;
}

.form-call-text a {
    font-weight: 600;
    color:#333;
    font-size: 22px;
}

input {
    width:100%;
    border-radius: 5px;
    border: 1px solid #ccc;
    min-height:48px;
    margin-bottom: 20px;
    font-size: 14px;
    font-weight: 500;
    padding: 5px 10px;
}

.push-notification input[type=checkbox] {
    width: auto;
    min-height: auto;
    margin: 3px 5px 0 0;
}
.push-notification{
   text-align:left;
   padding: 0;
   display: flex;
   align-items: flex-start;
   margin-bottom: 10px;
}
.push-notification label{
    font-size: 12px;
}

.logo-size {
    height: 100px;
}

/*Banner section design end*/



/*Paragraph start*/

.paragraph-content .text-block h4 {
    position:relative;
    padding-bottom: 15px;
    margin-bottom: 15px;
}

.paragraph-content .text-block h4:after {
    position:absolute;
    content:'';
    height:1px;
    width:50px;
    background:#333;
    bottom:0;
    left:0;
}


/*Paragraph end*/


/*Responsive style start*/

@media (max-width: 767px) {
	header {
	    position: relative;
	    padding: 10px 0;
	}
	header .logo {
		margin-bottom: 10px;
	}
	header .call-info a {
		padding: 10px;
	}
	header .call-info a span{
		display: none;
	}

	.banner-section {
	    height: auto;
	    padding: 50px 0;
	}
}


/*Responsive style end*/
/*Detailed product section end*/
</style>
</head>
<body>