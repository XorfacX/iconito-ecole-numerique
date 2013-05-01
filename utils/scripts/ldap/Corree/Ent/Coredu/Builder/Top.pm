package Corree::Ent::Coredu::Builder::Top;

use v5.10;
use strict;
use warnings;

sub new {
	my $this  = shift;
	my $class = ref($this) || $this;
	my $self  = {};
	bless $self, $class;

	$self->_initialize( @_ );
	
	return $self;
}

sub _initialize {
	my $self = shift;
	
	$self->sdet( shift );
	$self->key( shift );

}

sub key {
	my ( $self, $key ) = @_;

	if ( defined $key ) {
		$self->{'key'} = $key;
	}

	return $self->{'key'};
}

sub sdet {
	my ( $self, $sdet ) = @_;

	if ( defined $sdet ) {
		$self->{'sdet'} = $sdet;
	}

	return $self->{'sdet'};
}

sub jointure  {
	my ( $self, $key ) = @_;
	
	return join('$', $self->key, $key);
	
}
1;
