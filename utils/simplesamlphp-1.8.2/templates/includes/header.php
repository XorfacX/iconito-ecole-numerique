<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php



/**
 * Support the htmlinject hook, which allows modules to change header, pre and post body on all pages.
 */
$this->data['htmlinject'] = array(
	'htmlContentPre' => array(),
	'htmlContentPost' => array(),
	'htmlContentHead' => array(),
);


$jquery = array();
if (array_key_exists('jquery', $this->data)) $jquery = $this->data['jquery'];

if (array_key_exists('pageid', $this->data)) {
	$hookinfo = array(
		'pre' => &$this->data['htmlinject']['htmlContentPre'], 
		'post' => &$this->data['htmlinject']['htmlContentPost'], 
		'head' => &$this->data['htmlinject']['htmlContentHead'], 
		'jquery' => &$jquery, 
		'page' => $this->data['pageid']
	);
		
	SimpleSAML_Module::callHooks('htmlinject', $hookinfo);	
}
// - o - o - o - o - o - o - o - o - o - o - o - o -




?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script type="text/javascript" src="/<?php echo $this->data['baseurlpath']; ?>resources/script.js"></script>
<title><?php
if(array_key_exists('header', $this->data)) {
	echo $this->data['header'];
} else {
	echo 'simpleSAMLphp';
}
?></title>

	<link rel="stylesheet" type="text/css" href="/<?php echo $this->data['baseurlpath']; ?>resources/default.css" />
	<link rel="icon" type="image/icon" href="/<?php echo $this->data['baseurlpath']; ?>resources/icons/favicon.ico" />

<?php

if(!empty($jquery)) {
	$version = '1.5';
	if (array_key_exists('version', $jquery))
		$version = $jquery['version'];
		
	if ($version == '1.5') {
		if (isset($jquery['core']) && $jquery['core'])
			echo('<script type="text/javascript" src="/' . $this->data['baseurlpath'] . 'resources/jquery.js"></script>' . "\n");
	
		if (isset($jquery['ui']) && $jquery['ui'])
			echo('<script type="text/javascript" src="/' . $this->data['baseurlpath'] . 'resources/jquery-ui.js"></script>' . "\n");
	
		if (isset($jquery['css']) && $jquery['css'])
			echo('<link rel="stylesheet" media="screen" type="text/css" href="/' . $this->data['baseurlpath'] . 
				'resources/uitheme/jquery-ui-themeroller.css" />' . "\n");	
			
	} else if ($version == '1.6') {
		if (isset($jquery['core']) && $jquery['core'])
			echo('<script type="text/javascript" src="/' . $this->data['baseurlpath'] . 'resources/jquery-16.js"></script>' . "\n");
	
		if (isset($jquery['ui']) && $jquery['ui'])
			echo('<script type="text/javascript" src="/' . $this->data['baseurlpath'] . 'resources/jquery-ui-16.js"></script>' . "\n");
	
		if (isset($jquery['css']) && $jquery['css'])
			echo('<link rel="stylesheet" media="screen" type="text/css" href="/' . $this->data['baseurlpath'] . 
				'resources/uitheme16/ui.all.css" />' . "\n");	
	}
}

if(!empty($this->data['htmlinject']['htmlContentHead'])) {
	foreach($this->data['htmlinject']['htmlContentHead'] AS $c) {
		echo $c;
	}
}




?>

	
	<meta name="robots" content="noindex, nofollow" />
	

<?php	
if(array_key_exists('head', $this->data)) {
	echo '<!-- head -->' . $this->data['head'] . '<!-- /head -->';
}
?>

<link rel="stylesheet" href="../../../themes/default/styles/core_deprecated.css" type="text/css"/>
<link rel="stylesheet" href="../../../themes/default/styles/core_layout.css" type="text/css"/>
<link rel="stylesheet" href="../../../themes/default/styles/core_typography.css" type="text/css"/>
<link rel="stylesheet" href="../../../themes/default/styles/core_buttons.css" type="text/css"/>

<link rel="stylesheet" href="../../../themes/default/styles/core_zones.css" type="text/css"/>
<link rel="stylesheet" href="../../../themes/default/styles/jquerycss/default/jquery-ui-1.8.2.custom.css" type="text/css"/>
<link rel="stylesheet" href="../../../js/fancybox/jquery.fancybox-1.3.4.css" type="text/css"/>
<link rel="stylesheet" href="../../../themes/default/styles/module_kernel.css" type="text/css"/>

<link rel="stylesheet" href="../../../js/jquery/jquery.tooltip.css" type="text/css"/>

