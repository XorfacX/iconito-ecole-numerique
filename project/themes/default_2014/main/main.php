<?php
/*
	@file 		main.php
	@desc		Main layout constructor
	@version 	1.0.0b
	@date 		2010-05-28 09:28:09 +0200 (Fri, 28 May 2010)
	@author 	S.HOLTZ <sholtz@cap-tic.fr>

	Copyright (c) 2010 CAP-TIC <http://www.cap-tic.fr>
*/
?>
<?php
include_once COPIX_PROJECT_PATH."themes/default/helper.php";
$module = CopixRequest::get ('module');?>
<!doctype html>
<!--[if lte IE 6]> <html class="no-js ie6 ie67 ie678 ie" lang="fr"> <![endif]-->
<!--[if IE 7]> <html class="no-js ie7 ie67 ie678 ie" lang="fr"> <![endif]-->
<!--[if IE 8]> <html class="no-js ie8 ie678 ie" lang="fr"> <![endif]-->
<!--[if IE 9]> <html class="no-js ie9 ie" lang="fr"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="fr"> <!--<![endif]-->
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=10">
	<title><?php echo isset ($TITLE_BAR) ? $TITLE_BAR : ''; ?></title>
	<meta name="viewport" content="initial-scale=1.0">
	<meta name="description" content="ICONITO Ecole Numérique">
	<meta name="keywords" content="ent iconito cap tic">
    <link rel="icon" type="image/x-icon" href="<?php echo CopixUrl::getRequestedScriptPath(); ?>favicon.ico" />

    <link  rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/knacss.css");?>" />
    <?php include_once COPIX_PROJECT_PATH."themes/default_2014/styles.php"; ?>
    <?php include_once COPIX_PROJECT_PATH."themes/default_2014/scripts.php"; ?>
    <?php echo $HTML_HEAD; ?>
	
	<!--[if lt IE 9]><script src="<?php echo CopixUrl::getResource ("js/html5shiv.js");?>"></script><![endif]-->

</head>
    <!-- MAIN -->
    <body class="">
        <!-- skip links for accessibility -->
        <ul class="skip-links">
            <li><a href="#navigation">Aller au menu</a></li>
            <li><a href="#main">Aller au contenu</a></li>
        </ul><!-- /skip-links -->
        
        <div id="popup"><?php getZones("popup"); ?></div>
        <header id="header" role="banner" class="">
            <div class="marginCenter w960p ">
                <a href="<?php echo CopixUrl::get() ?>" class="logo"><img class="mt1 mb1" src="<?=CopixUrl::getResource('img/logo-ecole-numerique.png');?>" alt="ICONITO &Eacute;cole Num&eacute;rique" /></a>
                <div class="right mt2 mb1">
                    <?php getZones('login');?>
                </div>
                
            </div>
            <div class="mt3 clear bgPrimary"> 
                <div class="marginCenter w960p bfc">
                    <?php getZones('menu', false);?>
                    <h1 class="main-title mb0 mt0"><a href="<?=CopixUrl::get();?>">Ecole Numérique</a></h1>
                </div>
            </div>
        </header><!-- header -->
        
        <div id="main" role="main" class="line marginCenter w960p mt2 mb5">
		<div class="mod p-reset">

                <!-- Main content -->
                <div class="clearfix">
                    <aside class="aside right">
                        <?php getZones("right", false);?>
                    </aside>

                    <?php $title = (isset($TITLE_PAGE)) ? $TITLE_PAGE : ''; ?>
                    <?php if (inDashContext()) { moduleContext('open', $title); } ?>
                    <div id="<?php echo $module; ?>" class="<?php echo $module; ?>">
                        <?php if (isset($MENU) && $MENU) { echo CopixZone::process ('kernel|menu', array('MENU'=>$MENU, 'popup'=>true, 'canClose'=>(isset($CAN_CLOSE)?$CAN_CLOSE:false))); } ?>
                        <?php echo $MAIN; ?>
                    </div>
                    <?php if (inDashContext()) { moduleContext('close'); } ?>

			</div><!-- /.items for Main content -->
		</div><!-- /.mod -->
	</div><!-- main -->


    <footer id="footer" role="contentinfo" class="marginCenter w960p pb2 mod">
        <?php getZones("footer"); ?>
	</footer><!-- /footer -->

	<div id="debug"><?php getZones("debug"); ?></div>
	<div id="divUserProfil" onclick="hideUser();"></div>
	<div id="ajaxDiv"></div>

	<div id="absolute"></div>

</body>
</html>