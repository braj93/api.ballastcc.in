<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to Census</title>
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<style type="text/css">
    	*{
    		box-sizing: border-box;
    	}
    	.wrapper{
    		font-family: sans-serif;
    		font-size: 14px;
    	}
    	.container{
    		width: 100%; 
    		margin: 0 auto;
    	}
    	.blue-bg{
    		background-color: #1d174f;
    		padding: 7px 7px;
    		display: inline-block;
    		width: 100%;
    	}
    	.white-bg{
    		background-color: #fff;
		    max-width: 820px;
		    width: 100%;
		    float: left;
    	}
    	.column {
		    width: 28%;
		    padding: 3px 10px;
		    vertical-align: middle;
		    display: inline-block;
		}
		.column.logo {
			width: 14%;
		}
		.column h2{
			margin: 0px;
		    font-size: 60px;
		    color: #f38a1d;
		    font-weight: 500;
		}
		.column p{
			margin: 0px;
			font-size: 16px;
		}
		.right-text {
		    overflow: hidden;
		    text-align: center;
		    color: #fff;
		    padding: 18px 0px;
		}
		.right-text p{
		    font-size: 22px;
		    margin: 0px;
		}
		.right-text h2{
		    font-size: 32px;
		    margin: 0;
		    font-weight: 600;
		}
		a{
			cursor: pointer;
			color: #fff;
			text-decoration: none;
		}
		img {
			max-width: 100%;
		}


		@media(max-width: 1100px){
			 
			.column{
				width: 45%;
				text-align: center;
				float: left;
			}
			.white-bg{
				max-width: 100%;
				float: none;
			}
			.column.logo {
				width: 20%;
			}
			.column.one {
				width: 77%;
				padding: 34px 40px;
			}
			.column.two,
			.column.three{
				width: 50%;
				padding-bottom: 10px;
			}
			.white-bg {
				overflow: hidden;
			}
			.right-text {
				width: 100%;
			}
		}

		@media (min-width: 600px)and (max-width: 1100px){
			.column.one {
				padding:40px;
			}
		}
		@media(max-width: 600px){
			.column.logo {
				width: 25%;
				margin: 0 auto;
				display: block;
				float: none;
			}
			.column{
				width: 100%;
				padding: 3px 0px
			}
			.column.one,
			.column.two,
			.column.three{
				width: 100%;
				padding-bottom: 20px;
			}
		}
    </style>
</head>
<body>
<div class="wrapper">
	<div class="container">
		<div class="blue-bg">
			<div class="white-bg">
				<div class="column logo" align="center">
					<div class="logo">
						<img src="/assets/image/logo.png">
					</div>
				</div>
				<div  class="column one">
					<p><i>Help us get to a 100% response rate so that we receive critically needed funding for our area!</i></p>
				</div>
				 
				<div class="column two">
					<h2><?php $for_place = json_decode($census['for_place'], TRUE); echo $for_place[1][1]; ?>%</h2>
					<p>Bullhead City Response</p>
				</div>
				<div class="column three">
					<h2><?php $for_county = json_decode($census['for_county'], TRUE); echo $for_county[1][1]; ?>%</h2>
					<p>Mohave County Response</p>
				</div>
			</div>
			<div class="right-text">
				<p>You can respond online at</p>
				<h2><a href="https://2020census.gov/" target="_blank">2020census.gov</a></h2>
			</div>
		</div>
	</div>
</div>
</body>
</html>