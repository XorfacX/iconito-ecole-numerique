package Corree::Ent::Coredu::Builder::ENTEntreprise;

use v5.10;
use strict;
use warnings;

use Log::Message::Simple qw[msg error debug];

use parent qw(Corree::Ent::Coredu::Builder::Top);

sub responsable {
	my ( $self, $empId ) = @_;
	
	my $jointure = $self->jointure( $empId );

	my $person = $self->sdet->creator('ENTPerson')->search(  $jointure );

	return $person if defined $person;

	error "personne non trouvée " . $jointure, 1;
	
	return undef;
}

sub build {
	my ( $self, $entry, $responsable ) = @_;

	my $entrp = Net::LDAP::Entry->new;

	my $siren = $entry->get_value("supannEtablissement");

	if ($entry->get_value('employeeType') ne 'responsable') {
		debug 'employeeType != responsable', 1;

		return undef;
	}
	if (! $siren) {
		error "supannEtablissement not defined for " . $entry->dn(), 1;
		
		return undef;
	}
	

	#my $empId = $entry->get_value("supannEmpId");
	
#	if (! $empId) {
#		carp "supannEmpId not defined for " . $entry->dn();
#		
#		return undef;
#	}
	
	# HACK : supprime les espaces contenus dans le siren
	$siren =~ s/\s+//;

	my $ou = $entry->get_value("supannOrganisme");

	$entrp->add(
		objectClass             => 'ENTEntreprise',
		ou                      => $ou,
		ENTStructureNomCourant  => $ou,
		ENTStructureSIREN       => $siren,
		ENTStructureJointure    => $self->jointure($siren),
		ENTStructureResponsable => $responsable->dn 
	);
	
	if ( defined ( my $l = $entry->get_value('l') ) ) {
		$entrp->add( l => $l );
	}

	if ( defined ( my $postalAddress = $entry->get_value('postalAddress') )  ) {
		$entrp->add( postalAddress => $postalAddress );
	}



#	my $responsable = $self->responsable($empId );
#	
#	if (!$responsable) 	{
#		carp "éditeur ($siren) sans responsable";
#
#		return undef;
#	}
#	
#    $entrp->replace( ENTStructureResponsable => $responsable->dn );
#    
	return $entrp;
}

1;
