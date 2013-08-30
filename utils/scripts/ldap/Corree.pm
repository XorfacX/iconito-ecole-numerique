package Corree;

use v5.10;
use strict;
use warnings;

use Log::Message::Simple qw[msg error debug carp croak cluck confess];

use Net::LDAP;

sub new {
	my $this  = shift;
	my $class = ref($this) || $this;
	my $self  = {};
	bless $self, $class;

	my $args = shift;

}

sub parse_dsn {
	my $dsn = shift;

	my ( $scheme, $host, $base );

	if ( $dsn =~ m{^ldap(s|i)?://(.*)/(.*)$} ) {
		$scheme = "ldap";

		if ($1) {
			$scheme .= $1;
		}

		$host = $2;

		$base = $3;
	}

	return ( $scheme, $host, $base );
}

sub base_dsn {
	my $dsn = shift;

	my ( $scheme, $host, $base ) = parse_dsn($dsn);

	return $base;
}

sub ldap_dsn {
	my $dsn = shift;

	my ( $scheme, $host, $base ) = parse_dsn($dsn);
	my $ldap = Net::LDAP->new( $host, scheme => $scheme, async => 1 )
	  or croak "$@";

	return $ldap;
}

sub sync {
	my $self = shift;

	my ( $class, $dsn, $user, $password, $o, $year ) = shift;

	my $organization_name = $o->get_value('o');

	return $class->new( { dsn => $dsn, user => $user, password => $password } )
	  ->sync( $self->sdet, $o, $year );
}

1;

