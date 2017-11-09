--
-- Struktur-dump for tabellen `tblSvgElement`
--

CREATE TABLE IF NOT EXISTS `tblSvgElement` (
  `PlaceID` int(11) NOT NULL,
  `SVG` int(11) NOT NULL,
  `SVGPosition` enum('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17') NOT NULL,
  `Path1Color` varchar(45) DEFAULT NULL,
  `Path2Color` varchar(45) DEFAULT NULL,
  `Path3Color` varchar(45) DEFAULT NULL,
  KEY `PlaceID` (`PlaceID`),
  KEY `SVG` (`SVG`),
  KEY `SVG_2` (`SVG`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `tblSvgs`
--

CREATE TABLE IF NOT EXISTS `tblSvgs` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(200) NOT NULL,
  `Path1` longtext NOT NULL,
  `Path2` longtext,
  `Path3` longtext,
  `Type` enum('EDGE','CORNER') NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`),
  KEY `ID_2` (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Data dump for tabellen `tblSvgs`
--
ALTER TABLE `tblSvgElement` ADD UNIQUE `unique_index`(`PlaceID`, `SVGPosition`);
