package Corree::Sdet::Handler::Top;

use v5.10;

use strict;
use warnings;

use Log::Message::Simple qw[msg error debug carp croak cluck confess];

use Corree::Sdet::Utils;

use Net::LDAP::Constant qw(LDAP_NO_SUCH_OBJECT);

use vars qw($DEBUG $MSG $ERROR);

$DEBUG = $MSG  = $ERROR = 1;

sub new {
	my $this  = shift;
	my $class = ref($this) || $this;
	my $self  = {};
	bless $self, $class;
	$self->{'handle'} = shift;
	
	return $self;
}

sub parent {
	my $self = shift;

	return $self->{'handle'}->dn;
}

sub dn {
	my ( $self, $entry ) = @_;

	if ( defined $entry ) {
		my @rdns;

		foreach my $attr ( @{ $self->dn_attributes } ) {
			my $value = $entry->get_value($attr);

			if ($value) {
				push( @rdns, join( '=', $attr, $value ) );
			}
		}

		if ( $self->parent() ) {
			push( @rdns, $self->parent() );
		}
		return join( ',', @rdns );
	}

	return $self->parent;
}

sub dn_attributes {
	return [];
}

sub ldap {
	my $self = shift;

	return $self->{'handle'}->ldap;
}

sub search_filter {
	die "Not Implemented";
}

sub _extract_jointure {
	my ( $self, $entry ) = @_;

	return $entry->get_value('ENTPersonJointure');
}

sub search {
	my ( $self, $jointure ) = @_;

	my $mesg = $self->ldap->search(
		base      => $self->dn,
		sizelimit => 1,
		filter    => $self->search_filter($jointure)
	);

	if ( $mesg->code and $mesg->code != LDAP_NO_SUCH_OBJECT ) {
		error "An error occurred during search: " . $mesg->error, $ERROR;
		
		return undef;
	}	  

	if ( $mesg->count() > 1 ) {
		error "Size limit execeeded", $ERROR;
		
		return undef;
	} 

	return $mesg->pop_entry();
}

sub update_entry {
	my ($self, $current, $entry ) = @_;
	
	$entry->dn( $current->dn );
	
	return 0;
}

sub _update {
	my ( $self, $entry ) = @_;

	my $jointure = $self->_extract_jointure($entry);

	if ($jointure) {
		my $current = $self->search($jointure);

		if ($current) {
			debug "correspondance trouvée pour " . $current->dn(), $DEBUG;

			if ( $self->update_entry( $current, $entry ) ) {

				#$current->changetype( 'modify' );

				my $mesg = $current->update( $self->ldap );

				if ( $mesg->code ) {
					error "An error occurred while updating entry: " . $mesg->error, $ERROR;
				}
				else {
					msg "mise àjour de " . $current->dn(), $MSG;
				}
			}
			else {
				debug "aucune mise � jour.", $DEBUG;
			}
		}
		else {
			$entry->dn( $self->dn($entry) );

			my $mesg = $self->ldap->add($entry);

			if ( $mesg->code ) {
				error "An error occurred while adding entry: " . $mesg->error, $ERROR;
			}
			else {
				msg "création d'une nouvelle entrée : " . $entry->dn(), $MSG;
			}
		}
	}
}

1;
