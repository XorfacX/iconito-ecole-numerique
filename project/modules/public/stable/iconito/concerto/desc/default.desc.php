<?php

$go             = & new CopixAction ('Concerto', 'go');       // Affiche le formulaire de connexion et autosumbit.
$logout         = & new CopixAction ('Concerto', 'logout');   // Retour � Iconito apr�s d�connexion de l'Espace Famille
$return         = & new CopixAction ('Concerto', 'logout');   // Retour � Iconito apr�s d�connexion de l'Espace Famille
$init           = & new CopixAction ('Concerto', 'init');     // Cr�ation des comptes parents

$default        = & $go;

?>
