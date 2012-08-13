<?php

$getNode		= new CopixAction ('Comptes', 'getNode');  // Affichage d'un noeud avec les utilisateurs attach�s.
$getUser		= new CopixAction ('Comptes', 'getUser');  // Infos sur un utilisateur particulier (changement de pass, etc.)
$setUserPasswd		= new CopixAction ('Comptes', 'setUserPasswd');  // Enregistrement de mot de passe
$getRoles		= new CopixAction ('Comptes', 'getRoles');  // Liste des r�les

$go				= new CopixAction ('Comptes', 'go');       // Appel par d�faut. D�termine la racine d'administration et appelle getNode.

$getLoginForm	= new CopixAction ('Comptes', 'getLoginForm'); // Affiche, pour une liste d'utilisateurs (type/id) des propositions de login/passwd.
$doLoginCreate	= new CopixAction ('Comptes', 'doLoginCreate'); // Execute la cr�attion des comptes et sauvegarde les infos en session.

$getLoginResult	= new CopixAction ('Comptes', 'getLoginResult'); // Affichage des comptes cr��s.
$getPurgeResult = new CopixAction ('Comptes', 'getPurgeResult'); // Propose l'effacement de donn�es en session.
$doPurgeResult  = new CopixAction ('Comptes', 'doPurgeResult'); // Efface les information de la session.

$getUserExt		= new CopixAction ('Comptes', 'getUserExt'); // Affichage des comptes externes.
$getUserExtAdd	= new CopixAction ('Comptes', 'getUserExtAdd'); // Formulaire d'ajout d'un compte.
$getUserExtDel	= new CopixAction ('Comptes', 'getUserExtDel'); // Formulaire de suppression d'un compte.
$getUserExtMod	= new CopixAction ('Comptes', 'getUserExtMod'); // Formulaire de modification d'un compte.

$default		= & $go;

?>
