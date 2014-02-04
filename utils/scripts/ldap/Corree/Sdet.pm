package Corree::Sdet;

use v5.10;

use strict;
use warnings;
use Log::Message::Simple qw[msg error debug carp croak cluck confess];

use Module::Load;
use Net::LDAP::Constant qw(LDAP_NO_SUCH_OBJECT);

use Net::LDAP::Util qw(ldap_explode_dn canonical_dn);
use vars qw( $DEBUG );

$DEBUG = 1;

sub new {
	my $this  = shift;
	my $class = ref($this) || $this;
	my $self  = {};
	bless $self, $class;

	$self->_initialize(@_);


	$self->__get_roles();
	return $self;
}

sub creator {
	my $self  = shift;
	my $class = 'Corree::Sdet::Handler::' . shift;

	load $class;

	$class->new($self);
}

sub _initialize {
	my $self = shift;

	$self->ldap(shift);
	$self->base(shift);
	
	$self->{'roles'} = {};
}

sub ldap {
	my ( $self, $ldap ) = @_;

	if ( defined $ldap ) {
		$self->{'ldap'} = $ldap;
	}

	return $self->{'ldap'};
}

sub base {
	my ( $self, $dn ) = @_;

	if ( defined $dn ) {
		$self->{'base'} = $dn;
	}

	return $self->{'base'};
}

sub dn {
	my $self = shift;

	return $self->base;
}

sub _create_ou {
	my ( $self, $ou ) = @_;

	my $dn = join( ',', join( '=', 'ou', $ou ), $self->dn() );

	my $mesg = $self->ldap->search(
		base      => $dn,
		scope     => 'base',
		sizelimit => 1,
		filter    => '(objectClass=organizationalUnit)',
		attr      => [],
	);

	if ( $mesg->is_error ) {
		croak " An error occurred during search : " . $mesg->error
		  if $mesg->code != LDAP_NO_SUCH_OBJECT;

		my $entry = Net::LDAP::Entry->new;

		$entry->dn($dn);

		$entry->add(
			objectClass => [qw(organizationalUnit)],
			ou          => [$ou]
		);

		$mesg = $self->ldap->add($entry);
		croak " An error occurred while adding entry :" . $mesg->error
			if $mesg->code;
	}
}

sub prepare {
	my $self = shift;

	$self->_create_ou('groupes');
	$self->_create_ou('personnes');
	$self->_create_ou('structures');
}

sub update {
	my $self  = shift;
	my $entry = shift;

	my $objectClass = get_objectClass($entry);

	

	if ( defined $objectClass ) {
		my $handler = $self->creator($objectClass);

		if ($handler) {
			$handler->_update($entry);
		}
	}
}

sub get_objectClass {
	my $entry = shift;

	my %oc = map { $_ => 1 } $entry->get_value('objectClass');
	
	my $retval;

	if ( exists $oc{'ENTAuxEnseignant'} ) {
		$retval = 'ENTAuxEnseignant';
	}
	elsif ( exists $oc{'ENTAuxNonEnsEtab'} ) {
		$retval = 'ENTAuxNonEnsEtab';
	}
	elsif ( exists $oc{'ENTEleve'} ) {
		$retval = 'ENTEleve';
	}
	elsif ( exists $oc{'ENTAuxRespEntrp'} ) {
		$retval = 'ENTAuxRespEntrp';
	}
	elsif ( exists $oc{'ENTClasse'} ) {
		$retval = 'ENTClasse';
	}
	elsif ( exists $oc{'ENTGroupe'} ) {
		$retval = 'ENTGroupe';
	}
	elsif ( exists $oc{'ENTEntreprise'} ) {
		$retval = 'ENTEntreprise';
	}
	elsif ( exists $oc{'ENTEtablissement'} ) {
		$retval = 'ENTEtablissement';
	}
	elsif ( exists $oc{'ENTAuxPersExt'} ) {
		$retval = 'ENTAuxPersExt';
	}

	debug "objectClass: $retval", $DEBUG > 9;
	
	return $retval;
}

sub __get_roles {
	my ( $self, $role ) = @_;


	my $rdn = ldap_explode_dn( $self->base );

	shift @$rdn;
	unshift @$rdn, { 'ou', 'groupes' };

	my $dn = canonical_dn( $rdn );

	debug "role search dn : " . $dn, $DEBUG;

	my $mesg = $self->ldap->search(
		base      => $dn,
		scope     => 'one',
		filter    => '(objectClass=ENTRoleAppli)',
		attr      => [qw(cn)],
	);
	
	croak 'An error occurred during search : ' . $mesg->error  if $mesg->is_error;
	
    	while( my $entry = $mesg->pop_entry ) {
    	my $cn = $entry->get_value('cn');
    	
    	$self->{'roles'}->{ $cn } = $entry;    	
	debug "role $cn ajouté", $DEBUG;
    }
}


sub get_role {
	my ( $self, $role ) = @_;

	return $self->{'roles'}->{$role}->dn;
}

1;