<link rel="stylesheet" href="../../../themes/default/styles/print.css" type="text/css" media="print"/>

<link rel="stylesheet" href="../../../themes/default/styles/module_welcome.css" type="text/css"/>
<link rel="stylesheet" href="../../../themes/default/styles/core_custom.css" type="text/css" />

        <script type="text/javascript">var urlBase = '/'; getRessourcePathImg = urlBase+'themes/default/img/';</script>
<script type="text/javascript" src="../../../js/jquery/jquery.tools.min.js"></script>

<script type="text/javascript" src="../../../js/iconito/iconito.js"></script>

<script type="text/javascript" src="../../../js/iconito/lang_fr.js"></script>
<script type="text/javascript" src="../../../flvplayer/ufo.js"></script>
<script type="text/javascript" src="../../../js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-1.8.custom.min.js"></script>

<script type="text/javascript" src="../../../js/fancybox/jquery.fancybox-1.3.4.js"></script>
<script type="text/javascript" src="../../../js/fancybox/jquery.easing-1.3.pack.js"></script>
<script type="text/javascript" src="../../../js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>

<script src="../../../js/jquery/jquery.bgiframe.js" type="text/javascript"></script>
<script src="../../../js/jquery/jquery.tooltip.min.js" type="text/javascript"></script>

<style>
<!--

form input[type="text"], form input[type="url"], form input[type="email"], form input[type="date"], input[type="password"], form textarea {
    border: 1px solid #F0F4F0;
    border-radius: 6px 6px 6px 6px;
    padding: 3px;
}
form input[type="text"]:hover, form input[type="url"]:hover, form input[type="email"]:hover, form input[type="date"]:hover, input[type="password"]:hover, form textarea:hover {
    border: 1px solid #BAD521;
    border-radius: 6px 6px 6px 6px;
}


body, input, textarea, input[type="password"], input[type="text"] {
    font-family: 'DroidSans',Lucida grande,Arial,Helvetica,sans-serif;
    font-size: 12px;
    letter-spacing: 0;
    text-align: justify;
}

div.help {
    margin-top: 30px;
}

#main-wrapper {
    width: 600px;
}

#content {
    margin: 30px;
    padding: 0px;
    min-height: 0;
}
#page {
    margin-top: 30px;
}


a, a:link, a:link, a:link, a:hover, a:visited {
    border-bottom: none;
    color: #777777;
    font-weight: normal;
    text-decoration: none;
}
.mesgError span {font-weight:normal;}
.button-back {
	float:right;
	margin-top:-4px;
}
-->
</style>

</head>
<?php
$onLoad = '';
if(array_key_exists('autofocus', $this->data)) {
	$onLoad .= 'SimpleSAML_focus(\'' . $this->data['autofocus'] . '\');';
}
if (isset($this->data['onLoad'])) {
	$onLoad .= $this->data['onLoad']; 
}

if($onLoad !== '') {
	$onLoad = ' onload="' . $onLoad . '"';
}
?>
<body class="thm nodebug"<?php echo $onLoad; ?>>

<div id="divUserProfil" onclick="hideUser();"></div>
<div id="ajaxDiv"></div>

<div id="main-wrapper" class="wrapper" style="">

<div id="absolute"></div>
<div id="page">
    <div id="page-header">

        <div class="thm-HL"><div class="thm-HR"><div class="thm-HM">
            <div class="thm-logo padder"><h1><a class="logo" href="../../.."><span class="hiddenClean">ICONITO &Eacute;cole Num&eacute;rique</span></a></h1>
            <div id="top"><div class="collapse"></div></div>
            <div id="menu">
                <div id="menucenter"><div class="collapse"></div></div>
                <div id="menuleft"><div class="filler"></div></div>
                <div id="menuright"><div class="welcome userlogged"></div></div>
            </div>
            </div>
        </div></div></div>
        <div id="welcome_bienvenue"></div>    </div>
    <div id="page-middle">

        <div class="thm-ML"><div class="thm-MR"><div class="thm-MTL"><div class="thm-MTR">
        <div class="marger">
            <div id="breadcrumb"><div class="welcome breadcrumb">Accueil</div></div>
            <div class="wrapper-expander">
                <div id="left"><div class="collapse"></div></div>
                <div class="wrapper-shifter">
                    <div id="content">
                        <div id="contenttop"><div class="collapse"></div></div>

                        <div id="contentmain">
<?php

if(!empty($this->data['htmlinject']['htmlContentPre'])) {
	foreach($this->data['htmlinject']['htmlContentPre'] AS $c) {
		echo $c;
	}
}
