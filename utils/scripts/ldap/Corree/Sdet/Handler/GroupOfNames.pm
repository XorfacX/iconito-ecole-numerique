package Corree::Sdet::Handler::GroupOfNames;

use strict;
use warnings;

use parent qw( Corree::Sdet::Handler::Top );

use Corree::Sdet::Utils;

sub dn_attributes {
	return [qw(cn)];
}

sub search_filter {
	my ( $self, $jointure ) = @_;

	return "(&(objectClass=GroupOfNames)(cn=$jointure))";
}

sub update_entry {
	my $self = shift;

	my ( $current, $entry ) = @_;
	return $self->SUPER::update_entry(@_) + Corree::Sdet::Utils::update_attrs( $current, $entry,
		(qw(cn member description businessCategory o ou owner seeAlso )) );
}

sub _extract_jointure {
	my ( $self, $entry ) = @_;

	return $entry->get_value('cn');
}

1;
