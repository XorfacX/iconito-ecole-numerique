package Corree::Ent::Iconito::Handler::Personnel;

use strict;
use warnings;

use MIME::Base64;

use parent qw(Corree::Ent::Iconito::Handler);

use v5.10;

use Net::LDAP::Entry;
use Switch;

use Log::Message::Simple qw[debug carp msg];

sub initialize {
	my $self = shift;

	$self->SUPER::initialize(@_);

	$self->query('main',
q[SELECT person.numero, link.user_id, link.bu_type, person.nom, person.prenom1, person.date_nais, COALESCE(person.mel, prefs.value), sexe.sexe, role.nom_role
FROM kernel_bu_personnel AS person
INNER JOIN kernel_link_bu2user AS link ON link.bu_id = person.numero AND link.bu_type='USER_ENS'
INNER JOIN kernel_bu_personnel_entite AS entite ON entite.id_per = person.numero AND entite.type_ref = 'ECOLE'
INNER JOIN kernel_bu_personnel_role AS role ON role.id_role = entite.role AND LOCATE('ECOLE', role.perimetre) > 0
LEFT JOIN kernel_bu_sexe AS sexe ON sexe.id_s = person.id_sexe
LEFT OUTER JOIN (SELECT user, value FROM module_prefs_preferences WHERE code='alerte_mail_email' AND module='prefs' LIMIT 1) AS prefs ON prefs.user = link.user_id]
	);

	$self->query(
		'structRattach', q[SELECT user.id_dbuser, entite.reference
FROM kernel_bu_personnel AS person
INNER JOIN kernel_link_bu2user AS link ON link.bu_id = person.numero AND link.bu_type='USER_ENS'
INNER JOIN dbuser AS user ON user.id_dbuser = link.user_id
INNER JOIN kernel_bu_personnel_entite AS entite ON entite.id_per = person.numero AND entite.type_ref = 'ECOLE'
INNER JOIN kernel_bu_personnel_role AS role ON role.id_role = entite.role AND LOCATE('ECOLE', role.perimetre) > 0]
	);

	$self->query(
		'classes', q[SELECT user.id_dbuser, classe.id, classe.ecole
FROM kernel_bu_personnel AS person
INNER JOIN kernel_link_bu2user AS link ON link.bu_id = person.numero AND link.bu_type='USER_ENS'
INNER JOIN dbuser AS user ON user.id_dbuser = link.user_id
INNER JOIN kernel_bu_personnel_entite AS entite ON entite.id_per = person.numero AND entite.type_ref = 'CLASSE'
INNER JOIN kernel_bu_ecole_classe AS classe ON classe.id = entite.reference AND classe.is_validee = 1 AND classe.is_supprimee = 0
-- INNER JOIN kernel_bu_personnel_role AS role ON role.id_role = entite.role AND LOCATE('ECOLE', role.perimetre) > 0]
	);
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
		$numero,       $user_id, $bu_type,      $nom,
		$prenom1,      $date_nais, $mel, $sexe,      $nom_role
	) = @_;

	my $entry = Net::LDAP::Entry->new;

	if ( $bu_type eq 'USER_ENS' ) {
		$entry->add(
			objectClass      => [qw( ENTPerson ENTAuxEnseignant )],
			ENTPersonProfils => [ $sdet->get_role('ROLE_ENSEIGNANT') ]
		);
	}
	else {
		$entry->add(
			objectClass      => [qw( ENTPerson ENTAuxNonEnsEtab )],
			ENTPersonProfils => [ $sdet->get_role('ROLE_NONENSEIGNANT') ]
		);
	}

	# $password_dbuser = unhex( $password_dbuser);
	
	$entry->add(
		sn                => $nom,
		cn                => join( ' ', $nom, $prenom1 ),
		displayName       => join( ' ', $prenom1, $nom ),
		ENTPersonJointure => $self->jointure($user_id),
		uid               => 'Uik0' . sprintf( "%04d", $user_id ),
		givenName         => $prenom1,
		ENTPersonLogin    => $nom.$prenom1,
		ENTPersonNomPatro => $nom,
		userPassword 	  => '{MD5}' . encode_base64( rand(100) ),
	);

	if ($mel) {
		$entry->add( mail => $mel );
	}

	if ( $sexe eq 'M' ) {
		$entry->add( personalTitle => 'M.' );
	}
	elsif ( $sexe eq 'F' ) {
		$entry->add( personalTitle => 'Mme' );
	}

	if ($date_nais) {

		#$entry->add( ENTPersonDateNaissance => $date_nais );
	}

	if ( $nom_role eq 'Directeur' ) {
		my @roles = $sdet->get_role( 'ROLE_CHEFDETABLISSEMENT' );

		
		if ($mel) {
			push( @roles, $sdet->get_role('ROLE_SUPERVISEUR') );
		}
		else {
			msg "le rôle SUPERVISEUR n'est pas attribué à $user_id car il n'a pas de mél", 1; 
		}

		$entry->add(
			ENTPersonProfils => \@roles,
		);
	}

	return $entry;
}

sub struct_rattach {
	my ( $self, $sdet ) = @_;

	my $query = $self->query('structRattach');

	$query->execute();

	my $struct_h = $sdet->creator('ENTStructure');

	my $person_h = $sdet->creator('ENTPerson');

	while ( my @data = $query->fetchrow_array() ) {
		my ( $user_id, $reference ) = @data;

		my $jointure = $self->jointure($reference);

		my $structure = $struct_h->search($jointure);

		if ( not defined $structure ) {
			carp "structure non trouvée " . $jointure;

			next;
		}

		$jointure = $self->jointure($user_id);

		my $person = $person_h->search($jointure);

		if ( not defined $person ) {
			carp "personne non trouvée " . $jointure;

			next;
		}

		$person->add( ENTPersonStructRattach => $structure->dn );

		$sdet->update($person);
	}
}


sub classes
{
	my ( $self, $sdet ) = @_;

	my $query = $self->query('classes');

	$query->execute();

	my $struct_h = $sdet->creator('ENTStructure');

	my $classe_h = $sdet->creator('ENTClasse');

	my $person_h = $sdet->creator('ENTPerson');

	while ( my @data = $query->fetchrow_array() ) {
		my ( $user_id, $id, $ecole ) = @data;

		my $jointure = $self->jointure($ecole);

		my $structure = $struct_h->search($jointure);

		if ( not defined $structure ) {
			carp "structure non trouvée " . $jointure;

			next;
		}

		$jointure = $self->jointure($user_id);

		my $person = $person_h->search($jointure);

		if ( not defined $person ) {
			carp "personne non trouvée " . $jointure;

			next;
		}

		my $cn = join('_', $structure->get_value('ENTStructureSIREN'), $id );

		my $classe = $classe_h->search( $cn );

		if ( not defined $classe ) {
			carp "classe non trouvée " . $cn;

			next;
		}

		debug "ajout de la classe " . $classe->dn . " à " . $person->dn;

		$person->add( ENTAuxEnsClasses => $classe->dn );

		$sdet->update($person);
	}

}
1;
