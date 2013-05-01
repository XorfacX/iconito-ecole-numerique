package Corree::Sdet::Handler::ENTPerson;

use strict;
use warnings;

use parent qw( Corree::Sdet::Handler::InetOrgPerson );

use Corree::Sdet::Utils;

sub initialize {
	my $self = shift;

	$self->SUPER::_initialize(@_);

	$self->{'jointure'} = "ENTPersonJointure";
}

sub search_filter {
	my ( $self, $jointure ) = @_;

	return "(&(objectClass=ENTPerson)(ENTPersonJointure=$jointure))";
}


sub parent {
	my $self = shift;
	
	my $rdn  = join('=', 'ou', 'personnes');
	
	return join(',', $rdn, $self->SUPER::parent() );
}

sub dn_attributes {
	return [qw(uid)];
}

sub update_entry {
	my $self = shift;

	my ( $current, $entry ) = @_;

	# ne pas modifier ENTPersonJointure
	return $self->SUPER::update_entry(@_) + Corree::Sdet::Utils::update_attrs(
		$current, $entry,
		qw( ENTPersonLogin ENTPersonAutresPrenom
		  ENTPersonNomPatro
		  ENTPersonSexe
		  ENTPersonCentresInteret
		  ENTPersonAdresse
		  ENTPersonCodePostal
		  ENTPersonVille
		  ENTPersonPays
		  ENTPersonAlias
		  ENTPersonStructRattach
		  ENTPersonFonctions
		  ENTPersonProfils
		  ENTPersonDateNaissance
		  personalTitle
		  )
	);
}

sub _extract_jointure {
	my ( $self, $entry ) = @_;

	return $entry->get_value('ENTPersonJointure');
}

1;
