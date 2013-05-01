package Corree::Ent::Coredu::Builder::ENTPerson;

use v5.10;
use strict;
use warnings;

use parent qw(Corree::Ent::Coredu::Builder::Top);

use Log::Message::Simple qw[msg error debug];

sub uid {
	my $entry = shift;

	my $empId = $entry->get_value("supannEmpId");

	if ( !$empId ) {

		msg "supannEmpId not defined for " . $entry->dn(), 1;

		return undef;
	}

	# Left padding a number with 0 (no truncation):
	my $padded = sprintf( "%04d", $empId );

	return "Ued$padded";
}


sub _initialize {
	my $self = shift;
	$self->SUPER::_initialize( @_ );

	my %admins = map {$_ => 1 } @_;
	
	$self->{'admins'} = \%admins;
}

sub build {
	my ( $self, $entry ) = @_;

	my $person = Net::LDAP::Entry->new;

	my $uid = uid($entry);

	return undef unless defined $uid;

	my $empId = $entry->get_value("supannEmpId");

	my $jointure = $self->key . '$' . $empId;

	my $cn = $entry->get_value("cn");

	my $sn = $entry->get_value("sn");

	my $login = $entry->get_value("uid");
	
	$login =~ /(\w+)@/;
	
	my $givenName = $entry->get_value("givenName");

	if ( !$givenName ) {
		msg "givenName missing in " . $entry->dn(), 1;

		return undef;
	}

	my $displayName = $entry->get_value("displayName") || $cn;

	$person->add(
		objectClass       => [qw(ENTPerson ENTAuxRespEntrp)],
		cn                => $cn,
		sn                => $sn,
		displayName       => $displayName,
		givenName         => $givenName,
		uid               => $uid,
		ENTPersonJointure => $jointure,
		ENTPersonLogin    => $login,
		ENTPersonProfils  => [ $self->sdet->get_role('ROLE_EDITEUR') ],
		#ENTPersonStructRattach => $entrp_dn,
		#ENTAuxRespEntrpSociete => $entrp_dn,
	);

	if ( $entry->get_value("telephoneNumber") ) {
		$person->add( telephoneNumber => $entry->get_value("telephoneNumber") );
	}

	if ( $entry->get_value("postalCode") ) {
		$person->add( postalCode => $entry->get_value("postalCode") );
	}

	if ( $entry->get_value("postalAddress") ) {
		$person->add( street => join("\n", $entry->get_value("postalAddress") ) );
	}

	if ( $entry->get_value("l") ) {
		$person->add( l => $entry->get_value("l") );
	}

	if ( $entry->get_value("mail") ) {
		$person->add( mail => $entry->get_value("mail") );
	}

	if ( exists($self->{'admins'}{$entry->dn}) ) {
		$person->add( ENTPersonProfils => $self->sdet->get_role('ROLE_ADMINISTRATEUR') );
	}

	return $person;
}

1;
