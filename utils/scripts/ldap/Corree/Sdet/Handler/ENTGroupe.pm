package Corree::Sdet::Handler::ENTGroupe;

use strict;
use warnings;

use parent qw( Corree::Sdet::Handler::GroupOfNames );

sub parent {
	my $self = shift;
	
	my $rdn  = join('=', 'ou', 'groupes');
	
	return join(',', $rdn, $self->SUPER::parent() );
}

sub search_filter{
	my ( $self, $jointure ) = @_;
	
	return "(&(objectClass=ENTGroupe)(cn=$jointure))";
}

1;
