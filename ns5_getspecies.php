<?php

$seq = "GGSEGDTLGDLWKRKLNGCTKEEFFAYRRTGILETERDKARELLRRGETNMGLAVSRGTAKLAWLEERGYATLKGEVVDLGCGRGGWSYYAASRPAVMSVKAYTIGGKGHETPKMVTSLGWNLIKFRAGMDVFSMQPHRADTIMCDIGESNPDAVVEGERTRKVILLMEQWKNRNPTATCVFKVLAPYRPEVIEALHRFQLQWGGGLVRTPFSRNSTHEMYYSTAVTGNIVNSVNIQSRKLLARFGDQRGPTRVPELDLGVGTRCVVLAEDKVKEKDVQERISALREQYGETWHMDREHPYRTWQYWGSYRTAPTGSAASLINGVVKLLSWPWNAREDVVRMAMTDTTAFGQQRVFKEKVDTKAQEPQPGTKVIMRAVNDWILERLARKSKPRMCSREEFIAKVKSNAALGAWSDEQNRWSSAKEAVEDPAFWQLVDEERERHLAGRCAHCVYNMMGKREKKLGEFGVAKGSRAIWYMWLGSRFLEFEALGFLNEDHWASRGSSGSGVEGISLNYLGWHLKGLSTLEGGLFYADDTAGWDTKVTNADLEDEEQLLRYMEGEHKQLAATIMQKAYHAKVVKVARPSRDGGCIMDVITRRDQRGSGQVVTYALNTLTNIKVQLIRMMEGEGVIEASDAHNPRLLRVERWLRDHGEERLGRMLVSGDDCVVRPVDDRFSRALYFLNDMAKTRKDIGEWEHSVGFSNWEEVPFCSHHFHELVMKDGRALIVPCRDQDELVGRARVSPGCGWSVRETACLSKAYGQMWLLSYFHRRDLRTLGLAICSAVPVDWVPTGRTTWSIHASGSWMTTEDMLDVWNRVWILDNPFMHSKEKIAEWRDVPYLPKSHDMLCSSLVGRKERAEWAKNIWGAVEKVRKMIGQEKFKDYLSCMDRHDLHWELKLESSII";
$qlen = strlen( $seq );
$dbfile = "/opt/base/seq1117/nrvirus.fa";
$res = file( "ns5align.txt" );
$rv = "";
$esize = 100;
$species = array();
$spcount = array();
foreach ( $res as $rstr )
{
	if ( strlen( $rstr ) == 0 ) continue;
	$cres = explode( "\t", $rstr );
	$fs = sizeof( $cres ) + 1;
	$id = substr( $cres[0], 0, strcspn( $cres[0], " " ) );
	$alength = $cres[ $fs - 3 ] - $cres[ $fs - 4 ];
	if ( $alength < 800 ) continue;
	$descr = $cres[0];
	$pbeg = strpos( $descr, "[" );
	$pend = strpos( $descr, "]" );
	if ( $pbeg === false || $pend === false || $pend < $pbeg ) continue;
	$cspec = substr( $descr, $pbeg + 1, $pend - $pbeg - 1 );
	if ( in_array( $cspec, $species ) ) 
	{
		$spcount[ $cspec ]++;
		continue;
	}
	$spcount[ $cspec ] = 1;
	$cseqres = array();
	$cseq = exec( "blastdbcmd -db $dbfile -entry $id -outfmt \"%s\n%t\n\"", $cseqres );
	if ( sizeof( $cseqres ) < 2 ) continue;
	$title = $cseqres[ 1 ];
	$cseq = $cseqres[0];
	//$cseqres[ 0 ];
	//echo $cseq;
	if ( $esize != "complete" )
	{
		$sbeg = max( 0, $cres[ $fs - 6 ] - $cres[ $fs - 4 ] - $esize );
		$send = min( strlen( $cseq ), $cres[ $fs - 5 ] + ( $qlen - $cres[ $fs - 3 ] ) + $esize );
		$cseq = substr( $cseq, $sbeg, $send - $sbeg );
	}
	echo ">$id [$cspec]\n$cseq\n";
	$species[] = $cspec;
}
?>