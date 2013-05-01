package Corree::Ent::Coredu;

use v5.10;
use strict;
use warnings;

use Log::Message::Simple qw[msg error debug carp croak cluck confess];
use Net::LDAP;
use Net::LDAP::Control::Paged;
use Net::LDAP::Constant qw(LDAP_CONTROL_PAGED LDAP_NO_SUCH_OBJECT);

use Corree qw(ldap_dsn base_dsn);

use Corree::Sdet;

use Corree::Ent::Coredu::Builder::ENTPerson;
use Corree::Ent::Coredu::Builder::ENTEntreprise;

use vars qw( $DEBUG );

$DEBUG  = 1;

sub new {
	my $this  = shift;
	my $class = ref($this) || $this;
	my $self  = {};
	bless $self, $class;

	my $args = shift;

	my $dsn = $args->{'dsn'};

	my $ldap = Corree::ldap_dsn( $dsn ) or croak "$@";

	my $mesg = $ldap->bind(dn => $args->{'user'}, password => $args->{'password'} );

	croak  "An error occurred binding to the LDAP server: " . $mesg->error if $mesg->code;

	$self->ldap( $ldap );

	$self->base( Corree::base_dsn( $dsn ) );

	return $self;
}

my $ldap_sizelimit = 100;

my $cookie;

sub ldap {
	my ( $self, $ldap ) = @_;

	if ( defined $ldap ) {
		$self->{'ldap'} = $ldap;
	}

	return $self->{'ldap'};
}

sub base {
	my ( $self, $base ) = @_;

	if ( defined $base ) {
		$self->{'base'} = $base;
	}

	return $self->{'base'};
}

sub sync {
	my ( $self, $sdet, $key ) = @_;
	
	my $page = Net::LDAP::Control::Paged->new( size => $ldap_sizelimit );

	# recherche dans coredu
	my @args = (
		base    => "ou=personnels," . $self->base,
		scope   => "subtree",
		filter  => "(objectClass=supannPerson)",
		control => [$page],
	);

	my @admins = $self->group_members( "superviseurs" );
	
	my $person_builder =
	  Corree::Ent::Coredu::Builder::ENTPerson->new( $sdet, $key, @admins );

	my $entreprise_builder =
	  Corree::Ent::Coredu::Builder::ENTEntreprise->new( $sdet, $key );

	while (1) {

		# Perform search
		my $sr = $self->ldap->search(@args);

		# Only continue on LDAP_SUCCESS
		$sr->code and last;

		my @entries = $sr->entries;

		foreach my $entry (@entries) {
			my $person = $person_builder->build($entry);

			if ($person) {
				$sdet->update($person);

				my $entreprise = $entreprise_builder->build($entry, $person);

				if ($entreprise) {
					$sdet->update($entreprise);
					
					$person->add( #ENTAuxRespEntrpSociete => $entreprise->dn,
						 ENTPersonStructRattach => $entreprise->dn );
					
					$sdet->update($person);
				}
			}
		}

		# Get cookie from paged control
		my ($resp) = $sr->control(LDAP_CONTROL_PAGED) or last;
		$cookie = $resp->cookie or last;

		# Set cookie in paged control
		$page->cookie($cookie);
	}

	if ($cookie) {

	   # We had an abnormal exit, so let the server know we do not want any more
		$page->cookie($cookie);
		$page->size(0);
		$self->ldap->search(@args);
	}
}

sub group_members {
	my $self = shift;
	my $cn = shift;

	# recherche dans coredu
	my @args = (
		base    => "ou=groupes," . $self->base,
		scope   => "one",
		filter  => "(&(cn=$cn)(objectClass=groupOfUniqueNames))",
	);

	# Perform search
	my $sr = $self->ldap->search(@args);

	# Only continue on LDAP_SUCCESS
	$sr->code and return;
	
	my $entry = $sr->shift_entry or return;

	return $entry->get_value('uniqueMember');
}

1;
