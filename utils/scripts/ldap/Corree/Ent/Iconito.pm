package Corree::Ent::Iconito;

use strict;
use warnings;

use v5.10;

use Log::Message::Simple qw[msg error debug carp croak cluck confess];

use DBI;

use Corree::Ent::Iconito::Handler::Personnel;
use Corree::Ent::Iconito::Handler::Eleve;
use Corree::Ent::Iconito::Handler::Classe;
use Corree::Ent::Iconito::Handler::Ecole;


BEGIN {
    use vars qw[ $DEBUG_LEVEL ];

    local $| = 1;
    $DEBUG_LEVEL            = 1;
}

sub new {
	my $this  = shift;
	my $class = ref($this) || $this;
	my $self = {};
	bless $self, $class;

	my $args = shift;

	$self->{'dbh'} = $self->_dbh( $args->{'dsn'}, $args->{'user'}, $args->{'password'} );

	return $self;
}

sub _dbh {
	my $self = shift;

	my ($dsn, $user, $password) = @_;

	my $dbh = DBI->connect($dsn, $user, $password, {RaiseError => 1});
	
	croak "An error occurred connecting to the database server: " . $DBI::errstr if not $dbh;

	return $dbh;
}

sub sync {
	my ($self, $sdet, $key, $year ) = @_;

	debug 'traitement des personnels', $DEBUG_LEVEL;

	my $personnels = Corree::Ent::Iconito::Handler::Personnel->new( $self->{'dbh'}, $key );

	$personnels->main_loop( $sdet );

	debug 'traitement des écoles', $DEBUG_LEVEL;

	my $ecoles = Corree::Ent::Iconito::Handler::Ecole->new( $self->{'dbh'}, $key );

	$ecoles->main_loop( $sdet );
	
	debug 'rattachement des personnels aux écoles', $DEBUG_LEVEL;

	$personnels->struct_rattach( $sdet );

	debug 'traitement des classes', $DEBUG_LEVEL;

	my $classes = Corree::Ent::Iconito::Handler::Classe->new( $self->{'dbh'}, $key );
	
	$classes->main_loop( $sdet, $year );
	
	debug 'affectation des classes aux personnels', $DEBUG_LEVEL;	

	$personnels->classes( $sdet );

	debug 'traitement des élèves', $DEBUG_LEVEL;

	my $eleves = Corree::Ent::Iconito::Handler::Eleve->new( $self->{'dbh'}, $key );
	
	$eleves->main_loop( $sdet, ( $year ) );

	debug 'affectation des élèves aux classes', $DEBUG_LEVEL;

	$classes->eleves( $sdet, $year );
}

sub close {

	my $self = shift;

	if (defined $self->{'dbh'} ) {
		$self->{'dbh'}->close;
	}
}
1;
