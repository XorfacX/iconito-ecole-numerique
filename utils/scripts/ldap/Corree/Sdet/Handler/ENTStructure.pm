package Corree::Sdet::Handler::ENTStructure;

use strict;
use warnings;

use parent qw( Corree::Sdet::Handler::OrganizationalUnit );

use Corree::Sdet::Utils;

sub initialize {
	my $self = shift;

	$self->SUPER::_initialize(@_);

	$self->{'jointure'} = 'ENTStructureJointure';
}

sub parent {
	my $self = shift;
	
	my $rdn  = join('=', 'ou', 'structures');
	
	return join(',', $rdn, $self->SUPER::parent() );
}

sub dn_attributes {
	return [qw(ENTStructureSIREN)];
}

sub search_filter {
	my ( $self, $jointure ) = @_;

	return "(&(objectClass=ENTStructure)(ENTStructureJointure=$jointure))";
}

sub update_entry {
	my $self = shift;

	my ( $current, $entry ) = @_;

	# ne pas modifier ENTStructureSIREN
	return $self->SUPER::update_entry(@_) + Corree::Sdet::Utils::update_attrs(
		$current, $entry, qw( ENTStructureJointure
		  ENTStructureNomCourant
		  ENTStructureResponsable
		  ENTStructureUAI
		  ENTStructureTypeStruct
		  ENTStructureEmail
		  ENTStructureSiteWeb
		  ENTStructureContactENT
		  )
	);
}

sub _extract_jointure {
	  my ( $self, $entry ) = @_;

	  return $entry->get_value('ENTStructureJointure');
}

1;
