-- Table
DROP SCHEMA IF EXISTS sae CASCADE;
CREATE SCHEMA sae;

SET search_path TO sae;

CREATE TABLE _utilisateur (
  id SERIAL PRIMARY KEY,
  nom VARCHAR(255) NOT NULL,
  prenom VARCHAR(255) NOT NULL,
  date_naissance DATE NOT NULL,
  civilite VARCHAR(4) NOT NULL,
  pseudo VARCHAR(255) NOT NULL,
  mot_de_passe VARCHAR(255) NOT NULL,
  photo_profile VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  telephone VARCHAR(255) NOT NULL,
  id_adresse INT NOT NULL
);

CREATE TABLE _carte_identite (
  id SERIAL PRIMARY KEY,
  piece_id_recto VARCHAR(255) NOT NULL,
  piece_id_verso VARCHAR(255) NOT NULL,
  valide BOOLEAN NOT NULL
); 

CREATE TABLE _compte_proprietaire (
  id SERIAL PRIMARY KEY,
  id_identite INT NOT NULL,
  IBAN VARCHAR(255) NOT NULL,
  BIC VARCHAR(255) NOT NULL,
  Titulaire VARCHAR(255) NOT NULL
);

CREATE TABLE _compte_client (
  id SERIAL PRIMARY KEY
);

CREATE TABLE _compte_admin (
  id SERIAL PRIMARY KEY
);

CREATE TABLE _adresse (
  id SERIAL PRIMARY KEY,
  pays VARCHAR(255) NOT NULL,
  region VARCHAR(255) NOT NULL,
  departement VARCHAR(255) NOT NULL,
  commune VARCHAR(255) NOT NULL,
  code_postal VARCHAR(255) NOT NULL,
  numero VARCHAR(255) NOT NULL,  
  nom_voie VARCHAR(255) NOT NULL,
  complement_1 VARCHAR(255),
  complement_2 VARCHAR(255),
  complement_3 VARCHAR(255),
  latitude FLOAT,
  longitude FLOAT
);

CREATE TABLE _langue_proprietaire (
  id_proprietaire INT NOT NULL,
  id_langue INT NOT NULL,
  PRIMARY KEY (id_proprietaire, id_langue)
);

CREATE TABLE _langue (
  id SERIAL PRIMARY KEY,
  langue VARCHAR(255) NOT NULL
);

CREATE TABLE _avis (
  id SERIAL PRIMARY KEY,
  id_logement INT NOT NULL,
  id_client INT NOT NULL,
  commentaire TEXT NOT NULL,
  note FLOAT NOT NULL
);

CREATE TABLE _signalement (
  id SERIAL PRIMARY KEY,
  id_avis INT NOT NULL,
  commentaire TEXT NOT NULL
);

CREATE TABLE _reponse_avis (
  id SERIAL PRIMARY KEY,
  id_avis INT NOT NULL,
  commentaire TEXT NOT NULL
);

CREATE TABLE _amenagement (
  id SERIAL PRIMARY KEY,
  amenagement VARCHAR(255) NOT NULL
);

CREATE TABLE _amenagement_logement (
  id_logement INT NOT NULL,
  id_amenagement INT NOT NULL,
  PRIMARY KEY (id_amenagement, id_logement)
);

CREATE TABLE _activite_logement (
  id SERIAL PRIMARY KEY,
  id_logement INT NOT NULL,
  activite VARCHAR(255) NOT NULL,
  id_distance INT NOT NULL
);

CREATE TABLE _type_logement (
  id SERIAL PRIMARY KEY,
  type VARCHAR(255) NOT NULL
);

CREATE TABLE _categorie_logement (
  id SERIAL PRIMARY KEY,
  categorie VARCHAR(255) NOT NULL
);

CREATE TABLE _distance (
  id SERIAL PRIMARY KEY,
  perimetre VARCHAR(255) NOT NULL
);

CREATE TABLE _image (
  src VARCHAR(255) NOT NULL,
  principale BOOLEAN DEFAULT false NOT NULL,
  alt VARCHAR(255) NOT NULL,
  id_logement INT NOT NULL
);

