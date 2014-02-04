package Corree::Sdet::Handler::OrganizationalUnit;

use strict;
use warnings;

use parent qw( Corree::Sdet::Handler::Top );

use Corree::Sdet::Utils;

sub dn_attributes {
	return [qw(ou)];
}

sub update_entry {
	my $self = shift;

	my ( $current, $entry ) = @_;

	return $self->SUPER::update_entry(@_) +  Corree::Sdet::Utils::update_attrs(
		$current, $entry, qw( ou
		  userPassword searchGuide seeAlso businessCategory
		  x121Address registeredAddress destinationIndicator
		  preferredDeliveryMethod  telexNumber  teletexTerminalIdentifier
		  telephoneNumber internationaliSDNNumber
		  facsimileTelephoneNumber  street  postOfficeBox  postalCode
		  postalAddress  physicalDeliveryOfficeName  st  l  description )
	);
}

1;
