package Corree::Sdet::Handler::ENTClasse;

use strict;
use warnings;

use parent qw( Corree::Sdet::Handler::GroupOfNames );

use Carp;

sub parent {
	my $self = shift;
	
	my $rdn  = join('=', 'ou', 'groupes');
	
	return join(',', $rdn, $self->SUPER::parent() );
}

sub search_filter{
	my ( $self, $jointure ) = @_;
	
	return "(&(objectClass=ENTClasse)(cn=$jointure))";
}

1;
