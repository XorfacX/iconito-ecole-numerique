package Corree::Sdet::Handler::OrganizationalPerson;

use strict;
use warnings;

use parent qw( Corree::Sdet::Handler::Person );

use Corree::Sdet::Utils;

sub update_entry {
	my $self = shift;

	my ( $current, $entry ) = @_;

	return $self->SUPER::update_entry(@_)  + Corree::Sdet::Utils::update_attrs(
		$current, $entry,
		qw(title x121Address registeredAddress destinationIndicator preferredDeliveryMethod telexNumber teletexTerminalIdentifier
		  telephoneNumber internationaliSDNNumber
		  facsimileTelephoneNumber street postOfficeBox postalCode 	postalAddress physicalDeliveryOfficeName  ou  st  l  )
	);
}

1;
