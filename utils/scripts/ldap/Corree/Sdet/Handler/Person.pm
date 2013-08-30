package Corree::Sdet::Handler::Person;

use strict;
use warnings;

use parent qw( Corree::Sdet::Handler::Top );

use Corree::Sdet::Utils;

sub update_entry {
	my $self = shift;
	
	my ($current, $entry) = @_;
	
	return $self->SUPER::update_entry( @_ )  + Corree::Sdet::Utils::update_attrs( $current, $entry, qw(cn sn userPassword telephoneNumber seeAlso description) );
}

1;
