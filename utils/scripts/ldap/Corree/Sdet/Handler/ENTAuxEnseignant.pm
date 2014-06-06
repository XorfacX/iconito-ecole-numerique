package Corree::Sdet::Handler::ENTAuxEnseignant;

use strict;
use warnings;

use parent qw( Corree::Sdet::Handler::ENTPerson );

use Corree::Sdet::Utils;

use vars qw(@ATTRIBUTES);

sub search_filter {
	my ( $self, $jointure ) = @_;

	return "(&(objectClass=ENTAuxEnseignant)(ENTPersonJointure=$jointure))";
}

@ATTRIBUTES = qw( ENTAuxsEnsMEF
                 ENTAuxEnsDisciplinesPoste
                 ENTAuxEnsMatiereEnseignEtab
         ENTAuxEnsClasses
         ENTAuxEnsGroupes
         ENTAuxEnsClassePrincipal
         ENTAuxEnsRespStage
         ENTAuxEnsTutStage );


sub update_entry {
	my $self = shift;
	
	my ( $current, $entry, @attributes_to_keep ) = @_;

	my %attributes_to_keep = map{$_=>1} @attributes_to_keep;

	return $self->SUPER::update_entry(@_) + Corree::Sdet::Utils::update_attrs(
		$current, $entry, grep(! defined $attributes_to_keep{$_}, @ATTRIBUTES )  
	);
}

1;
