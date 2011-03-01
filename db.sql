/*
 *      This file is part of Project Lumiére <http://monossido.ath.cx/cinema>
 *      
 *      Project Lumiére is free software: you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation, either version 3 of the License, or
 *      (at your option) any later version.
 *      
 *      Project Lumiére  is distributed in the hope that it will be useful,
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *      GNU General Public License for more details.
 *      
 *      You should have received a copy of the GNU General Public License
 *      along with Transdroid.  If not, see <http://www.gnu.org/licenses/>.
 *      
 */
--
-- Database: `cinema`
--

-- --------------------------------------------------------

--
-- Table structure for table `Film`
--

CREATE TABLE IF NOT EXISTS `Film` (
  `id_film` tinyint(4) NOT NULL auto_increment,
  `titolo` varchar(255) NOT NULL,
  `risoluzione` varchar(6) NOT NULL,
  `lingua` varchar(255) NOT NULL,
  `visto` tinyint(1) NOT NULL,
  `durata` varchar(40) NOT NULL,
  `voti` tinyint(4) NOT NULL,
  `passato` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id_film`),
  UNIQUE KEY `titolo` (`titolo`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;


-- --------------------------------------------------------

--
-- Table structure for table `Stato`
--

CREATE TABLE IF NOT EXISTS `Stato` (
  `Round` tinyint(4) NOT NULL,
  `RegistrazioniAperte` tinyint(1) NOT NULL,
  `VotazioniAperte` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Stato`
--

INSERT INTO `Stato` (`Round`, `RegistrazioniAperte`, `VotazioniAperte`) VALUES
(0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `Utenti`
--

CREATE TABLE IF NOT EXISTS `Utenti` (
  `id_utente` tinyint(4) NOT NULL auto_increment,
  `Username` varchar(20) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Voto` tinyint(4) NOT NULL,
  `amministratore` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id_utente`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;


-- --------------------------------------------------------

--
-- Table structure for table `Votazioni`
--

CREATE TABLE IF NOT EXISTS `Votazioni` (
  `idvotazione` tinyint(10) NOT NULL auto_increment,
  `id_film` tinyint(4) NOT NULL,
  `id_utente` tinyint(4) NOT NULL,
  `voto` tinyint(2) NOT NULL,
  PRIMARY KEY  (`idvotazione`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;


