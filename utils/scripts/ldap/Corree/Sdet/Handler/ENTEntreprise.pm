package Corree::Sdet::Handler::ENTEntreprise;

use strict;
use warnings;

use parent qw( Corree::Sdet::Handler::ENTStructure );

use Corree::Sdet::Utils;

sub update_entry {
	my $self = shift;

	my ( $current, $entry ) = @_;

	return $self->SUPER::update_entry(@_) + Corree::Sdet::Utils::update_attrs(
		$current, $entry, qw( ENTEntrepriseEtabs )
	);
}

1;
