 
INSERT INTO `_DATABASE_NAME_`.`AccessMethod` (shortName) values ('CD Autonome');
INSERT INTO `_DATABASE_NAME_`.`AccessMethod` (shortName) values ('Hôte externe');
INSERT INTO `_DATABASE_NAME_`.`AccessMethod` (shortName) values ('Hôte local');



INSERT INTO `_DATABASE_NAME_`.`AcquisitionType` (acquisitionTypeID, shortName) values (1, 'Payant');
INSERT INTO `_DATABASE_NAME_`.`AcquisitionType` (acquisitionTypeID, shortName) values (2, 'Gratuit');
INSERT INTO `_DATABASE_NAME_`.`AcquisitionType` (acquisitionTypeID, shortName) values (3, 'Démonstration');


INSERT INTO `_DATABASE_NAME_`.`AdministeringSite` (shortName) values ('Bibliothèque Unique');


INSERT INTO `_DATABASE_NAME_`.`AlertDaysInAdvance` (daysInAdvanceNumber) values ('30');
INSERT INTO `_DATABASE_NAME_`.`AlertDaysInAdvance` (daysInAdvanceNumber) values ('60');
INSERT INTO `_DATABASE_NAME_`.`AlertDaysInAdvance` (daysInAdvanceNumber) values ('90');



INSERT INTO `_DATABASE_NAME_`.`AliasType` (shortName) values ('Abréviation');
INSERT INTO `_DATABASE_NAME_`.`AliasType` (shortName) values ('Nom alternative');
INSERT INTO `_DATABASE_NAME_`.`AliasType` (shortName) values ('Nom Change');



INSERT INTO `_DATABASE_NAME_`.`AttachmentType` (shortName) values ('Email');
INSERT INTO `_DATABASE_NAME_`.`AttachmentType` (shortName) values ("Guide d'Utilisateur");
INSERT INTO `_DATABASE_NAME_`.`AttachmentType` (shortName) values ('Liste des titres');
INSERT INTO `_DATABASE_NAME_`.`AttachmentType` (shortName) values ('Géneral');



INSERT INTO `_DATABASE_NAME_`.`AuthenticationType` (shortName) values ('Adress IP');
INSERT INTO `_DATABASE_NAME_`.`AuthenticationType` (shortName) values ("Nom d'utilisateur");
INSERT INTO `_DATABASE_NAME_`.`AuthenticationType` (shortName) values ('URL de Réference');



INSERT INTO `_DATABASE_NAME_`.`AuthorizedSite` (shortName) values ('Campus Unique');

INSERT INTO `_DATABASE_NAME_`.`CatalogingType` (catalogingTypeID, shortName) values (1, 'Lot');
INSERT INTO `_DATABASE_NAME_`.`CatalogingType` (catalogingTypeID, shortName) values (2, 'Manual');
INSERT INTO `_DATABASE_NAME_`.`CatalogingType` (catalogingTypeID, shortName) values (3, 'MARCit');

INSERT INTO `_DATABASE_NAME_`.`CatalogingStatus` (catalogingStatusID, shortName) values (1, 'Terminé');
INSERT INTO `_DATABASE_NAME_`.`CatalogingStatus` (catalogingStatusID, shortName) values (2, 'En cours');
INSERT INTO `_DATABASE_NAME_`.`CatalogingStatus` (catalogingStatusID, shortName) values (3, 'Rejeté');

INSERT INTO `_DATABASE_NAME_`.`ContactRole` (shortName) values ('Soutien');
INSERT INTO `_DATABASE_NAME_`.`ContactRole` (shortName) values ('Comptabilité');
INSERT INTO `_DATABASE_NAME_`.`ContactRole` (shortName) values ('Géneral');
INSERT INTO `_DATABASE_NAME_`.`ContactRole` (shortName) values ('Licence');
INSERT INTO `_DATABASE_NAME_`.`ContactRole` (shortName) values ('Ventes');
INSERT INTO `_DATABASE_NAME_`.`ContactRole` (shortName) values ('Formation');



