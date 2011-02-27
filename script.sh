#!/bin/bash
	echo -e "Inserimento film nel database in corso..\n"
	echo "------------------------------------------"
	
	a=0
        IFS=$'\n';for i in $(ls *.mkv); do
		temp=`echo $i | grep -i 720p`
		if [ -z $temp ]
		then
			ris="1080p"
		else
			ris="720p"
		fi
		temp1=`mkvinfo $i | grep -i "Language: ita"`
		temp2=`mkvinfo $i | grep -i "Language: eng"`
#Ho dovuto usare -z (stringa nulla) perchè -n (stringa non nulla) dava problemi
		if [ -z "$temp1" ]
		then
			if [ -z "$temp2" ]
			then
				lingua=""
			else
				lingua="Eng"
			fi
		else
			if [ -z "$temp2" ]
			then
				lingua="Ita"
			else
				lingua="Ita/Eng"
			fi
		fi
		temp3=`mkvinfo $i | grep "Duration" | sed -e 's/^.*(//g' | sed -e 's/\..*$//g'`
		nome=$(perl -MURI::Escape -e 'print uri_escape($ARGV[0]);' "$i")
		wget -O /dev/null -q "http://monossido.ath.cx/cinema/inserisci.php?titoli=$nome&ris=$ris&lin=$lingua&dur=$temp3"
		(( ++a ))
	done
	echo -e "------------------------------------------\n"
	echo Sono stati inseriti correttamente $a films.
	