CREATE TABLE _logement (
  id SERIAL PRIMARY KEY,
  id_proprietaire INT NOT NULL,
  id_adresse INT NOT NULL,
  titre VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  accroche VARCHAR(255) NOT NULL,
  base_tarif FLOAT NOT NULL,
  surface INT NOT NULL,
  nb_max_personne INT NOT NULL,
  nb_chambre INT NOT NULL,
  nb_lit_simple INT NOT NULL,
  nb_lit_double INT NOT NULL,
  duree_min_res INT NOT NULL,
  delai_avant_res INT NOT NULL,
  periode_preavis INT NOT NULL,
  en_ligne BOOLEAN NOT NULL,
  id_categorie INT NOT NULL,
  id_type INT NOT NULL
);

CREATE TABLE _calendrier (
  date DATE NOT NULL,
  id_logement INT NOT NULL,
  prix FLOAT NOT NULL,
  statut VARCHAR(1),
  PRIMARY KEY (date, id_logement)
);

CREATE TABLE _reservation (
  id SERIAL PRIMARY KEY,
  id_logement INT NOT NULL,
  id_client INT NOT NULL,
  date_reservation DATE NOT NULL,
  date_debut DATE NOT NULL,
  date_fin DATE NOT NULL,
  nb_occupant FLOAT NOT NULL,
  taxe_sejour FLOAT NOT NULL,
  taxe_commission FLOAT NOT NULL,
  prix_ht FLOAT NOT NULL,
  prix_ttc FLOAT NOT NULL,
  date_annulation DATE,
  annulation BOOL NOT NULL
);

CREATE TABLE _reservation_prix_par_nuit (
  id_reservation INT NOT NULL,
  prix FLOAT NOT NULL,
  nb_nuit INT NOT NULL,
  PRIMARY KEY (id_reservation, prix)
);

-- Foreign Key
ALTER TABLE _utilisateur
  ADD CONSTRAINT utilisateur_adresseid FOREIGN KEY (id_adresse) REFERENCES _adresse (id);
  
ALTER TABLE _compte_client
  ADD CONSTRAINT client_userid FOREIGN KEY (id) REFERENCES _utilisateur (id);

ALTER TABLE _compte_proprietaire
  ADD CONSTRAINT proprio_userid FOREIGN KEY (id) REFERENCES _utilisateur (id);
  
ALTER TABLE _compte_admin
  ADD CONSTRAINT admin_userid FOREIGN KEY (id) REFERENCES _utilisateur (id);
  
ALTER TABLE _langue_proprietaire 
  ADD CONSTRAINT _langue_proprietaire_langueid FOREIGN KEY (id_langue) 
  REFERENCES _langue (id);
  
ALTER TABLE _langue_proprietaire 
  ADD CONSTRAINT _langue_proprietaire_proprietaireid FOREIGN KEY (id_proprietaire) 
  REFERENCES _compte_proprietaire (id);
  
ALTER TABLE _avis 
  ADD CONSTRAINT _avis_clientid FOREIGN KEY (id_client) 
  REFERENCES _compte_client (id),
  ADD CONSTRAINT _avis_logementid FOREIGN KEY (id_logement) 
  REFERENCES _logement (id);

ALTER TABLE _reponse_avis
  ADD CONSTRAINT _reponse_avis_avisid FOREIGN KEY (id_avis)
  REFERENCES _avis (id);
  
ALTER TABLE _signalement
  ADD CONSTRAINT _signalement_avisid FOREIGN KEY (id_avis)
  REFERENCES _avis (id);

ALTER TABLE _calendrier
  ADD CONSTRAINT _calendrier_logementid FOREIGN KEY (id_logement)
  REFERENCES _logement (id);

