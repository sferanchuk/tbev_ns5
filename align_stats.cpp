
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#include <string>
#include <vector>
#include <set>
#include <map>

using namespace std;

int main( int argc, char **argv )
{
	const char *tid = "ACJ38115.1";
	FILE *ifile = stdin;
	char buf[65535];
	map<string,map<string,string > > seqs;
	string id;
	string spec;
	while ( fgets( buf, 65535, ifile ) )
	{
		if ( buf[0] == '>' )
		{
			char *p1 = strtok( buf + 1, " \n" );
			char *p2 = strtok( 0, " \n" );
			if ( !p2 ) continue;
			id = p1;
			spec = p2;
		}
		else
		{
			strtok( buf, "\n" );
			seqs[ spec ][ id ] += buf;
		}
	}
	string selseq;
	int alen = 0;
	printf( "*\t*\t" );
	for ( map<string,map<string,string> >::iterator it = seqs.begin(); it != seqs.end(); it++ )
	{
		printf( "%s\t", it->first.data() );
		alen = it->second.begin()->second.size();
		if ( it->second.find( tid ) != it->second.end() ) selseq = it->second[ tid ];
	}
	printf( "\n" );
	int tcnt = 1;
	for ( int pc = 0; pc < alen; pc++ )
	{
		printf( "%d\t%d\t", pc + 1, tcnt );
		if ( selseq.size() > pc && selseq[pc] != '-' ) tcnt++;
		for ( map<string,map<string,string> >::iterator it = seqs.begin(); it != seqs.end(); it++ )
		{
			map<int,int> lcount;
			for ( map<string,string>::iterator it1 = it->second.begin(); it1 != it->second.end(); it1++ )
			{
				lcount[ it1->second[ pc ] ]++;
			}
			multimap<int,int> rlcount;
			for ( map<int,int>::iterator it1 = lcount.begin(); it1 != lcount.end(); it1++ )
			{
				rlcount.insert( pair<int,int>( it1->second, it1->first ) );
			}
			for ( multimap<int,int>::reverse_iterator it1 = rlcount.rbegin(); it1 != rlcount.rend(); it1++ )
			{
				printf( "[%c %d] ", it1->second, it1->first );
			}
			printf( "\t" );
		}
		printf( "\n" );
	}
	return 0;
}


		