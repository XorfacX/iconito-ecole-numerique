package Corree::Ent::Iconito::Handler::Ecole;

use strict;
use warnings;

use v5.10;

use parent qw(Corree::Ent::Iconito::Handler);

use Log::Message::Simple qw[msg error debug carp croak cluck confess];
use Net::LDAP::Entry;
use Switch;

sub initialize {
	my $self = shift;

	$self->SUPER::initialize(@_);

	$self->query(
		'main',
q( SELECT numero, siret, rne, nom, type, commune, num_rue, num_seq, adresse1, adresse2, code_postal, tel, web
FROM kernel_bu_ecole )
	);

	$self->query(
		'responsable', q(SELECT user_id
FROM kernel_bu_personnel_entite AS entite
INNER JOIN kernel_bu_personnel_role AS role ON role.id_role = entite.role
INNER JOIN kernel_bu_personnel as person on person.numero = id_per 
INNER JOIN kernel_link_bu2user AS link ON link.bu_id = person.numero AND link.bu_type = 'USER_ENS'
INNER JOIN dbuser ON dbuser.id_dbuser = link.user_id
WHERE entite.type_ref = 'ECOLE' AND role.nom_role = 'Directeur' AND LOCATE('ECOLE', role.perimetre) > 0 and entite.reference = ? )
	);
}

sub code_type {
	switch (shift) {
		case 'ElÃ©mentaire' { return 151; }
		case 'Primaire'     { return 159; }
		case 'Maternelle'   { return 101; }
		else                { return 0; }
	}
}

sub responsable_iterator {
	my ( $self, $sdet, $numero ) = @_;
	
	my $query = $self->query('responsable');

	my $person_h = $sdet->creator('ENTPerson');
	
	$query->execute($numero);
	
	return sub {
		while ( my ($id) = $query->fetchrow_array() ) {
			my $jointure = $self->jointure( $id );
		
			my $person = $person_h->search( $jointure );

			return $person if defined $person ;

			debug "personne non trouvÃ©e " . $jointure, 1;
		}
	}
}

sub build_entry {
	my $self = shift;
	my $sdet = shift;
	
	my (
		$numero,      $siret,	$rne,     $nom,     $type, 
		$commune,     $num_rue, $num_seq, $adresse1, $adresse2,
		$code_postal, $tel,     $web
	) = @_;

	my $entry = Net::LDAP::Entry->new;

	my @adresse =
	  grep { defined $_ } ( $num_rue, $num_seq, $adresse1, $adresse2 );

	if (@adresse) {
		$entry->add( street => join( ' ', @adresse ) );
	}

	if ($code_postal) {
		$entry->add( postalCode => $code_postal );
	}
	
	my $count_responsables = 0;
	
	my $next = $self->responsable_iterator( $sdet, $numero );
		
	while ( my $item = $next->() ) {
    	$entry->replace( ENTStructureResponsable => $item->dn );
    	$count_responsables++;
	}
	 
	if ( $count_responsables > 0 ) {

		$entry->add(
			ou                     => $nom,
			objectClass            => [qw(ENTEtablissement)],
			ENTStructureJointure   => $self->jointure($numero),
			ENTStructureTypeStruct => code_type($type),
			ENTStructureNomCourant => $nom,
			ENTStructureSIREN      => $siret,
			ENTEtablissementMinistereTutelle => '06',
			ENTEtablissementContrat          => 'PU'
		);

		if ($rne) {
			$entry->add( ENTStructureUAI => $rne, );
		}

		if ($web) {
			$entry->add( ENTStructureSiteWeb => $web, );
		}

		if ($tel) {
			$entry->add( telephoneNumber => $tel, );
		}

		if ($commune) {
			$entry->add( l => $commune, );
		}
		return $entry;
	}
	else {
		error "Ã©cole $nom (\#$numero) n'a pas été importé car aucun responsable n'est défini", 1;

		return undef;
	}
}

1;
