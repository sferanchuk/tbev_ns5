<?php

$seq = "GGSEGDTLGDLWKRKLNGCTKEEFFAYRRTGILETERDKARELLRRGETNMGLAVSRGTAKLAWLEERGYATLKGEVVDLGCGRGGWSYYAASRPAVMSVKAYTIGGKGHETPKMVTSLGWNLIKFRAGMDVFSMQPHRADTIMCDIGESNPDAVVEGERTRKVILLMEQWKNRNPTATCVFKVLAPYRPEVIEALHRFQLQWGGGLVRTPFSRNSTHEMYYSTAVTGNIVNSVNIQSRKLLARFGDQRGPTRVPELDLGVGTRCVVLAEDKVKEKDVQERISALREQYGETWHMDREHPYRTWQYWGSYRTAPTGSAASLINGVVKLLSWPWNAREDVVRMAMTDTTAFGQQRVFKEKVDTKAQEPQPGTKVIMRAVNDWILERLARKSKPRMCSREEFIAKVKSNAALGAWSDEQNRWSSAKEAVEDPAFWQLVDEERERHLAGRCAHCVYNMMGKREKKLGEFGVAKGSRAIWYMWLGSRFLEFEALGFLNEDHWASRGSSGSGVEGISLNYLGWHLKGLSTLEGGLFYADDTAGWDTKVTNADLEDEEQLLRYMEGEHKQLAATIMQKAYHAKVVKVARPSRDGGCIMDVITRRDQRGSGQVVTYALNTLTNIKVQLIRMMEGEGVIEASDAHNPRLLRVERWLRDHGEERLGRMLVSGDDCVVRPVDDRFSRALYFLNDMAKTRKDIGEWEHSVGFSNWEEVPFCSHHFHELVMKDGRALIVPCRDQDELVGRARVSPGCGWSVRETACLSKAYGQMWLLSYFHRRDLRTLGLAICSAVPVDWVPTGRTTWSIHASGSWMTTEDMLDVWNRVWILDNPFMHSKEKIAEWRDVPYLPKSHDMLCSSLVGRKERAEWAKNIWGAVEKVRKMIGQEKFKDYLSCMDRHDLHWELKLESSII";
$qlen = strlen( $seq );
$dbfile = "/opt/base/seq1117/nrvirus.fa";
$res = file( "ns5align.txt" );
$rv = "";
$esize = 100;
$species = array( "[Tick-borne encephalitis virus]", "[Omsk hemorrhagic fever virus]", "[Kyasanur forest disease virus]", "[Japanese encephalitis virus]", "[West Nile virus]", "[Dengue virus 1]", "[Dengue virus 2]", "[Dengue virus 3]", "[Dengue virus 4]", "[Yellow fever virus]", "[Zika virus]", "[Usutu virus]" );
$sabbr = array( "TBEV", "OHFV", "KFDV", "JEV", "WNV", "DV1", "DV2", "DV3", "DV4", "YFV", "ZIKA", "USU" );
foreach ( $res as $rstr )
{
	if ( strlen( $rstr ) == 0 ) continue;
	$cres = explode( "\t", $rstr );
	$fs = sizeof( $cres ) + 1;
	$id = substr( $cres[0], 0, strcspn( $cres[0], " " ) );
	$alength = $cres[ $fs - 3 ] - $cres[ $fs - 4 ];
	if ( $alength < 800 ) continue;
	$cabbr = "";
	for ( $i = 0; $i < sizeof( $species ); $i++ )
	{
		if ( strpos( $cres[0], $species[ $i ] ) !== false )
		{
			$cabbr = $sabbr[ $i ];
			break;
		}
	}
	if ( strlen( $cabbr ) == 0 ) continue;
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
	echo ">$id $cabbr\n$cseq\n";
}
?>