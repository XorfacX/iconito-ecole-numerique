package Corree::Ent::Iconito::Handler::Classe;

use strict;
use warnings;

use parent qw(Corree::Ent::Iconito::Handler);

use v5.10;

use Log::Message::Simple qw[debug carp msg];

use Net::LDAP::Entry;
use Switch;

sub initialize {
	my $self = shift;

	$self->SUPER::initialize(@_);

	$self->query( 'main',
q[SELECT id, ecole, nom FROM kernel_bu_ecole_classe
WHERE is_supprimee = false AND is_validee = true AND annee_scol = ? ]
	);

	$self->query(
		'enseignants',
q(SELECT link.user_id from kernel_bu_personnel_entite AS entite
INNER JOIN kernel_link_bu2user AS link ON link.bu_id = entite.id_per AND link.bu_type = 'USER_ENS'
WHERE type_ref='CLASSE'
AND entite.reference = ? )
	);

	$self->query(
		'eleves',
q(select c.id, c.ecole, link.user_id
from kernel_bu_ecole_classe AS c
inner join kernel_bu_eleve_affectation AS a ON a.classe = c.id AND a.annee_scol=? AND a.current = 1
INNER JOIN kernel_link_bu2user AS link ON link.bu_id = a.eleve AND link.bu_type = 'USER_ELE'
where c.is_validee = 1 and c.is_supprimee = 0 AND c.annee_scol=? 
ORDER BY c.id;)
	);
}

sub jointure {
	my ( $self, $id, $ecole ) = @_;

	return join( '_', $ecole->get_value('ENTStructureSIREN'), $id );
}

sub enseignants_iterator {
	my ( $self, $sdet, $id ) = @_;
	
	my $query = $self->query('enseignants');

	my $person_h = $sdet->creator('ENTPerson');

	$query->execute($id);

	return sub {
		while ( my $user_id = $query->fetchrow_array() ) {
			my $jointure = $self->SUPER::jointure($user_id);

			my $person = $person_h->search($jointure);

			return $person if defined $person ;

			carp "personne non trouvée " . $jointure;
		}
		
		return undef;
	};
}

sub build_entry {
	my $self = shift;
	my $sdet = shift;
	my ( $id, $ecole, $nom ) = @_;

	my $entry = Net::LDAP::Entry->new;

	my $_ecole =
	  $sdet->creator('ENTStructure')->search( $self->SUPER::jointure($ecole) );

	carp "école $nom ($ecole) non trouvée "
	  if not defined $_ecole;

	if ( defined $_ecole ) {
		my $count_members = 0;
		my $next = $self->enseignants_iterator( $sdet, $id );
		
		while ( my $item = $next->() ) {
    		$entry->add( member => $item->dn );
    		$count_members++;
		}

		if ( $count_members > 0 ) {
			$entry->add(
				objectClass => [qw( ENTClasse )],
				cn          => $self->jointure( $id, $_ecole ),
				owner       => $_ecole->dn(),
				description => $nom
			);
			return $entry;
		}
		else {
			carp "Aucun enseignant est affecté à la classe '$nom' ($id) de '"
			  . $_ecole->get_value('ou')
			  . "' ($ecole)";
		}
	}

	return undef;
}


sub eleves
{
    my ( $self, $sdet, $year ) = @_;

    my $query = $self->query('eleves');

    $query->execute($year, $year);

    my $struct_h = $sdet->creator('ENTStructure');

    my $classe_h = $sdet->creator('ENTClasse');

	my $person_h = $sdet->creator('ENTPerson');

	while ( my @data = $query->fetchrow_array() ) {
		my ( $id, $ecole, $user_id ) = @data;

		my $jointure = $self->SUPER::jointure($ecole);

		my $_ecole =
	  		$sdet->creator('ENTStructure')->search( $jointure );

	  	if (not defined $_ecole) {
			carp "école $jointure non trouvée ", 1;
			next;
		}

		$jointure = $self->jointure($id, $_ecole);
	    #my $cn = join('_', $structure->get_value('ENTStructureSIREN'), $id );
		my $classe = $classe_h->search($jointure);
	    #my $classe = $classe_h->search( $cn );

	    if ( not defined $classe ) {
 			carp "classe non trouvée " . $jointure, 1;

	        next;
		}

	    $jointure = $self->SUPER::jointure($user_id);

	    my $_eleve = $person_h->search($jointure);

	    if ( not defined $_eleve ) {
	    	carp "élève non trouvé " . $jointure;

			next;
		}

		debug "ajout de l\'élève " . $_eleve->dn . " à la classe " . $classe->dn, 1;

		$classe->add( member => $_eleve->dn );

		$sdet->update($classe);
	}
}

1;
