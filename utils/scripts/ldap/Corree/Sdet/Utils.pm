package Corree::Sdet::Utils;

use v5.10;

use strict;
use warnings;
use Log::Message::Simple qw[msg error debug carp croak cluck confess];

use vars qw( $DEBUG );

$DEBUG = 1;

sub update_attr {
	my $count_modified = 0;
	my ( $current, $entry, $attr ) = @_;

	my $current_values = $current->get_value( lc($attr), asref => 1 );

	my $new_values = $entry->get_value( lc($attr), asref => 1 );

	if ( !defined $current_values ) {
		if ( defined $new_values ) {
			debug $attr . ' >> ' . join( ', ', @$new_values ), $DEBUG > 9;

			$current->add( $attr => $new_values );

			$count_modified += scalar( grep { defined $_ } @$new_values );
		}
	}
	elsif ( !defined $new_values ) {
		debug $attr . ' << ' . join( ', ', @$current_values ), $DEBUG > 9;

		$current->delete( $attr => [] );

		$count_modified += scalar( grep { defined $_ } @$current_values );
	}
	else {
		my %current_values = map { $_ => 1 } @$current_values;
		my %new_values     = map { $_ => 1 } @$new_values;

		my @values_to_delete = grep( !defined $new_values{$_}, @$current_values );
		my $count_to_delete = scalar( grep { defined $_ } @values_to_delete );

		if ( $count_to_delete > 0 ) {
			# remove missing values in new entry
			foreach my $value (@values_to_delete) {
				debug $attr . ' < ' . $value, $DEBUG > 9;
			}

			$current->delete( $attr => @values_to_delete );
			$count_modified += $count_to_delete;
		}

		my @values_to_add = grep( !defined $current_values{$_}, @$new_values );
		my $count_to_add = scalar( grep { defined $_ } @values_to_add );

		if ( $count_to_add > 0 ) {
			# add missing values from new entry
			foreach my $value (@values_to_add) {
				debug $attr . ' > ' . $value, $DEBUG > 9;
			}
			$current->add( $attr => @values_to_add );
			$count_modified += $count_to_add;
		}
	}

	return $count_modified;
}

sub update_attrs {
	my $count_modified = 0;
	my ( $current, $entry, @attrs ) = @_;

	foreach my $attr (@attrs) {
		$count_modified += update_attr( $current, $entry, $attr );
	}

	return $count_modified;
}

1;
