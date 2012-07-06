<?php

$module = CopixRequest::get ('module');
$ENpopup = 1;

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
	<meta content="text/html; charset=UTF-8" http-equiv="content-type" />
	<title><?php echo isset ($TITLE_BAR) ? $TITLE_BAR : ''; ?></title>
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo CopixUrl::get () ?>favicon.ico" />
	<?php include_once COPIX_PROJECT_PATH."themes/default/scripts.php"; ?>
	<?php include_once COPIX_PROJECT_PATH."themes/default/styles.php"; ?>
	<?php echo $HTML_HEAD; ?>
</head>


<style>
<!--
body.thm {
	padding: 0;
}

#page {
	padding: 5px;
}
-->
</style>

<body class="thm nodebug"<?php if (isset($BODY_ON_LOAD) && $BODY_ON_LOAD) echo ' onLoad="'.$BODY_ON_LOAD.'"'; if (isset($BODY_ON_UNLOAD) && $BODY_ON_UNLOAD) echo ' onUnLoad="'.$BODY_ON_UNLOAD.'"'; ?>>

<?php if($ppo->field == 'new_admins') { ?>
<div id="submenu" style="position: relative; margin-bottom: 5px;"><div class="menuitems">
	<ul style="font-size: 120%;">
		<li><a class="users" href="<?php echo CopixURL::get('annuaire||getPopup', array('field'=>_request('field'))); ?>">
			<span class="valign"></span>
			<span><?php echo _i18n('annuaire|annuaire.users') ?></span>
		</a></li>
		<li><a class="users" href="<?php echo CopixURL::get('annuaire||getPopup', array('field'=>_request('field'), 'mode'=>'ext')); ?>">
			<span class="valign"></span>
			<span><?php echo _i18n('annuaire|annuaire.ext') ?></span>
		</a></li>
	</ul>
</div></div>
<?php } ?>


<div id="divUserProfil" onclick="hideUser();"></div>
<div id="ajaxDiv"></div>

<div id="page">
						<div id="contentmain">
							<div class="<?php echo $module; ?>">
							<?php if (isset($MENU) && $MENU) { echo CopixZone::process ('kernel|menu', array('MENU'=>$MENU, 'popup'=>true, 'canClose'=>(isset($CAN_CLOSE)?$CAN_CLOSE:false))); } ?>
							<?php echo $MAIN; ?>
							</div>
						</div>
</div><!-- page -->

</body>
</html>