INSERT INTO `_DATABASE_NAME_`.`Currency` (currencyCode, shortName) values ('USD', 'United States Dollar');
INSERT INTO `_DATABASE_NAME_`.`Currency` (currencyCode, shortName) values ('EUR', 'Euro');
INSERT INTO `_DATABASE_NAME_`.`Currency` (currencyCode, shortName) values ('GBP', 'Great Britain (UK) Pound');
INSERT INTO `_DATABASE_NAME_`.`Currency` (currencyCode, shortName) values ('CAD', 'Canadian Dollar');
INSERT INTO `_DATABASE_NAME_`.`Currency` (currencyCode, shortName) values ('ARS', 'Argentine Peso');
INSERT INTO `_DATABASE_NAME_`.`Currency` (currencyCode, shortName) values ('AUD', 'Australian Dollar');
INSERT INTO `_DATABASE_NAME_`.`Currency` (currencyCode, shortName) values ('SEK', 'Swedish Krona');




INSERT INTO `_DATABASE_NAME_`.`ExternalLoginType` (shortName) values ('Admin');
INSERT INTO `_DATABASE_NAME_`.`ExternalLoginType` (shortName) values ('FTP');
INSERT INTO `_DATABASE_NAME_`.`ExternalLoginType` (shortName) values ('Statistiques');
INSERT INTO `_DATABASE_NAME_`.`ExternalLoginType` (shortName) values ('Soutien');



INSERT INTO `_DATABASE_NAME_`.`LicenseStatus` (shortName) values ('En suspens');
INSERT INTO `_DATABASE_NAME_`.`LicenseStatus` (shortName) values ('Completé');
INSERT INTO `_DATABASE_NAME_`.`LicenseStatus` (shortName) values ('Aucune Licence requise');



INSERT INTO `_DATABASE_NAME_`.`NoteType` (shortName) values ('Détails du Produit');
INSERT INTO `_DATABASE_NAME_`.`NoteType` (shortName) values ('Détails Acquisition');
INSERT INTO `_DATABASE_NAME_`.`NoteType` (shortName) values ('Détails Accès');
INSERT INTO `_DATABASE_NAME_`.`NoteType` (shortName) values ('Géneral');
INSERT INTO `_DATABASE_NAME_`.`NoteType` (shortName) values ('Détails de la Licence');
INSERT INTO `_DATABASE_NAME_`.`NoteType` (shortName) values ('Note Initial');



INSERT INTO `_DATABASE_NAME_`.`OrderType` (shortName) values ('En cours');
INSERT INTO `_DATABASE_NAME_`.`OrderType` (shortName) values ('Une fois');



INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (2, 'Institute of Physics');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (3, 'American Institute of Aeronautics and Astronautics');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (4, 'American Physical Society');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (5, 'American Society of Civil Engineers');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (6, 'American Insitute of Physics');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (7, 'Society for Industrial and Applied Mathematics');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (8, 'TNS Media Intelligence');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (9, 'Chemical Abstracts Service');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (10, 'Risk Management Association');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (11, 'American Concrete Institute');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (12, 'American Association for Cancer Research');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (13, 'Institution of Engineering and Technology');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (14, 'American Economic Association');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (15, 'American Mathematical Society');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (16, 'American Medical Association');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (18, 'Consejo Superior de Investigaciones Cientificas');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (19, 'American Meteorological Society');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (20, 'American Library Association');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (21, 'American Society for Testing and Materials');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (22, 'Association of Research Libraries');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (23, 'American Society of Limnology and Oceanography');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (24, 'Tablet Publishing');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (25, 'American Psychological Association');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (26, 'American Council of Learned Societies');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (27, 'American Association for the Advancement of Science');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (28, 'Thomson Healthcare and Science');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (29, 'Elsevier');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (30, 'JSTOR');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (31, 'SAGE Publications');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (32, 'American Geophysical Union');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (33, 'Annual Reviews');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (34, 'BioOne');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (35, 'Gale');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (36, 'Wiley');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (37, 'Oxford University Press');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (38, 'Springer');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (39, 'Taylor and Francis');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (40, 'Stanford University');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (41, 'University of California Press');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (42, 'EBSCO Publishing');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (43, 'Blackwell Publishing');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (44, 'Ovid');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (45, 'Project Muse');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (46, 'American Fisheries Society');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (47, 'Neilson Journals Publishing');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (48, 'GuideStar USA, Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (49, 'Alexander Street Press, LLC');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (50, 'Informa Healthcare USA, Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (51, 'ProQuest LLC');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (52, 'Accessible Archives Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (53, 'ACCU Weather Sales and Services, Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (54, 'Adam Matthew Digital Ltd');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (55, 'Akademiai Kiado');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (56, 'World Trade Press');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (57, 'World Scientific');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (58, 'Walter de Gruyter');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (59, 'Cambridge University Press');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (60, 'GeoScienceWorld');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (61, 'IEEE');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (62, 'Yankelovich Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (63, 'Nature Publishing Group');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (64, 'Verlag der Zeitschrift fur Naturforschung ');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (65, 'White Horse Press');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (66, 'Verlag C.H. Beck');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (67, 'Vault, Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (68, 'Value Line, Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (69, 'Vanderbilt University');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (70, 'Uniworld Business Publications, Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (71, 'Universum USA');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (72, 'University of Wisconsin Press');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (73, 'University of Virginia Press');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (74, 'University of Toronto Press Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (75, 'University of Toronto');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (76, 'University of Pittsburgh');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (77, 'University of Illinois Press');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (78, 'University of Chicago Press');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (79, 'University of Barcelona');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (80, 'UCLA Chicano Studies Research Center Press');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (81, 'Transportation Research Board');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (82, 'Trans Tech Publications');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (83, 'Thomas Telford Ltd');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (84, 'Thesaurus Linguae Graecae');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (85, 'Tetrad Computer Applications Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (86, 'Swank Motion Pictures, Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (87, 'Standard and Poors');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (88, 'SPIE');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (89, 'European Society of Endocrinology');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (90, 'Society of Environmental Toxicology and Chemistry');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (91, 'Society of Antiquaries of Scotland');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (92, 'Society for Reproduction and Fertility');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (93, 'Society for Neuroscience');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (94, 'Society for Leukocyte Biology');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (95, 'Society for General Microbiology');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (96, 'Society for Experimental Biology and Medicine');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (97, 'Society for Endocrinology');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (98, 'Societe Mathematique de France');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (99, 'Social Explorer');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (404, 'SETAC');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (101, 'Swiss Chemical Society');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (102, 'Scholarly Digital Editions');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (103, 'Royal Society of London');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (104, 'Royal Society of Chemistry');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (105, 'Roper Center for Public Opinion Research');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (106, 'Rockefeller University Press');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (107, 'Rivista di Studi italiani');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (108, 'Reuters Loan Pricing Corporation');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (109, 'Religious and Theological Abstracts, Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (110, 'Psychoanalytic Electronic Publishing Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (111, 'Cornell University Library');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (112, 'Preservation Technologies LP');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (113, 'Portland Press Limited');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (114, 'ITHAKA');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (115, 'Philosophy Documentation Center');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (116, 'Peeters Publishers');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (117, 'Paratext');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (118, 'Mathematical Sciences Publishers');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (119, 'Oxford Centre of Hebrew and Jewish Studies');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (120, 'NewsBank, Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (121, 'Massachusetts Medical Society');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (122, 'Naxos of America, Inc.');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (123, 'National Research Council of Canada');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (124, 'National Gallery Company Ltd');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (125, 'National Academy of Sciences');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (126, 'Mintel International Group Limited');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (127, 'Metropolitan Opera');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (128, 'M.E. Sharpe, Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (129, 'Mergent, Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (130, 'Mediamark Research and Intelligence, LLC');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (131, 'Mary Ann Liebert, Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (132, 'MIT Press');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (133, 'MarketResearch.com, Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (134, 'Marine Biological Laboratory');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (135, 'W.S. Maney and Son Ltd');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (136, 'Manchester University Press');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (137, 'Lord Music Reference Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (138, 'Liverpool University Press');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (139, 'Seminario Matematico of the University of Padua');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (140, 'Library of Congress, Cataloging Distribution Service');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (141, 'LexisNexis');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (142, 'Corporacion Latinobarometro');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (143, 'Landes Bioscience');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (144, 'Keesings Worldwide, LLC');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (145, 'Karger');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (146, 'John Benjamins Publishing Company');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (147, 'Irish Newspaper Archives Ltd');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (148, 'IPA Source, LLC');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (149, 'International Press');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (150, 'Intelligence Research Limited');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (151, 'Intellect');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (152, 'InteLex');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (153, 'Institute of Mathematics of the Polish Academy of Sciences');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (154, 'Ingentaconnect');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (155, 'INFORMS');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (156, 'Information Resources, Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (157, 'Indiana University Mathematics Journal');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (158, 'Incisive Media Ltd');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (159, 'IGI Global ');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (160, 'IBISWorld USA');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (161, 'H.W. Wilson Company');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (162, 'University of Houston Department of Mathematics');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (163, 'Histochemical Society');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (164, 'Morningstar Inc.');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (165, 'Paradigm Publishers');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (166, 'HighWire Press');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (167, 'Heldref Publications');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (168, 'Haworth Press');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (417, 'Thomson Legal');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (170, 'IOS Press');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (171, 'Agricultural Institute of Canada');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (172, 'Allen Press');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (173, 'H1 Base, Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (175, 'Global Science Press');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (176, 'Geographic Research, Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (177, 'Genetics Society of America');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (178, 'Franz Steiner Verlag');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (179, 'Forrester Research, Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (180, 'Federation of American Societies for Experimental Biology');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (181, 'Faulkner Information Services');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (182, 'ExLibris');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (183, 'Brill');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (184, 'Evolutionary Ecology Ltd');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (185, 'European Mathematical Society Publishing House');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (186, 'New York Review of Books');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (187, 'Dunstans Publishing Ltd');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (188, 'Equinox Publishing Ltd');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (189, 'Entomological Society of Canada');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (190, 'American Association of Immunologists, Inc.');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (191, 'Endocrine Society');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (192, 'EDP Sciences');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (193, 'Edinburgh University Press');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (194, 'Ecological Society of America');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (195, 'East View Information Services');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (196, 'Dun and Bradstreet Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (197, 'Duke University Press');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (198, 'Digital Distributed Community Archive');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (199, 'Albert C. Muller');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (200, 'Dialogue Foundation');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (201, 'Dialog');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (202, 'Current History, Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (203, 'CSIRO Publishing');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (204, 'CQ Press');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (205, 'Japan Focus');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (206, 'Carbon Disclosure Project');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (207, 'University of Buckingham Press');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (208, 'Boopsie, INC.');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (209, 'Company of Biologists Ltd');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (210, 'Chronicle of Higher Education');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (211, 'CCH Incorporated');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (212, 'CareerShift LLC');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (213, 'Canadian Mathematical Society');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (214, 'Cambridge Crystallographic Data Centre');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (215, 'CABI Publishing');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (216, 'Business Monitor International');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (217, 'Bureau of National Affairs, Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (218, 'Bulletin of the Atomic Scientists');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (219, 'Brepols Publishers');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (221, 'Botanical Society of America');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (222, 'BMJ Publishing Group Limited');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (223, 'BioMed Central');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (224, 'Berkeley Electronic Press');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (225, 'Berghahn Books');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (226, 'Berg Publishers');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (227, 'Belser Wissenschaftlicher Dienst Ltd');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (228, 'Beilstein Information Systems, Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (229, 'Barkhuis Publishing');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (230, 'Augustine Institute');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (231, 'Asempa Limited');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (232, 'ARTstor Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (233, 'Applied Probability Trust');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (234, 'Antiquity Publications Limited');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (235, 'Ammons Scientific Limited');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (236, 'American Statistical Association');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (237, 'American Society of Tropical Medicine and Hygiene');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (238, 'American Society of Plant Biologists');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (239, 'Teachers College Record');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (240, 'American Society of Agronomy');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (241, 'American Society for Nutrition');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (242, 'American Society for Horticultural Science');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (243, 'American Society for Clinical Investigation');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (244, 'American Society for Cell Biology');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (245, 'American Psychiatric Publishing');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (246, 'American Phytopathological Society');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (247, 'American Physiological Society');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (248, 'Encyclopaedia Britannica Online');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (249, 'Agricultural History Society');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (250, 'Begell House, Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (251, 'Hans Zell Publishing');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (252, 'Alliance for Children and Families');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (253, 'Robert Blakemore');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (254, 'IVES Group, Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (255, 'Massachusetts Institute of Technology');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (256, 'Marquis Who\'s Who LLC');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (257, 'Atypon Systems Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (258, 'Worldwatch Institute');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (259, 'Thomson Financial');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (260, 'Digital Heritage Publishing Limited');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (261, 'U.S. Department of Commerce');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (262, 'Lipper Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (263, 'Chiniquy Collection');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (264, 'OCLC');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (265, 'Consumer Electronics Association');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (267, 'Institutional Shareholder Services Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (268, 'KLD Research and Analytics Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (269, 'BIGresearch LLC');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (270, 'Cambridge Scientific Abstracts');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (271, 'American Institute of Certified Public Accountants');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (272, 'Terra Scientific Publishing Company');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (273, 'American Counseling Association');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (274, 'Army Times Publishing Company');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (275, 'Librairie Droz');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (276, 'American Academy of Religion');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (277, 'Boyd Printing');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (278, 'Canadian Association of African Studies');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (279, 'Experian Marketing Solutions, Inc.');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (280, 'Centro de Investigaciones Sociologicas');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (281, 'Chorus America');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (282, 'College Art Association');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (283, 'Human Kinetics Inc.');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (288, 'NERL');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (293, 'Colegio de Mexico');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (294, 'University of Iowa');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (295, 'Academy of the Hebrew Language');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (296, 'FamilyLink.com, Inc.');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (297, 'SISMEL - Edizioni del Galluzzo');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (301, 'Informaworld');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (302, 'ScienceDirect');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (304, 'China Data Center');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (305, 'Association for Computing Machinery');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (306, 'American Chemical Society');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (307, 'Design Research Publications');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (308, 'ABC-CLIO');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (311, 'American Association on Intellectual and Developmental Disabilities ');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (310, 'American Antiquarian Society');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (312, 'American Society for Microbiology');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (314, 'American Society of Mechanical Engineers');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (315, 'Now Publishers, Inc.');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (316, 'Cabell Publishing Company, Inc.');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (317, 'Center for Research Libraries');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (444, 'Cold North Wind Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (321, 'Erudit ');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (322, 'American Institute of Mathematical Sciences');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (324, 'American Sociological Association');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (325, 'Archaeological Institute of America');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (326, 'Bertrand Russell Research Centre');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (328, 'Cork University Press');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (329, 'College Publishing');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (330, 'Council for Learning Disabilities');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (331, 'International Society on Hypertension in Blacks (ISHIB)');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (332, 'Firenze University Press');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (333, 'History of Earth Sciences Society');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (334, 'History Today Ltd.');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (335, 'Journal of Music');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (336, 'University of Nebraska at Omaha');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (337, 'Journal of Indo-European Studies');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (338, 'Library Binding Institute');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (339, 'McFarland & Co. Inc.');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (340, 'Lyrasis');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (341, 'Amigos Library Services');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (343, 'Fabrizio Serra Editore');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (344, 'Aux Amateurs');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (346, 'National Affairs, Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (357, 'Society of Chemical Industry');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (347, 'New Criterion');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (348, 'Casa Editrice Leo S. Olschki s.r.l.');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (349, 'Rhodes University, Department of Philosophy');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (350, 'Rocky Mountain Mathematics Consortium');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (352, 'Royal Irish Academy');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (353, 'Chadwyck-Healey');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (354, 'CSA illumina');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (355, 'New School for Social Research');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (356, 'Society of Biblical Literature');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (358, 'Stazione Zoologica Anton Dohrn');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (359, 'BioScientifica Ltd.');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (360, 'CASALINI LIBRI');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (361, 'Institute of Organic Chemistry');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (362, 'Columbia International Affairs Online ');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (363, 'Corporation for National Research Initiatives ');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (364, 'Tilgher-Genova');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (365, 'Emerald Group Publishing Limited');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (366, 'Geological Society of America');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (367, 'Institute of Mathematical Statistics');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (368, 'Institute of Pure and Applied Physics');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (369, 'JSTAGE');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (370, 'Metapress');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (371, 'Modern Language Association');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (372, 'Optical Society of America');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (373, 'University of British Columbia');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (374, 'University of New Mexico');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (375, 'Vandenhoeck & Ruprecht');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (376, 'Verlag Mohr Siebeck GmbH & Co. KG');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (377, 'Palgrave Macmillan');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (378, 'Vittorio Klostermann');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (379, 'Project Euclid');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (380, 'Psychonomic Society ');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (411, 'Cengage Learning');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (382, 'Infotrieve');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (383, 'Society of Automotive Engineers');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (384, 'Turpion Publications');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (426, 'Midwest Collaborative for Library Services');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (386, 'Lawrence Erlbaum Associates');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (387, 'Alphagraphics');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (388, 'Bellerophon Publications, Inc.');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (389, 'Boydell & Brewer Inc.');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (390, 'Carcanet Press');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (391, 'Feminist Studies');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (393, 'Dustbooks');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (394, 'Society for Applied Anthropology ');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (395, 'United Nations Publications');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (396, 'Wharton Research Data Services');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (398, 'Human Development');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (399, 'infoUSA Marketing, Inc.');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (400, 'Bowker');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (402, 'Brown University');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (401, 'Women Writers Project');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (445, 'Coutts');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (446, 'Numara Software, Inc.');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (447, 'Trinity College Library Dublin');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (405, 'Oldenbourg Wissenschaftsverlag ');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (406, 'Dow Jones');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (412, 'Financial Information Inc. (FII)');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (408, 'Jackson Publishing and Distribution');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (409, 'Elsevier Engineering Information, Inc. ');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (410, 'Eneclann Ltd.');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (413, 'UCLA Latin American Institute');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (414, 'Harmonie Park Press ');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (415, 'Harrassowitz');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (416, 'Thomson Reuters');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (418, 'Human Relations Area Files, Inc. ');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (432, 'Capital IQ');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (419, 'Society for Ethnomusicology');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (420, 'MSCI RiskMetrics');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (421, 'Rapid Multimedia');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (422, 'MSCI Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (423, 'New England Journal of Medicine');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (424, 'NetLibrary');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (425, 'CARMA');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (427, 'Public Library of Science');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (428, 'Social Science Electronic Publishing');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (429, 'United Nations Industrial Develoipment Organization');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (430, 'University of Michigan Press');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (431, 'ORS Publishing, Inc.');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (433, 'McGraw-Hill');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (434, 'Biblical Archaeology Society');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (435, 'GeoLytics, Inc.');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (436, 'JoVE ');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (437, 'ICEsoft Technologies, Inc.');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (438, 'Films Media Group');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (439, 'Films on Demand');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (440, 'Connect Journals');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (441, 'Scuola Normale Superiore');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (442, 'Wolters Kluwer');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (448, 'Pier Professional');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (449, 'ABC News');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (450, 'University of Aberdeen ');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (451, 'BullFrog Films, Inc.');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (453, 'FirstSearch');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (455, 'History Cooperative ');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (456, 'Omohundro Institute of Early American History and Culture');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (457, 'Arms Control Association');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (458, 'Heritage Archives');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (459, 'International Historic Films, Inc.');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (460, 'Euromonitor International ');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (461, 'Safari Books Online');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (462, 'Mirabile');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (466, 'Publishing Technology');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (463, 'SageWorks, Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (464, 'Johns Hopkins Universtiy Press');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (465, 'Knovel ');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (467, 'American Society of Nephrology');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (468, 'Water Envrionment Federation ');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (469, 'Refworks');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (470, 'Cinemagician Productions');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (471, 'Algorithmics');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (472, 'YBP Library Services ');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (474, 'Maydream Inc.');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (475, 'Organization for Economic Cooperation and Development');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (476, 'The Chronicle for Higher Education');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (477, 'Association for Research in Vision and Ophthalmologie (ARVO)');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (478, 'SRDS Media Solutions');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (479, 'Kantar Media');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (480, 'Peace & Justice Studies Association');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (481, 'Addison Publications Ltd.');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (482, 'Mutii-Science Publishing');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (483, 'ASM International');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (484, 'Verlag der Osterreichischen Akademie der Wissenschaften');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (485, 'Anthology of Recorded Music');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (486, 'Left Coast Press, Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (487, 'Video Data Bank');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (488, 'Atlassian');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (489, 'medici.tv');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (490, 'Bar Ilan Research & Development Company Ltd');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (491, 'Primary Source Media');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (492, 'Ebrary');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (493, 'University of Michigan, Department of Mathematics');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (494, 'StataCorp LP ');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (495, 'L\' Enseignement Mathematique  ');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (496, 'Audio Engineering Society, Inc');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (497, 'LOCKSS');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (498, 'MUSEEC ');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (499, 'Mortgage Bankers Association');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (500, 'BibleWorks');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (501, 'National Library of Ireland');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (502, 'Scholars Press');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (503, 'Index to Jewish periodicals');
INSERT INTO `_DATABASE_NAME_`.Organization (organizationID, shortName) values (504, 'Cold Spring Harbor Laboratory Press');



INSERT INTO `_DATABASE_NAME_`.OrganizationRole (shortName) values ("Consortium");
INSERT INTO `_DATABASE_NAME_`.OrganizationRole (shortName) values ("Bibliothèque");
INSERT INTO `_DATABASE_NAME_`.OrganizationRole (shortName) values ("Plate-forme");
INSERT INTO `_DATABASE_NAME_`.OrganizationRole (shortName) values ("Fornisseur");
INSERT INTO `_DATABASE_NAME_`.OrganizationRole (shortName) values ("Éditeur");
INSERT INTO `_DATABASE_NAME_`.OrganizationRole (shortName) values ("Vendeur");



INSERT INTO `_DATABASE_NAME_`.`Privilege` (shortName) values ('admin');
INSERT INTO `_DATABASE_NAME_`.`Privilege` (shortName) values ('ajouter/modifier');
INSERT INTO `_DATABASE_NAME_`.`Privilege` (shortName) values ('regarder seulement');


INSERT INTO `_DATABASE_NAME_`.`PurchaseSite` (shortName) values ('Bibliothèque Unique');


INSERT INTO `_DATABASE_NAME_`.`RelationshipType` (shortName) values ('Parent');
INSERT INTO `_DATABASE_NAME_`.`RelationshipType` (shortName) values ('General');



INSERT INTO `_DATABASE_NAME_`.`ResourceFormat` (resourceFormatID, shortName) values (1, 'Imprimible + Electronique');
INSERT INTO `_DATABASE_NAME_`.`ResourceFormat` (resourceFormatID, shortName) values (2, 'Electronique');



INSERT INTO `_DATABASE_NAME_`.`Status` (shortName) values ('En Cours');
INSERT INTO `_DATABASE_NAME_`.`Status` (shortName) values ('Completé');
INSERT INTO `_DATABASE_NAME_`.`Status` (shortName) values ('Enregistré');
INSERT INTO `_DATABASE_NAME_`.`Status` (shortName) values ('Archivé');


INSERT INTO `_DATABASE_NAME_`.`StorageLocation` (shortName) values ('Chambre des réserves des livres');

INSERT INTO `_DATABASE_NAME_`.`UserLimit` (shortName) values ('1');
INSERT INTO `_DATABASE_NAME_`.`UserLimit` (shortName) values ('2');
INSERT INTO `_DATABASE_NAME_`.`UserLimit` (shortName) values ('3');
INSERT INTO `_DATABASE_NAME_`.`UserLimit` (shortName) values ('4');
INSERT INTO `_DATABASE_NAME_`.`UserLimit` (shortName) values ('5');
INSERT INTO `_DATABASE_NAME_`.`UserLimit` (shortName) values ('6');
INSERT INTO `_DATABASE_NAME_`.`UserLimit` (shortName) values ('7');
INSERT INTO `_DATABASE_NAME_`.`UserLimit` (shortName) values ('8');
INSERT INTO `_DATABASE_NAME_`.`UserLimit` (shortName) values ('9');
INSERT INTO `_DATABASE_NAME_`.`UserLimit` (shortName) values ('10+');


INSERT INTO `_DATABASE_NAME_`.`Workflow` (workflowID, resourceFormatIDValue, resourceTypeIDValue, acquisitionTypeIDValue)
VALUES (1, '2', '','1');

INSERT INTO `_DATABASE_NAME_`.`Workflow` (workflowID, resourceFormatIDValue, resourceTypeIDValue, acquisitionTypeIDValue)
VALUES (2, '2', '','2');



INSERT INTO `_DATABASE_NAME_`.`UserGroup` (userGroupID, groupName) VALUES (1, 'Accès');
INSERT INTO `_DATABASE_NAME_`.`UserGroup` (userGroupID, groupName) VALUES (2, 'Licence');
INSERT INTO `_DATABASE_NAME_`.`UserGroup` (userGroupID, groupName) VALUES (3, 'Approbation du Financement');
INSERT INTO `_DATABASE_NAME_`.`UserGroup` (userGroupID, groupName) VALUES (4, 'Acquisitions');
INSERT INTO `_DATABASE_NAME_`.`UserGroup` (userGroupID, groupName) VALUES (5, 'Reçu');


INSERT INTO `_DATABASE_NAME_`.`Step` (stepID, priorStepID, stepName, userGroupID, workflowID, displayOrderSequence)
VALUES (1, NULL, 'Approbation du financement', 3, 1, 1);
INSERT INTO `_DATABASE_NAME_`.`Step` (stepID, priorStepID, stepName, userGroupID, workflowID, displayOrderSequence)
VALUES (2, NULL, 'Licence', 2, 1, 2);
INSERT INTO `_DATABASE_NAME_`.`Step` (stepID, priorStepID, stepName, userGroupID, workflowID, displayOrderSequence)
VALUES (3, 2, 'Process du commande', 4, 1, 3);
INSERT INTO `_DATABASE_NAME_`.`Step` (stepID, priorStepID, stepName, userGroupID, workflowID, displayOrderSequence)
VALUES (4, 3, 'Activation', 1, 1, 4);

INSERT INTO `_DATABASE_NAME_`.`Step` (stepID, priorStepID, stepName, userGroupID, workflowID, displayOrderSequence)
VALUES (5, NULL, 'Licence', 2, 2, 1);
INSERT INTO `_DATABASE_NAME_`.`Step` (stepID, priorStepID, stepName, userGroupID, workflowID, displayOrderSequence)
VALUES (6, NULL, 'Activation', 1, 2, 2);