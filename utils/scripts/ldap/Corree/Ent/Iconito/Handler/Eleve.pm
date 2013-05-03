package Corree::Ent::Iconito::Handler::Eleve;

use strict;
use warnings;

use parent qw(Corree::Ent::Iconito::Handler);

use v5.10;

use Log::Message::Simple qw[msg error debug carp croak cluck confess];

use Net::LDAP::Entry;
use Switch;

use MIME::Base64;

use Corree::Ent::Iconito::Handler::Eleve;

sub initialize {
	my $self = shift;

	$self->SUPER::initialize(@_);

	$self->query('main',
q(SELECT user.id_dbuser, eleve.nom, eleve.prenom1, eleve.date_nais, user.login_dbuser, user.email_dbuser, niveau.niveau_court, niveau.niveau, classe.ecole, classe.id, user.password_dbuser
FROM kernel_bu_eleve AS eleve
INNER JOIN kernel_link_bu2user AS link ON link.bu_id = eleve.idEleve
INNER JOIN dbuser AS user ON user.id_dbuser = link.user_id
LEFT JOIN kernel_bu_eleve_affectation AS aff ON aff.eleve = eleve.idEleve
INNER JOIN kernel_bu_ecole_classe AS classe ON classe.id = aff.classe AND aff.current = 1 AND aff.annee_scol = ?
INNER JOIN kernel_bu_classe_niveau AS niveau ON niveau.id_n = aff.niveau
WHERE link.bu_type = 'USER_ELE')
	);
}

sub code_filiere {
	switch (shift) {
		case 'TPS' { return '11100'; }
		case 'PS'  { return '11110'; }
		case 'MS'  { return '11120'; }
		case 'GS'  { return '11130'; }
		case 'CP'  { return '11210'; }
		case 'CE1' { return '11220'; }
		case 'CE2' { return '11230'; }
		case 'CM1' { return '11240'; }
		case 'CM2' { return '11250'; }
		else       { return undef; }
	}
}

sub code_niveau {
	switch (shift) {
		case 'TPS' { return '1110'; }
		case 'PS'  { return '1111'; }
		case 'MS'  { return '1112'; }
		case 'GS'  { return '1113'; }
		case 'CP'  { return '1121'; }
		case 'CE1' { return '1122'; }
		case 'CE2' { return '1123'; }
		case 'CM1' { return '1124'; }
		case 'CM2' { return '1125'; }
		else       { return undef; }
	}
}

sub code_mef {
	switch (shift) {
		case 'TPS' { return '00010001310'; }
		case 'PS'  { return '00010001320'; }
		case 'MS'  { return '1000132'; }
		case 'GS'  { return '00010001330'; }
		case 'CP'  { return '00110002110'; }
		case 'CE1' { return '00210002210'; }
		case 'CE2' { return '00210002220'; }
		case 'CM1' { return '00310002210'; }
		case 'CM2' { return '00310002220'; }
		else       { return undef; }
	}
}

sub unhex {
	my $hex_string = shift;
		
	$hex_string =~ s/(..)/chr(hex($1))/ge;
	
	return $hex_string;
}

sub build_entry {
	my $self = shift;
	my $sdet = shift;
	
	my (
		$id_dbuser,    $nom,          $prenom1,      $date_nais,
		$login_dbuser, $email_dbuser, $niveau_court, $niveau,
		$ecole,        $classe, $password_dbuser
	) = @_;

	my $entry = Net::LDAP::Entry->new;

	$password_dbuser = unhex( $password_dbuser );
	$entry->add(
		objectClass       => [qw( ENTEleve )],
		ENTPersonProfils  => [ $sdet->get_role('ROLE_ELEVE') ],
		sn                => $nom,
		cn                => join( ' ', $nom, $prenom1 ),
		displayName       => join( ' ', $prenom1, $nom ),
		ENTPersonJointure => $self->jointure($id_dbuser),
		uid               => 'Uik0' . sprintf( "%04d", $id_dbuser ),
		givenName         => $prenom1,
		ENTPersonNomPatro => $nom,
		ENTEleveMajeur    => 'N',
		ENTEleveStatutEleve => 'NA',
		ENTPersonLogin => $login_dbuser,
		userPassword => rand(100), 
	);

	
	if ($email_dbuser) {
		$entry->add( mail => $email_dbuser );
	}

	if ($date_nais) {

		#$entry->add( ENTPersonDateNaissance => $date_nais );
	}

	my $mef = code_mef($niveau_court);

	if ($mef) {
		$entry->add(
			ENTEleveMEF          => $mef,
			ENTEleveLibelleMEF   => $niveau,
			ENTEleveNivFormation => code_niveau($niveau_court),
			ENTEleveFiliere      => code_filiere($niveau_court)
		);
	}
	else {
		carp "niveau $niveau inconnu";
		return undef;
	}

	my $struct = $sdet->creator('ENTStructure')->search( $self->jointure($ecole) );

	if ($struct) {
		$entry->add( ENTPersonStructRattach => $struct->dn );
		
		my $dn = Corree::Ent::Iconito::Handler::Classe->clone( $self )->jointure($classe, $struct );
		
		my $_classe = $sdet->creator('ENTClasse')->search( $dn );

		if ($_classe) {
			$entry->add( ENTEleveClasses => $_classe->dn );
			$_classe->add( member => $entry->dn );
		}
		else {
			carp "classe non trouvée " . $dn;
			return undef;
		}
	}
	else {
		carp "école non trouvée " . $self->jointure($ecole);
	}

	return $entry;
}

1;
