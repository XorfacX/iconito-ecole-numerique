package Corree::Ent::Iconito::Handler;

use v5.10;
use strict;
use warnings;

use Try::Tiny;

use parent qw(Corree::Ent::Iconito);

use Log::Message::Simple qw[msg error debug carp croak cluck confess];
use Text::Iconv;

my $converter = Text::Iconv->new( "ISO-8859-1", "UTF-8" );

sub new {
	my $this  = shift;
	my $class = ref($this) || $this;
	my $self  = {};
	bless $self, $class;

	$self->initialize(@_);

	return $self;
}

sub clone {
	my $this  = shift;
	my $class = ref($this) || $this;
	my $self  = {};
	bless $self, $class;

	my $clone = shift;

	$self->initialize( $clone->db, $clone->key );

	return $self;
}

sub initialize {
	my ( $self, $dbh, $key ) = @_;

	$self->db($dbh);

	$self->key($key);

	$self->{'_queries'} = {};
}

sub query {
	my ( $self, $name, $query ) = @_;

	if ( defined $query ) {
		$self->{'_queries'}->{$name} = $self->db->prepare($query);
	}

	return $self->{'_queries'}->{$name};
}

# getter/setter for db
sub db {
	my ( $self, $dbh ) = @_;

	if ( defined $dbh ) {
		$self->{'dbh'} = $dbh;
	}

	return $self->{'dbh'};
}

sub key {
	my ( $self, $key ) = @_;

	if ( defined $key ) {
		$self->{'key'} = $key;
	}

	return $self->{'key'};
}

sub jointure {
	my ( $self, $key ) = @_;

	return join( '$', $self->key, $key );
}

sub main_loop {
	my ( $self, $sdet, @params ) = @_;

	my $query = $self->query('main');

	if (@params) {
		debug @params, 1;
	}
	$query->execute( @params );

MAIN:
	while ( my @data = $query->fetchrow_array() ) {
		try {
			my $entry = $self->build_entry( $sdet, _trimArray(@data) );

			if ($entry) {
				$sdet->update($entry);
			}
		}
		catch {
			error "error while processing entry " . $_, 1;
		};	
	}
}

sub _trimArray {
	my @arr = @_;
	my @rv;
	for my $val (@arr) {
		if ($val) {
			$val =~ s/^\s+//;
			$val =~ s/\s+$//;

			$val = $converter->convert($val);
		}
		push @rv, $val;
	}
	return @rv;
}
1;
