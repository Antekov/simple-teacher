<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Просто-репетитор</title>
	<script type="text/javascript" src="/js/jquery.js"></script>
	<script type="text/javascript" src="/js/jquery-ui-1.8.js"></script>
	<script type="text/javascript" src="/js/common.js"></script>
	<script type="text/javascript" src="/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/css/fonts.css"/>
	<link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css"/>
	<link rel="stylesheet" type="text/css" href="/css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="/css/bootstrap-theme.css"/>
	<link rel="stylesheet" type="text/css" href="/css/main.css"/>

	<? foreach ((array) $js as $js_file) { ?><script type="text/javascript" src="/js/<?=$js_file?>"></script><? } ?>
</head>
<body>
