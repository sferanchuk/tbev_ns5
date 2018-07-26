#!/usr/bin/python

import sys

if len( sys.argv ) == 1:
	print "arguments: align beg end"
	sys.exit( 1 )
	
beg = int( sys.argv[2] )
end = int( sys.argv[3] )

seq = ""
previd = ""
with open( sys.argv[1] ) as f:
	for line in f:
		if len( line ) == 0:
			continue
		if line[0:1] == ">":
			if len( seq ) > 0:
				print "%-20s\t%s" % ( previd, seq[ beg : end ] )
			previd = line[1:].strip()
			seq = ""
		else:
			seq += line.strip()
if len( seq ) > 0:
	print "%-20s\t%s\n" % ( previd, seq[ beg : end ] )
			
			
		