ALTER TABLE _logement
  ADD CONSTRAINT _logement_categorieid FOREIGN KEY (id_categorie) 
    REFERENCES _categorie_logement (id),
  ADD CONSTRAINT _logement_typeid FOREIGN KEY (id_type) 
    REFERENCES _type_logement (id),
  ADD CONSTRAINT _logement_proprietaireid FOREIGN KEY (id_proprietaire) 
    REFERENCES _compte_proprietaire (id),
  ADD CONSTRAINT _logement_adresseid FOREIGN KEY (id_adresse) 
    REFERENCES _adresse (id);

ALTER TABLE _amenagement_logement
  ADD CONSTRAINT _amenagementlog_amenagementid FOREIGN KEY (id_amenagement) 
    REFERENCES _amenagement (id),
  ADD CONSTRAINT _amenagement_logementid FOREIGN KEY (id_logement) 
    REFERENCES _logement (id);

ALTER TABLE _activite_logement
  ADD CONSTRAINT _activite_distanceid FOREIGN KEY (id_distance) 
    REFERENCES _distance (id),
  ADD CONSTRAINT _activite_logementid FOREIGN KEY (id_logement) 
    REFERENCES _logement (id);

ALTER TABLE _reservation
  ADD CONSTRAINT _reservation_clientid FOREIGN KEY (id_client)
    REFERENCES _compte_client (id),
  ADD CONSTRAINT _reservation_logementid FOREIGN KEY (id_logement)
    REFERENCES _logement (id);

ALTER TABLE _reservation_prix_par_nuit
  ADD CONSTRAINT _reservation_ppn_reservationid FOREIGN KEY (id_reservation) 
    REFERENCES _reservation (id);

ALTER TABLE _image
  ADD CONSTRAINT _image_logementid FOREIGN KEY (id_logement)
    REFERENCES _logement (id);

ALTER TABLE _compte_proprietaire
  ADD CONSTRAINT _compte_proprietaire_identite FOREIGN KEY (id_identite)
    REFERENCES _carte_identite (id);

-- TRIGGER

CREATE OR REPLACE FUNCTION insert_cal_trigger()
RETURNS TRIGGER AS $$
BEGIN
    DELETE FROM _calendrier
    WHERE id_logement = NEW.id_logement AND date = NEW.date;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER insert_cal_trigger
BEFORE INSERT ON _calendrier
FOR EACH ROW
EXECUTE PROCEDURE insert_cal_trigger();

-- PEUPLEMENT 
INSERT INTO _categorie_logement (id, categorie) VALUES
(1, 'Appartement'),
(2, 'Maison'),
(3, 'Villa d''exception'),
(4, 'Chalet'),
(5, 'Bateau'),
(6, 'Logement insolite');

INSERT INTO _type_logement (id, type) VALUES
(1, 'T1'),
(2, 'T2'),
(3, 'T3'),
(4, 'T4'),
(5, 'T5 et plus'),
(6, 'F1'),
(7, 'F2'),
(8, 'F3'),
(9, 'F4'),
(10, 'F5 et plus');

INSERT INTO _distance (id, perimetre) VALUES
(1, 'sur place'),
(2, 'moins de 5 km'),
(3, 'moins de 10 km'),
(4, 'moins de 20 km'),
(5, '20 km ou plus');

INSERT INTO _langue(langue) VALUES
('Français'),
('Anglais'),
('Allemand'),
('Espagnol'),
('Italien'),
('Portugais'),
('Mendarin'),
('Hindi'),
('Arabe'),
('Ukrainien'),
('Russe');

INSERT INTO _amenagement (amenagement) VALUES 
('Jardin'), 
('Beer-Pong'),
('Cave à vin'),
('Fut de Bière'),
('Balcon'), 
('Terrasse'), 
('Piscine'),
('Jacuzzi'),  
('Lave-Vaiselle'), 
('Micro-Onde'),
('Four'),
('Climatisation'), 
('Barbecue'), 
('Lave-Linge'), 
('Sèche-Linge');

