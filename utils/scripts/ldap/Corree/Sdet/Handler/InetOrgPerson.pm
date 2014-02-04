package Corree::Sdet::Handler::InetOrgPerson;

use strict;
use warnings;

use parent qw( Corree::Sdet::Handler::OrganizationalPerson );

use Corree::Sdet::Utils;

sub update_entry {
	my $self = shift;

	my ( $current, $entry ) = @_;

	# ne pas modifier qw(uid)
	return $self->SUPER::update_entry(@_) + Corree::Sdet::Utils::update_attrs(
		$current, $entry,
		qw(audio businessCategory carLicense departmentNumber
		  displayName employeeNumber  employeeType  givenName
		  homePhone  homePostalAddress  initials  jpegPhoto
		  labeledURI  mail  manager  mobile  o  pager
		  photo  roomNumber  secretary    userCertificate
		  x500uniqueIdentifier  preferredLanguage
		  userSMIMECertificate  userPKCS12 )
	);
}
1;
