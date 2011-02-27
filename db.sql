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
  `giudizio` tinyint(2) NOT NULL,
  PRIMARY KEY  (`id_film`),
  UNIQUE KEY `titolo` (`titolo`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `Stato`
--

CREATE TABLE IF NOT EXISTS `Stato` (
  `Round` tinyint(4) NOT NULL,
  `RegistrazioniAperte` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Stato`
--

INSERT INTO `Stato` (`Round`, `RegistrazioniAperte`) VALUES
(0, 1);

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
