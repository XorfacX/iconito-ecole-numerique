<?php
$this->data['header'] = $this->t('{login:user_pass_header}');

if (strlen($this->data['username']) > 0) {
	$this->data['autofocus'] = 'password';
} else {
	$this->data['autofocus'] = 'username';
}
$this->includeAtTemplateBase('includes/header.php');

?>

<h2><?php echo $this->t('{login:user_pass_header}'); ?> <a href="../../../" class="button button-back">Retour</a></h2>


<?php
if ($this->data['errorcode'] !== NULL) {
?>
	<p class="mesgError">
		<?php echo $this->t('{errors:title_' . $this->data['errorcode'] . '}'); ?><br />
		<span><?php echo $this->t('{errors:descr_' . $this->data['errorcode'] . '}'); ?></span>
	</p>
<?php
}
?>
	
	<p><?php // echo $this->t('{login:user_pass_text}'); ?></p>

	<form action="?" method="post" name="f" class="edit">
    
    <div class="field">
		<label for="username"><?php echo $this->t('{login:username}'); ?></label>
		<p class="input"><?php
        if ($this->data['forceUsername']) {
            echo '<strong style="font-size: medium">' . htmlspecialchars($this->data['username']) . '</strong>';
        } else {
            echo '<input type="text" id="username" tabindex="1" name="username" value="' . htmlspecialchars($this->data['username']) . '" autocomplete="off" />';
        }
        ?></p>
    </div>
    <div class="field">
    	<label for="password"><?php echo $this->t('{login:password}'); ?></label>
        <p class="input"><input id="password" type="password" tabindex="2" name="password" autocomplete="off" /></p>
    </div>
    <div class="submit">
		<input type="submit" tabindex="4" value="<?php echo $this->t('{login:login_button}'); ?>" class="button button-confirm" />
	</div>
    
	<?php
    if (array_key_exists('organizations', $this->data)) {
    ?>
		<div class="field">
			<label for="organization"><?php echo $this->t('{login:organization}'); ?></label>
			<p class="input"><select name="organization" id="organization" tabindex="3">
		<?php
        if (array_key_exists('selectedOrg', $this->data)) {
            $selectedOrg = $this->data['selectedOrg'];
        } else {
            $selectedOrg = NULL;
        }

		foreach ($this->data['organizations'] as $orgId => $orgDesc) {
			if (is_array($orgDesc)) {
				$orgDesc = $this->t($orgDesc);
			}
		
			if ($orgId === $selectedOrg) {
				$selected = 'selected="selected" ';
			} else {
				$selected = '';
			}
		
			echo '<option ' . $selected . 'value="' . htmlspecialchars($orgId) . '">' . htmlspecialchars($orgDesc) . '</option>';
		}
		?>
			</select></p>
		</div>
	<?php
    }
    ?>


	<?php
    foreach ($this->data['stateparams'] as $name => $value) {
        echo('<input type="hidden" name="' . htmlspecialchars($name) . '" value="' . htmlspecialchars($value) . '" />');
    }
    ?>

	</form>

<?php

if(!empty($this->data['links'])) {
	echo '<ul class="links" style="margin-top: 2em">';
	foreach($this->data['links'] AS $l) {
		echo '<li><a href="' . htmlspecialchars($l['href']) . '">' . htmlspecialchars($this->t($l['text'])) . '</a></li>';
	}
	echo '</ul>';
}




/*echo('<div class="help">');
echo('<h2>' . $this->t('{login:help_header}') . '</h2>');
echo('<p>' . $this->t('{login:help_text}') . '</p>');
echo('</div>');
*/
$this->includeAtTemplateBase('includes/footer.php');
?>
