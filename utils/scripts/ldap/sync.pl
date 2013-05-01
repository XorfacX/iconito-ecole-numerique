#!/usr/bin/perl -w

use v5.10;
use strict;
use warnings;

use Log::Message::Simple qw[msg error debug carp croak cluck confess];

use Module::Load;
use Getopt::Std;
use Net::LDAP;
use File::Spec::Functions;
use FileHandle;
use File::Path qw(make_path);
use DateTime::Format::ISO8601;
use Try::Tiny;

use Corree::Sdet;
use Corree::Ent::Iconito;

use constant MAND_OPTS => qw(H b D w y);

use constant OTHER_OPTS => qw(d L o f);

use constant {
	VERSION_ATTRIBUTE => 'gidNumber',
	DSN_ATTRIBUTE     => 'labeledUri',
	MODULE_ATTRIBUTE  => 'description',
	USER_ATTRIBUTE    => 'uid',
	PWD_ATTRIBUTE     => 'userPassword',
	MIN_ATTRIBUTE     => 'shadowMin',
	MAX_ATTRIBUTE     => 'shadowMax',
};

my %opts;

my $usage = <<USAGE
Usage: syn.pl -H URI -b basedn -D binddn -w passwd -L logdir -d level -o organizationName -y year -f
USAGE
  ;

if ( !getopts( join( ':', (OTHER_OPTS), (MAND_OPTS) ) . ':', \%opts ) ) {
	die "unknow option\n" . $usage;
}

if ( scalar( grep { !defined $opts{$_} } (MAND_OPTS) ) ) {
	die "missing option\n" . $usage;
}

my $log_dir;

if ( defined $opts{'L'} ) {
	$log_dir = $opts{'L'};
	make_path($log_dir);
}

my $corree = Net::LDAP->new( $opts{'H'} );

my $mesg = $corree->bind(
	dn       => $opts{'D'},
	password => $opts{'w'},
);

die "An error occurred binding to the LDAP server: " . $mesg->error . "\n"
  if $mesg->code;


my $filter = "(objectClass=organization)";

if ( $opts{'o'} ) {
	$filter="(&$filter(o=". $opts{'o'} . "))";
}

$mesg = $corree->search(
	base   => $opts{'b'},
	scope  => 'one',
	filter => $filter,
	attrs  => [ '*', 'modifyTimestamp' ]
);

croak 'An error occurred during search : ' . $mesg->error if $mesg->is_error;

while ( my $entry = $mesg->pop_entry ) {
	my $o = $entry->get_value('o');

	my $module = get_attr( $entry, MODULE_ATTRIBUTE, $o ) or next;

	my $min = get_attr( $entry, MIN_ATTRIBUTE, $o );

	next if not defined $min;

	my $ts = get_attr( $entry, "modifyTimestamp", $o );
	
	# HACK
	substr($ts, 8, 0) = 'T';
	
	my $dt = DateTime::Format::ISO8601->parse_datetime( $ts );

	if ( time - $dt->epoch > $min or $opts{'f'} ) {
		my $msg_fh = log_h( "$o.log", $log_dir );
		my $err_fh = log_h( "$o.err", $log_dir );
		my $dbg_fh = log_h( "$o.out", $log_dir );

		local $Log::Message::Simple::MSG_FH   = $msg_fh;
		local $Log::Message::Simple::ERROR_FH = $err_fh;
		local $Log::Message::Simple::DEBUG_FH = $dbg_fh;

		my $dsn = get_attr( $entry, DSN_ATTRIBUTE, $o ) or next;

		my $user = get_attr( $entry, USER_ATTRIBUTE, $o ) or next;

		my $password = get_attr( $entry, PWD_ATTRIBUTE, $o );

		next if not defined $password;

		my $sdet = Corree::Sdet->new( $corree, $entry->dn );

		$sdet->prepare();

		my $class = 'Corree::Ent::' . $module;

		try {
			load $class;
		}
		catch {
			carp $_;
		};

		try {
			my $res = $class->new(
				{ dsn => $dsn, user => $user, password => $password } )
			  ->sync( $sdet, $o, $opts{'y'} );
		}
		catch {
			carp "erreur: $_";
		};

		my $version = $entry->get_value(VERSION_ATTRIBUTE);

		if ( not defined $version ) {
			$entry->add( (VERSION_ATTRIBUTE) => 1 );
		}
		else {
			$entry->replace( (VERSION_ATTRIBUTE) => ++$version );
		}

		my $mesg = $entry->update($corree);

		croak 'An error occurred during search : ' . $mesg->error
		  if $mesg->is_error;

		$msg_fh->close;
		$err_fh->close;
		$dbg_fh->close;
	}
}

$corree->unbind;

exit(0);

sub get_attr {
	my ( $entry, $attr, $o ) = @_;

	my $value = $entry->get_value($attr);

	if ( !defined $value ) {
		error "missing " . $attr . " attribute for $o", 1;
	}
	return $value;
}

sub log_h {
	my ( $filename, $dir ) = @_;

	if ( defined $log_dir ) {
		return FileHandle->new( catfile( $dir, $filename ), "w+" );
	}
	else {
		return \*STDERR;
	}
}