INSERT INTO _adresse (pays, region, departement, commune, code_postal, numero, nom_voie, longitude, latitude) VALUES
('France', 'Bretagne', 'Côte d''Armor', 'Tréguier', '22220', '5', 'Rue de Crublen', '-3.239756', '48.781099'),
('France', 'Bretagne', 'Côte d''Armor', 'Trégueux', '22950', '8', 'La Cloture', '-2.734797', '48.477424'),
('France', 'Bretagne', 'Finistère', 'Arzano', '29300', '28', 'Rue de Keravel', '-3.436063', '47.902366'),
('France', 'Bretagne', 'Ille-et-Vilaine', 'Vieux-Vy-sur-Couesnon', '35490', '40', 'La Perriere', '-1.491549', '48.33785'),
('France', 'Bretagne', 'Ille-et-Vilaine', 'Vezin-le-Coquet', '35132', '3', 'Rue du Champ Morio', '-1.756728', '48.12124'),
('France', 'Bretagne', 'Ille-et-Vilaine', 'Vezin-le-Coquet', '35132', '2', 'le Champ Noël', '-1.732101', '48.117256'),
('France', 'Bretagne', 'Ille-et-Vilaine', 'Vern-sur-Seiche', '35770', '97', 'Rue de Châteaubriant', '-1.602116', '48.03964'),
('France', 'Bretagne', 'Ille-et-Vilaine', 'Vern-sur-Seiche', '35770', '21', 'Avenue de Solidor', '-1.61559', '48.046809'),
('France', 'Bretagne', 'Morbihan', 'Allaire', '56350', '8', 'Impasse des Bengalis', '-2.16798', '47.634074'),
('France', 'Bretagne', 'Morbihan', 'Ambon', '56190', '12', 'Lotissement le Prinhuel', '-2.563304', '47.555222'),
('France', 'Bretagne', 'Morbihan', 'Ambon', '56190', '4', 'ZAC Espace Littoral', '-2.506348', '47.559448'),
('France', 'Bretagne', 'Morbihan', 'Arzal', '56190', '26', 'Parc Loisir du Galitour', '-2.379848', '47.518231'),
('France', 'Bretagne', 'Morbihan', 'Vannes', '56000', '9', 'Rue Louis Pasteur', '-2.763011', '47.655071'),
('France', 'Bretagne', 'Finistère', 'Plougourvest', '29400', '3', 'Impasse du Guillec', '-2.763011', '47.655071'),
('France', 'Bretagne', 'Finistère', 'Saint-Renan', '29300', '10', 'Rue des artisans', '-2.763011', '47.655071');

INSERT INTO _utilisateur (nom, prenom, date_naissance, civilite, pseudo, mot_de_passe, photo_profile, email, telephone, id_adresse) VALUES
('Dupont', 'Jean', '1944-03-15', 'Mr', 'JeanD', '$2y$10$BDX6AiuIWhjCXxTO3rXhMObumJjQsn6waQVEJPGcX3hZXolu4J9M.', './img/compte/profile_jeand.jpg', 'jean.dupont@gmail.com', '06 12 34 56 78', 9),
('Martin', 'Marie', '1947-05-23', 'Mme', 'MarieM', '$2y$10$lVCsVLWcTTKb4qHytuN6C.iHPHcLwjuxWBmDJ99GP.z3EYWYY80SG', './img/compte/profile_mariem.jpg', 'marie.martin@yahoo.com', '06 23 45 67 89', 10),
('Lefevre', 'Pierre', '1950-08-19', 'Mr', 'PierreL', '$2y$10$tACfPV0DiKt1PQJDGaiUUOUdlbK7vUjfs/3fpQF4zfY5NCWe6/hWG', './img/compte/profile_pierrel.jpg', 'pierre.lefevre@icloud.com', '06 34 56 78 90', 11),

