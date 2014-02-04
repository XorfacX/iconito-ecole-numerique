package Corree::Sdet::Handler::ENTEleve;

use strict;
use warnings;

use parent qw( Corree::Sdet::Handler::ENTPerson );

use Corree::Sdet::Utils;

sub search_filter {
	my ( $self, $jointure ) = @_;

	return "(&(objectClass=ENTEleve)(ENTPersonJointure=$jointure))";
}

sub update_entry {
	my $self = shift;
	
	my ( $current, $entry ) = @_;

	return $self->SUPER::update_entry(@_) + Corree::Sdet::Utils::update_attrs(
		$current, $entry, qw( ENTEleveStatutEleve
		  ENTEleveMEF
		  ENTEleveLibelleMEF
		  ENTEleveNivFormation
		  ENTEleveFiliere
		  ENTEleveClasses
		  ENTEleveMajeur
		  ENTEleveVilleNaissance
		  ENTEleveDeptNaissance
		  ENTElevePaysNaissance
		  ENTElevePere
		  ENTEleveMere
		  ENTEleveEnseignements
		  ENTEleveAutoriteParentale
		  ENTElevePersRelEleve1
		  ENTEleveQualitePersRelEleve1
		  ENTElevePersRelEleve2
		  ENTEleveQualitePersRelEleve2
		  ENTEleveBoursier
		  ENTEleveRegime
		  ENTEleveTransport
		  ENTEleveMEFRattach
		  ENTEleveNivFormationDiplome
		  ENTEleveSpecialite
		  ENTEleveGroupes
		  ENTEleveEnsRespStage
		  ENTEleveEnsTutStage
		  ENTEleveEntrTutStage
		  ENTEleveEntrAutres
		  ENTEleveDelegClasse
		  ENTEleveDelegAutres
		  ENTEleveMajeurAnticipe  )
	);
}

1;
