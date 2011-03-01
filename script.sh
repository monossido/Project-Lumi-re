#!/bin/bash
#
#      This file is part of Project Lumiére <http://monossido.ath.cx/cinema>
#      
#      Project Lumiére is free software: you can redistribute it and/or modify
#      it under the terms of the GNU General Public License as published by
#      the Free Software Foundation, either version 3 of the License, or
#      (at your option) any later version.
#      
#      Project Lumiére  is distributed in the hope that it will be useful,
#      but WITHOUT ANY WARRANTY; without even the implied warranty of
#      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#      GNU General Public License for more details.
#      
#      You should have received a copy of the GNU General Public License
#      along with Transdroid.  If not, see <http://www.gnu.org/licenses/>.
#      
#
	if [ -z $1 ] || [ -z $2 ]
	then
		echo "Usage: script.sh utente password"
		exit;
	fi
	wget --post-data "test=1&username=$1&password=$2" -O "/tmp/project_lumiere" -q "http://monossido.ath.cx/cinema/inserisci.php"
	dati=`cat /tmp/project_lumiere`
	if [ -n "$dati" ]
	then
		echo $dati
		rm "/tmp/project_lumiere"
		exit;
	fi
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
		wget --post-data "username=$1&password=$2" -O /dev/null -q "http://monossido.ath.cx/cinema/inserisci.php?titoli=$nome&ris=$ris&lin=$lingua&dur=$temp3"
		(( ++a ))
	done
	echo -e "------------------------------------------\n"
	echo Sono stati inseriti correttamente $a films.
	