('Bernard', 'Sophie', '1953-11-05', 'Mme', 'SophieB', '$2y$10$X/3LCBxqfR9r5e.9g7x5QOCh05UOqTzH8vibS3ej7Q9RqjyGNHFcG', './img/compte/profile_sophieb.jpg', 'sophie.bernard@free.com', '06 45 67 89 01', 12),
('Dubois', 'Jacques', '1956-01-29', 'Mr', 'JacquesD', '$2y$10$G8a1JQ0zbcJ9kvcnK7PEmeDuoyXfXpyhBJaxHfaR0BKoxo9hHNsnG', './img/compte/profile_jacquesd.jpg', 'jacques.dubois@orange.com', '06 56 78 90 12', 13),
('Moreau', 'Camille', '1959-04-17', 'Mme', 'CamilleM', '$2y$10$ZoN3zC0CCtjt6F.7e6xTiOdyzL24G.tGDKqyqf8tB2wjZGNv.0e7G', './img/compte/profile_camillem.jpg', 'camille.moreau@gmail.com', '06 67 89 01 23', 14),

('Petit', 'Louis', '1962-07-08', 'Mr', 'LouisP', '$2y$10$h8Z1E0u8pz8fmluTIMmVCeCatHuYZDDrBG72PtoCYq3pIoeVhju.y', './img/compte/profile_louisp.jpg', 'louis.petit@yahoo.com', '06 78 90 12 34', 15);
INSERT INTO _compte_client (id) VALUES 
(1), (2), (3);

INSERT INTO _compte_admin (id) VALUES (7);

INSERT INTO _carte_identite (piece_id_recto, piece_id_verso, valide) VALUES 
('/piece/4/recto.png', '/img/piece/4/verso.png', true),
('/piece/5/recto.png', '/img/piece/5/verso.png', true),
('/piece/6/recto.png', '/img/piece/6/verso.png', true);

INSERT INTO _compte_proprietaire (id, id_identite, IBAN, BIC, Titulaire) VALUES 
(4, 1, 'FR7612345678901234567890126', 'AGRIFRPPXXX', 'Bernard Sophie'),
(5, 2, 'FR7612345678901234567890127', 'AGRIFRPPXXX', 'Dubois Jacques'),
(6, 3, 'FR7612345678901234567890128', 'AGRIFRPPXXX', 'Moreau Camille');

INSERT INTO _langue_proprietaire(id_proprietaire, id_langue) VALUES
(4, 1), (4, 2), (4, 3), -- Propriétaire 1 (Français, Anglais, Allemand)
(5, 1), (5, 4), (5, 5), -- Propriétaire 2 (Français, Espagnol, Italien)
(6, 1), (6, 6), (6, 7); -- Propriétaire 3 (Français, Portugais, Mendarin)

