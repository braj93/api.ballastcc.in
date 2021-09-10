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
    background:#f60b00;
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
    background:#f60b00;
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

.head-block .image-holder-block img {
    min-height: 100px;
    max-height: 100px;
}

header .call-info img {
    max-width: 20px;
}

header .call-info a {
    color: #134074;
    font-size: 14px;
    font-weight: 600;
    border: 2px solid  #134074;
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
    min-height:750px;
    position:relative;
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
	background:#7ccb1d;
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

.banner-section .banner-form button {
    color: #fff;
    font-size: 18px;
    background:#7ccb1d;
    font-weight: 600;
    border: 2px solid #7ccb1d;
    border-radius: 50px;
    padding: 8px 30px;
}

.banner-section h3 {
    color: #fff;
    padding: 15px 5px;
    background:#7ccb1d;
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
/*Simple product section end*/
</style>
</head>
<body>