INSERT INTO _logement(titre, description, accroche, base_tarif, surface, nb_max_personne, nb_chambre, nb_lit_double, nb_lit_simple, periode_preavis, en_ligne, id_proprietaire, id_adresse, id_categorie, id_type, duree_min_res, delai_avant_res) VALUES
('Appartement cosy en centre-ville', 'Un appartement confortable et bien situé au centre-ville, parfait pour une ou deux personnes. Ce logement offre un espace de vie moderne avec une cuisine entièrement équipée, une salle de bain rénovée et une chambre lumineuse avec un grand lit. Idéal pour les voyageurs souhaitant découvrir la ville à pied, tout en profitant du confort d’un intérieur douillet.', 'Réservez votre séjour en centre-ville.', 75, 25, 2, 1, 0, 2, 3, true, 4, 1, 1, 2, 1, 1),
('Maison spacieuse à la campagne', 'Une grande maison avec jardin à la campagne, idéale pour des vacances en famille. Cette maison comprend plusieurs chambres spacieuses, une cuisine moderne, une salle à manger conviviale et un salon confortable avec cheminée. Le jardin privé est parfait pour les barbecues et les jeux en plein air. À proximité, vous trouverez des sentiers de randonnée et des attractions locales pour toute la famille.', 'Vivez la campagne en grand.', 135, 120, 6, 3, 2, 2, 3, true, 5, 2, 2, 4, 1, 0),
('Villa d''exception avec vue sur mer', 'Une magnifique villa avec vue panoramique sur la mer, offrant luxe et confort. La villa dispose de grandes baies vitrées pour profiter de la vue, une piscine à débordement, et plusieurs terrasses pour se détendre. À l’intérieur, vous trouverez des équipements haut de gamme, des chambres élégantes, et une cuisine gastronomique. Idéale pour des vacances de luxe en bord de mer, avec un accès facile aux plages et aux restaurants.', 'Luxe et vue sur mer.', 300, 150, 8, 4, 2, 6, 5, true, 6, 3, 3, 1, 2, 2),
('Chalet de montagne authentique', 'Un chalet traditionnel en bois, situé dans une station de ski populaire. Le chalet combine charme rustique et commodités modernes, avec une grande cheminée, un espace spa avec sauna, et une cuisine équipée. Les chambres sont confortables avec des lits douillets, et le salon offre une vue imprenable sur les montagnes. Parfait pour les amateurs de ski et de nature, avec des pistes accessibles directement depuis le chalet.', 'Profitez de l''authenticité de la montagne.', 320, 110, 10, 5, 2, 8, 5, true, 4, 4, 4, 6, 1, 1),
('Bateau de charme sur la Seine', 'Un bateau confortable amarré sur la Seine, idéal pour un séjour romantique. Ce bateau offre une expérience unique avec une vue sur la ville depuis l’eau. Les aménagements incluent une chambre cosy, un salon avec grandes fenêtres, et une petite cuisine équipée. Profitez des soirées sur le pont, en admirant les lumières de la ville et en vous détendant au son de l’eau.', 'Séjour romantique sur la Seine.', 200, 30, 4, 2, 1, 2, 5, true, 5, 5, 5, 3, 1, 1),
('Logement insolite dans une cabane', 'Une cabane unique en pleine nature, parfaite pour un séjour dépaysant. Construite en bois avec des matériaux écologiques, cette cabane offre un espace de vie confortable avec une petite cuisine, une salle de bain moderne, et une chambre avec vue sur la forêt. Idéale pour ceux qui cherchent à se reconnecter avec la nature, avec des sentiers de randonnée et un environnement paisible.', 'Séjournez dans une cabane insolite.', 150, 20, 2, 1, 1, 1, 3, true, 6, 6, 6, 7, 1, 1),
('Appartement moderne avec balcon', 'Un appartement moderne avec un grand balcon et une vue sur la ville. L’intérieur est décoré avec goût et offre tout le confort nécessaire, incluant une cuisine équipée, un salon lumineux, et une chambre avec un lit king-size. Le balcon est parfait pour prendre un café le matin ou un verre le soir en admirant le coucher de soleil. Situé proche des commerces et des transports en commun, cet appartement est idéal pour les voyageurs urbains.', 'Vue imprenable sur la ville.', 90, 28, 4, 1, 2, 2, 3, false, 5, 7, 1, 5, 1, 1),
('Maison familiale avec piscine', 'Maison spacieuse avec piscine privée, idéale pour des vacances en famille. La maison dispose de plusieurs chambres, d’une cuisine moderne et équipée, d’un salon confortable, et d’une grande salle à manger. Le jardin est équipé d’un barbecue et d’une aire de jeux pour enfants. La piscine est chauffée et sécurisée, parfaite pour se rafraîchir durant les journées chaudes. Située dans un quartier calme, mais proche des attractions locales.', 'Profitez de la piscine privée.', 160, 100, 8, 4, 3, 4, 3, false, 5, 8, 2, 7, 1, 1);

INSERT INTO _avis(commentaire, note, id_logement, id_client) VALUES
('Très bel appartement avec vue sur la ville. Nous avons passé un excellent séjour !', 4.5, 1, 2);

INSERT INTO _reponse_avis(commentaire, id_avis) VALUES
('Merci pour votre commentaire positif, nous sommes ravis que vous ayez apprécié votre séjour !', 1);

INSERT INTO _signalement(commentaire, id_avis) VALUES
('L''avis semble contenir des informations mensongères.', 1);

INSERT INTO _activite_logement(id_logement, activite, id_distance) VALUES 
(1, 'Baignade', 1),
(1, 'Voile', 2),
(1, 'Canoë', 3),
(1, 'Golf', 4),
(1, 'Équitation', 5),
(2, 'Accrobranche', 1),
(2, 'Randonnée', 2),
(2, 'Plage', 3),
(3, 'Baignade', 1),
(3, 'Golf', 2),
(3, 'Équitation', 3),
(4, 'Canoë', 1),
(4, 'Randonnée', 2),
(4, 'Plage', 3),
(5, 'Voile', 1),
(5, 'Accrobranche', 2),
(5, 'Équitation', 3),
(6, 'Baignade', 1),
(6, 'Golf', 2),
(6, 'Accrobranche', 3),
(7, 'Randonnée', 1),
(7, 'Plage', 2),
(7, 'Baignade', 3),
(8, 'Voile', 1),
(8, 'Équitation', 2),
(8, 'Canoë', 3);

INSERT INTO _amenagement_logement (id_logement, id_amenagement) VALUES
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6), (1, 7), (1, 8),
(2, 2), (2, 3), (2, 4), (2, 5), (2, 6), (2, 7), (2, 8), (2, 9),
(3, 3), (3, 4), (3, 5), (3, 6), (3, 7), (3, 8), (3, 9), (3, 10),
(4, 4), (4, 5), (4, 6), (4, 7), (4, 8), (4, 9), (4, 10), (4, 11),
(5, 5), (5, 6), (5, 7), (5, 8), (5, 9), (5, 10), (5, 11), (5, 12),
(6, 6), (6, 7), (6, 8), (6, 9), (6, 10), (6, 11), (6, 12),
(7, 7), (7, 8), (7, 9), (7, 10), (7, 11), (7, 12),
(8, 8), (8, 9), (8, 10), (8, 11), (8, 12);

INSERT INTO _reservation (date_reservation, date_debut, date_fin, nb_occupant, taxe_sejour, taxe_commission, prix_ht, prix_ttc, date_annulation, annulation, id_logement, id_client) VALUES
('2024-05-15', '2024-07-15', '2024-07-22', 6, 20.00, 15.00, 1200.00, 1400.00, NULL, false, 1, 1),
('2024-05-15', '2024-08-15', '2024-08-22', 6, 20.00, 15.00, 1200.00, 1400.00, NULL, false, 1, 1),
('2024-05-15', '2024-06-29', '2024-07-05', 6, 20.00, 15.00, 1200.00, 1400.00, NULL, false, 1, 2),
('2024-05-15', '2024-06-10', '2024-01-17', 4, 32.00, 12.00, 1200.00, 1244.00, NULL, false, 2, 3),
('2024-05-15', '2024-06-05', '2024-02-15', 3, 33.00, 15.00, 1500.00, 1548.00, '2023-12-10', true, 3, 3);

INSERT INTO _reservation_prix_par_nuit (prix, nb_nuit, id_reservation)
SELECT
    (EXTRACT(DAY FROM AGE(_reservation.date_fin, _reservation.date_debut)) * _logement.base_tarif) AS prix,
    EXTRACT(DAY FROM AGE(_reservation.date_fin, _reservation.date_debut)) AS nb_nuit,
    _reservation.id AS id_reservation
FROM
    _reservation
JOIN
    _logement ON _reservation.id_logement = _logement.id;

INSERT INTO _calendrier(date, id_logement, prix, statut)
SELECT
    _reservation.date_debut + n * INTERVAL '1 DAY' AS date,
    _reservation.id_logement AS id_logement,
    _logement.base_tarif AS prix,
    'R' AS statut
FROM
    _reservation
JOIN
    _logement ON _reservation.id_logement = _logement.id
JOIN
    generate_series(0, CAST(EXTRACT(DAY FROM AGE(_reservation.date_fin, _reservation.date_debut)) AS INTEGER)) AS n
ON
    (_reservation.date_debut + n * INTERVAL '1 DAY') <= _reservation.date_fin;
