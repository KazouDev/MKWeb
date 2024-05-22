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

INSERT INTO _amenagement (amenagement)
VALUES 
('Piscine'), ('Climatisation'), ('Barbecue'), ('Lave-Linge'), ('Ping-Pong'), ('Beer-Pong'), ('Baby-Foot'), ('Lave-Vaiselle'), ('Four'), ('Micro-Onde');

INSERT INTO _adresse (pays, region, departement, ville, rue)
VALUES
('France', 'Île-de-France', 'Paris', 'Paris', '1 Rue du Faubourg Saint-Honoré'),
('France', 'Auvergne-Rhône-Alpes', 'Rhône', 'Lyon', '2 Avenue des Champs-Élysées'),
('France', 'Provence-Alpes-Côte d"Azur', 'Bouches-du-Rhône', 'Marseille', '3 Boulevard Saint-Germain'),
('France', 'Île-de-France', 'Departement de Paris', 'Paris', '4 Boulevard Saint-Germain'),
('France', 'Bretagne', 'Ille-et-Vilaine', 'Rennes', '5 Avenue de la République'),
('France', 'Bretagne', 'Finistère', 'Brest', '6 Rue de la Liberté'),
('France', 'Bretagne', 'Morbihan', 'Vannes', '7 Rue des Capitouls'),
('France', 'Bretagne', 'Côtes-d"Armor', 'Saint-Brieuc', '8 Rue de la Mairie');

INSERT INTO _utilisateur (nom, prenom, date_naissance, civilite, pseudo, mot_de_passe, photo_profile, email, telephone, id_adresse) 
VALUES 
('Lefevre', 'Sophie', '1990-11-07', 'Mme', 'SophieLef', '$2y$12$uwSKgb4oHcXPBteO3Vz3lO7Sjinlda1rQ/JX.rR0XyRBRX2uXFW9e', '/img/compte/sophie.jpg', 'sophie.lefevre@gmail.com', '0678901234', 1),
('Baptiste', 'Jean', '1987-09-15', 'Mr', 'JB1987', '$2y$12$NQVqgljWIIfzI.Moi3vG.ODcrki9uSRR98HvKwRgAr5VSfQ524/PK', '/img/compte/jean.jpg', 'jean.baptiste@gmail.com', '0698765432', 2),
('Dubois', 'Marie', '2000-06-18', 'Mme', 'MarieD', '$2y$12$QrmUnkU5D8GP3FErKET0suHFMnQ5hkNueEE3IIOZaDG0QRA4snrXu', '/img/compte/marie.jpg', 'marie.dubois@gmail.com', '0623456789', 3);

INSERT INTO _utilisateur (nom, prenom, date_naissance, civilite, pseudo, mot_de_passe, photo_profile, email, telephone, id_adresse) 
VALUES 
('Dupuis', 'Emma', '1975-09-22', 'Mme', 'EmmaD', '$2y$12$2Rna9n68jFqjPNsP/pKgyuPXNsjQoyAswG6WwDEcK.YuwNBFux9LW', '/img/compte/emma.jpg', 'emma.dupuis@gmail.com', '0678901234', 4);

INSERT INTO _utilisateur (nom, prenom, date_naissance, civilite, pseudo, mot_de_passe, photo_profile, email, telephone, id_adresse) 
VALUES 
('Leclerc', 'Pierre', '1983-12-10', 'Mr', 'PierreLeclerc', '$2y$12$mWBi5LnEajEatHcKqXHp9eAk4lqNXEmnn8lVcTjfFhRXXRTkHFOxy', '/img/compte/pierre.jpg', 'pierre.leclerc@gmail.com', '0678901234', 5),
('Durand', 'Marie', '1990-06-25', 'Mme', 'MarieD', '$2y$12$XTeyD.U5pwOEvpKREoDdjuRezdcddiYedsjgCdlsm6KhOHoeBEwzW', '/img/compte/marie.jpg', 'marie.durand@gmail.com', '0612345678', 6),
('Girard', 'Paul', '1978-08-20', 'Mr', 'PaulG', '$2y$12$H2Vag2nlVMOyzfd0D3xRluNdkM0IigMBsdkJmR.eDzGXHawhefhdO', '/img/compte/paul.jpg', 'paul.girard@gmail.com', '0678904567', 7),
('Martin', 'Luc', '1972-03-28', 'Mme', 'LucM', '$2y$12$0/fP5Kw4u/za805iegm0.uF6flHQTwr1FRdjt2Lm9ZcNyyiHQaExS', '/img/compte/luc.jpg', 'luc.martin@gmail.com', '0678901357', 8);

INSERT INTO _compte_client (id) VALUES (1), (2), (3);

INSERT INTO _compte_admin (id) VALUES 
(21), (22);

INSERT INTO _compte_proprietaire (id, piece_id_recto, piece_id_verso, IBAN, BIC, Titulaire) 
VALUES 
(5 ,'/img/piece/ID_Leclerc_recto.jpg', '/img/piece/ID_Leclerc_verso.jpg', 'FR7612345678901234567890123', 'AGRIFRPPXXX', 'Pierre Leclerc'),
(6, '/img/piece/ID_Durand_recto.jpg', '/img/piece/ID_Durand_verso.jpg', 'FR7612345678901234567890456', 'BNPFRPPXXX', 'Marie Durand'),
(7, '/img/piece/ID_Girard_recto.jpg', '/img/piece/ID_Girard_verso.jpg', 'FR7612345678901234567890789', 'CEPAFRPPXXX', 'Paul Girard'),
(8, '/img/piece/ID_Martin_recto.jpg', '/img/piece/ID_Martin_verso.jpg', 'FR7612345678901234567890000', 'ABCDFRPPXXX', 'Luc Martin');

INSERT INTO _langue_proprietaire(id_proprietaire, id_langue) VALUES
(1, 1), (1, 2), (1, 3), -- Propriétaire 1 (Français, Anglais, Allemand)
(2, 1), (2, 4), (2, 5), -- Propriétaire 2 (Français, Espagnol, Italien)
(3, 1), (3, 6), (3, 7), -- Propriétaire 3 (Français, Portugais, Mendarin)
(4, 1), (4, 8), (4, 9), -- Propriétaire 4 (Français, Hindi, Arabe)
(5, 1), (5, 10), (5, 11), -- Propriétaire 5 (Français, Ukrainien, Russe)
(6, 1), (6, 2), -- Propriétaire 6 (Français, Anglais)
(7, 1), (7, 3), -- Propriétaire 7 (Français, Allemand)
(8, 1), (8, 4), -- Propriétaire 8 (Français, Espagnol)
(9, 1), (9, 5), -- Propriétaire 9 (Français, Italien)
(10, 1), (10, 6), -- Propriétaire 10 (Français, Portugais)
(11, 1), (11, 7), -- Propriétaire 11 (Français, Mendarin)
(12, 1), (12, 8), -- Propriétaire 12 (Français, Hindi)
(13, 1), (13, 9), -- Propriétaire 13 (Français, Arabe)
(14, 1), (14, 10), -- Propriétaire 14 (Français, Ukrainien)
(15, 1), (15, 11), -- Propriétaire 15 (Français, Russe)
(16, 1), -- Propriétaire 16 (Français)
(17, 1), -- Propriétaire 17 (Français)
(18, 1), -- Propriétaire 18 (Français)
(19, 1), -- Propriétaire 19 (Français)
(20, 1); -- Propriétaire 20 (Français)

INSERT INTO _logement(titre, description, accroche, base_tarif, surface, nb_max_personne, nb_chambre, nb_lit_double, nb_lit_simple, periode_preavis, en_ligne, id_proprietaire, id_adresse, id_categorie, id_type)
VALUES
('Villa avec piscine', 'Belle villa avec piscine dans un quartier calme. Vue magnifique sur la campagne.', 'Vivez des vacances de rêve dans cette villa spacieuse avec piscine.', 150, 200, 8, 4, 3, 2, 30, true, 5,5, 3, 5),
('Maison en pierre avec piscine', 'Charmante maison en pierre rénovée avec piscine privée. Idéale pour des vacances en famille ou entre amis.', 'Profitez du charme de cette maison en pierre avec piscine et jardin arboré.', 180, 180, 6, 3, 2, 2, 45, false, 6,6, 2, 4),
('Appartement cosy', 'Appartement cosy en plein cœur de la ville. Parfait pour découvrir la vie locale.', 'Découvrez le charme de la ville en séjournant dans cet appartement confortable.', 80, 70, 4, 2, 1, 1, 15, true, 7,7, 1, 1);

INSERT INTO _avis(commentaire, note, id_logement, id_client) VALUES
('Très bel appartement avec vue sur la ville. Nous avons passé un excellent séjour !', 4.5, 1, 23),
('Le logement était sale à notre arrivée, ce qui a été décevant.', 2, 1, 24),
('Villa magnifique avec piscine privée. Vacances de rêve assurées !', 5, 1, 25),
('Nous avons eu des problèmes d''eau chaude pendant notre séjour, dommage.', 2, 1, 26),
('Superbe chalet en montagne avec vue panoramique. Nous reviendrons !', 4.5, 2, 27),
('Le chalet manquait d''équipements de cuisine, ce qui a été un inconvénient.', 3, 2, 28),
('Maison de vacances agréable avec jardin. Parfait pour se détendre !', 4, 2, 29),
('Nous avons trouvé la literie inconfortable, cela a été un point négatif.', 2, 2, 30),
('Bateau de croisière luxueux avec tout le confort nécessaire. Expérience inoubliable !', 5, 3, 31),
('Le bateau avait des problèmes de climatisation, ce qui a été gênant.', 3, 3, 32),
('Appartement moderne avec vue sur la mer. Très bien situé !', 4, 3, 33),
('Nous avons eu des soucis avec la connexion Wi-Fi, pas pratique.', 2, 3, 34),
('Villa avec piscine et jardin privatifs. Parfait pour des vacances en famille !', 5, 4, 35),
('La villa était mal isolée, nous avons eu froid pendant la nuit.', 2, 4, 36),
('Chalet cosy avec cheminée. Ambiance chaleureuse garantie !', 4.5, 4, 37),
('Nous avons été déçus par la propreté du chalet, cela aurait pu être mieux.', 3, 4, 38),
('Appartement lumineux avec terrasse. Nous avons passé un séjour très agréable !', 4, 5, 39),
('Le logement était bruyant la nuit, ce qui a perturbé notre sommeil.', 3, 5, 40),
('Villa de luxe avec vue sur la montagne. Endroit parfait pour se ressourcer !', 5, 5, 41),
('La villa manquait d''entretien, certains équipements étaient défectueux.', 2, 5, 42),
('Magnifique chalet en bord de lac. Cadre idyllique pour des vacances reposantes !', 4.5, 6, 23),
('Nous avons été dérangés par des insectes dans le chalet, pas très agréable.', 2, 6, 24),
('Appartement bien situé mais mal insonorisé, nous avons été dérangés par le bruit extérieur.', 3, 6, 25),
('Le logement était propre mais le mobilier était vieillissant.', 3, 6, 26),
('Villa d''exception avec piscine à débordement. Vacances de luxe assurées !', 5, 7, 27),
('La villa manquait d''équipements de loisirs, surtout pour le prix demandé.', 3, 7, 28),
('Bateau de croisière spacieux et confortable. Expérience de navigation unique !', 4.5, 7, 29),
('Nous avons eu des problèmes avec la climatisation du bateau, ce qui a été gênant.', 2, 7, 30),
('Appartement avec vue imprenable sur la mer. Emplacement idéal pour des vacances !', 4, 8, 31),
('Le logement était sale à notre arrivée, un effort aurait été apprécié.', 2, 8, 32),
('Villa de charme avec jardin exotique. Nous avons passé un séjour enchanteur !', 4.5, 8, 33),
('Nous avons été déçus par l''absence de propreté dans la villa.', 2, 8, 34),
('Chalet cosy avec vue sur la montagne. Séjour reposant assuré !', 4, 9, 35),
('Le chalet manquait d''équipements de cuisine, surtout pour une location à la montagne.', 3, 9, 36),
('Maison insolite avec décoration originale. Expérience unique à vivre !', 5, 9, 37),
('Nous avons trouvé le logement mal isolé, surtout en hiver.', 3, 9, 38),
('Villa avec piscine privée et vue sur la mer. Paradis sur terre !', 5, 10, 39),
('La villa manquait d''entretien, certains équipements étaient défectueux.', 2, 10, 40),
('Appartement moderne et bien équipé. Nous avons passé un excellent séjour !', 4, 10, 41),
('Le logementétait bruyant la nuit, ce qui a perturbé notre sommeil.', 2.5, 10, 42),
('Magnifique chalet en bois avec vue sur la forêt. Atmosphère cosy garantie !', 4.5, 11, 23),
('Nous avons été gênés par des problèmes d''humidité dans le chalet.', 2, 11, 24),
('Appartement bien situé mais mal entretenu. Déçu de la propreté.', 3, 11, 25),
('Le logement était propre mais le mobilier était vétuste.', 3, 11, 26),
('Villa d''exception avec piscine à débordement. Vacances de luxe assurées !', 5, 12, 27),
('La villa manquait d''équipements de loisirs, surtout pour le prix demandé.', 3, 12, 28),
('Bateau de croisière spacieux et confortable. Expérience de navigation unique !', 4.5, 12, 29),
('Nous avons eu des problèmes avec la climatisation du bateau, ce qui a été gênant.', 2, 12, 30),
('Appartement avec vue imprenable sur la mer. Emplacement idéal pour des vacances !', 4, 13, 31),
('Le logement était sale à notre arrivée, un effort aurait été apprécié.', 2, 13, 32),
('Villa de charme avec jardin exotique. Nous avons passé un séjour enchanteur !', 4.5, 13, 33),
('Nous avons été déçus par l''absence de propreté dans la villa.', 2, 13, 34),
('Chalet cosy avec vue sur la montagne. Séjour reposant assuré !', 4, 14, 35),
('Le chalet manquait d''équipements de cuisine, surtout pour une location à la montagne.', 3, 14, 36),
('Maison insolite avec décoration originale. Expérience unique à vivre !', 5, 14, 37),
('Nous avons trouvé le logement mal isolé, surtout en hiver.', 3, 14, 38),
('Villa avec piscine privée et vue sur la mer. Paradis sur terre !', 5, 15, 39),
('La villa manquait d''entretien, certains équipements étaient défectueux.', 2, 15, 40),
('Appartement moderne et bien équipé. Nous avons passé un excellent séjour !', 4, 15, 41),
('Le logement était bruyant la nuit, ce qui a perturbé notre sommeil.', 2.5, 15, 42),
('Magnifique chalet en bois avec vue sur la forêt. Atmosphère cosy garantie !', 4.5, 16, 23),
('Nous avons été gênés par des problèmes d''humidité dans le chalet.', 2, 16, 24),
('Appartement bien situé mais mal entretenu. Déçu de la propreté.', 3, 16, 25),
('Le logement était propre mais le mobilier était vétuste.', 3, 16, 26),
('Villa d''exception avec piscine à débordement. Vacances de luxe assurées !', 5, 17, 27),
('La villa manquait d''équipements de loisirs, surtout pour le prix demandé.', 3, 17, 28),
('Bateau de croisière spacieux et confortable. Expérience de navigation unique !', 4.5, 17, 29),
('Nous avons eu des problèmes avec la climatisation du bateau, ce qui a été gênant.', 2, 17, 30),
('Appartement avec vue imprenable sur la mer. Emplacement idéal pour des vacances !', 4, 18, 31),
('Le logement était sale à notre arrivée, un effort aurait été apprécié.', 2, 18, 32),
('Villa de charme avec jardin exotique. Nous avons passé un séjour enchanteur !', 4.5, 18, 33),
('Nous avons été déçus par l''absence de propreté dans la villa.', 2, 18, 34),
('Chalet cosy avec vue sur la montagne. Séjour reposant assuré !', 4, 19, 35),
('Le chalet manquait d''équipements de cuisine, surtout pour une location à la montagne.', 3, 19, 36),
('Maison insolite avec décoration originale. Expérience unique à vivre !', 5, 19, 37),
('Nous avons trouvé le logement mal isolé, surtout en hiver.', 3, 19, 38),
('Villa avec piscine privée et vue sur la mer. Paradis sur terre !', 5, 20, 39),
('La villa manquait d''entretien, certains équipements étaient défectueux.', 2, 20, 40),
('Appartement moderne et bien équipé. Nous avons passé un excellent séjour !', 4, 20, 41),
('Le logement était bruyant la nuit, ce qui a perturbé notre sommeil.', 2.5, 20, 42),
('Magnifique chalet en bois avec vue sur la forêt. Atmosphère cosy garantie !', 4.5, 21, 23),
('Nous avons été gênés par des problèmes d''humidité dans le chalet.', 2, 21, 24),
('Appartement bien situé mais mal entretenu. Déçu de la propreté.', 3, 21, 25),
('Le logement était propre mais le mobilier était vétuste.', 3, 21, 26),
('Villa d''exception avec piscine à débordement. Vacances de luxe assurées !', 5, 22, 27),
('La villa manquait d''équipements de loisirs, surtout pour le prix demandé.', 3, 22, 28),
('Bateau de croisière spacieux et confortable. Expérience de navigation unique !', 4.5, 22, 29),
('Nous avons eu des problèmes avec la climatisation du bateau, ce qui a été gênant.', 2, 22, 30),
('Appartement avec vue imprenable sur la mer. Emplacement idéal pour des vacances !', 4, 23, 31),
('Le logement était sale à notre arrivée, un effort aurait été apprécié.', 2, 23, 32),
('Villa de charme avec jardin exotique. Nous avons passé un séjour enchanteur !', 4.5, 23, 33),
('Nous avons été déçus par l''absence de propreté dans la villa.', 2, 23, 34),
('Chalet cosy avec vue sur la montagne. Séjour reposant assuré !', 4, 24, 35),
('Le chalet manquait d''équipements de cuisine, surtout pour une location à la montagne.', 3, 24, 36),
('Maison insolite avec décoration originale. Expérience unique à vivre !', 5, 24, 37),
('Nous avons trouvé le logement mal isolé, surtout en hiver.', 3, 24, 38),
('Villa avec piscine privée et vue sur la mer. Paradis sur terre !', 5, 25, 39),
('La villa manquait d''entretien, certains équipements étaient défectueux.', 2, 25, 40),
('Appartement moderne et bien équipé. Nous avons passé un excellent séjour !', 4, 25, 41),
('Le logement était bruyant la nuit, ce qui a perturbé notre sommeil.', 2.5, 25, 42),
('Magnifique chalet en bois avec vue sur la forêt. Atmosphère cosy garantie !', 4.5, 26, 23),
('Nous avons été gênés par des problèmes d''humidité dans le chalet.', 2, 26, 24),
('Appartement bien situé mais mal entretenu. Déçu de la propreté.', 3, 26, 25),
('Le logement était propre mais le mobilier était vétuste.', 3, 26, 26),
('Villa d''exception avec piscine à débordement. Vacances de luxe assurées !', 5, 27, 27),
('La villa manquait d''équipements de loisirs, surtout pour le prix demandé.', 3, 27, 28),
('Bateau de croisière spacieux et confortable. Expérience de navigation unique !', 4.5, 27, 29),
('Nous avons eu des problèmes avec la climatisation du bateau, ce qui a été gênant.', 2, 27, 30),
('Appartement avec vue imprenable sur la mer. Emplacement idéal pour des vacances !', 4, 28, 31),
('Le logement était sale à notre arrivée, un effort aurait été apprécié.', 2, 28, 32),
('Villa de charme avec jardin exotique. Nous avons passé un séjour enchanteur !', 4.5, 28, 33),
('Nous avons été déçus par l''absence de propreté dans la villa.', 2, 28, 34),
('Chalet cosy avec vue sur la montagne. Séjour reposant assuré !', 4, 29, 35),
('Le chalet manquait d''équipements de cuisine, surtout pour une location à la montagne.', 3, 29, 36),
('Maison insolite avec décoration originale. Expérience unique à vivre !', 5, 29, 37),
('Nous avons trouvé le logement mal isolé, surtout en hiver.', 3, 29, 38),
('Villa avec piscine privée et vue sur la mer. Paradis sur terre !', 5, 30, 39),
('La villa manquait d''entretien, certains équipements étaient défectueux.', 2, 30, 40),
('Appartement moderne et bien équipé. Nous avons passé un excellent séjour !', 4, 30, 41),
('Le logement était bruyant la nuit, ce qui a perturbé notre sommeil.', 2.5, 30, 42),
('Magnifique chalet en bois avec vue sur la forêt. Atmosphère cosy garantie !', 4.5, 31, 23),
('Nous avons été gênés par des problèmes d''humidité dans le chalet.', 2, 31, 24),
('Appartement bien situé mais mal entretenu. Déçu de la propreté.', 3, 31, 25),
('Le logement était propre mais le mobilier était vétuste.', 3, 31, 26),
('Villa d''exception avec piscine à débordement. Vacances de luxe assurées !', 5, 32, 27),
('La villa manquait d''équipements de loisirs, surtout pour le prix demandé.', 3, 32, 28),
('Bateau de croisière spacieux et confortable. Expérience de navigation unique !', 4.5, 32, 29),
('Nous avons eu des problèmes avec la climatisation du bateau, ce qui a été gênant.', 2, 32, 30),
('Appartement avec vue imprenable sur la mer. Emplacement idéal pour des vacances !', 4, 33, 31),
('Le logement était sale à notre arrivée, un effort aurait été apprécié.', 2, 33, 32),
('Villa de charme avec jardin exotique. Nous avons passé un séjour enchanteur !', 4.5, 33, 33),
('Nous avons été déçus par l''absence de propreté dans la villa.', 2, 33, 34),
('Chalet cosy avec vue sur la montagne. Séjour reposant assuré !', 4, 34, 35),
('Le chalet manquait d''équipements de cuisine, surtout pour une location à la montagne.', 3, 34, 36),
('Maison insolite avec décoration originale. Expérience unique à vivre !', 5, 34, 37),
('Nous avons trouvé le logement mal isolé, surtout en hiver.', 3, 34, 38),
('Villa avec piscine privée et vue sur la mer. Paradis sur terre !', 5, 35, 39),
('La villa manquait d''entretien, certains équipements étaient défectueux.', 2, 35, 40),
('Appartement moderne et bien équipé. Nous avons passé un excellent séjour !', 4, 35, 41),
('Le logement était bruyant la nuit, ce qui a perturbé notre sommeil.', 2.5, 35, 42),
('Magnifique chalet en bois avec vue sur la forêt. Atmosphère cosy garantie !', 4.5, 36, 23),
('Nous avons été gênés par des problèmes d''humidité dans le chalet.', 2, 36, 24),
('Appartement bien situé mais mal entretenu. Déçu de la propreté.', 3, 36, 25),
('Le logement était propre mais le mobilier était vétuste.', 3, 36, 26),
('Villa d''exception avec piscine à débordement. Vacances de luxe assurées !', 5, 37, 27),
('La villa manquait d''équipements de loisirs, surtout pour le prix demandé.', 3, 37, 28),
('Bateau de croisière spacieux et confortable. Expérience de navigation unique !', 4.5, 37, 29),
('Nous avons eu des problèmes avec la climatisation du bateau, ce qui a été gênant.', 2, 37, 30),
('Appartement avec vue imprenable sur la mer. Emplacement idéal pour des vacances !', 4, 38, 31),
('Le logement était sale à notre arrivée, un effort aurait été apprécié.', 2, 38, 32),
('Villa de charme avec jardin exotique. Nous avons passé un séjour enchanteur !', 4.5, 38, 33),
('Nous avons été déçus par l''absence de propreté dans la villa.', 2, 38, 34),
('Chalet cosy avec vue sur la montagne. Séjour reposant assuré !', 4, 39, 35),
('Le chalet manquait d''équipements de cuisine, surtout pour une location à la montagne.', 3, 39, 36),
('Maison insolite avec décoration originale. Expérience unique à vivre !', 5, 39, 37),
('Nous avons trouvé le logement mal isolé, surtout en hiver.', 3, 39, 38),
('Villa avec piscine privée et vue sur la mer. Paradis sur terre !', 5, 40, 39),
('La villa manquait d''entretien, certains équipements étaient défectueux.', 2, 40, 40),
('Appartement moderne et bien équipé. Nous avons passé un excellent séjour !', 4, 40, 41),
('Le logement était bruyant la nuit, ce qui a perturbé notre sommeil.', 2.5, 40, 42),
('Magnifique chalet en bois avec vue sur la forêt. Atmosphère cosy garantie !', 4.5, 41, 23),
('Nous avons été gênés par des problèmes d''humidité dans le chalet.', 2, 41, 24),
('Appartement bien situé mais mal entretenu. Déçu de la propreté.', 3, 41, 25),
('Le logement était propre mais le mobilier était vétuste.', 3, 41, 26),
('Villa d''exception avec piscine à débordement. Vacances de luxe assurées !', 5, 42, 27),
('La villa manquait d''équipements de loisirs, surtout pour le prix demandé.', 3, 42, 28),
('Bateau de croisière spacieux et confortable. Expérience de navigation unique !', 4.5, 42, 29),
('Nous avons eu des problèmes avec la climatisation du bateau, ce qui a été gênant.', 2, 42, 30);

INSERT INTO _reponse_avis(commentaire, id_avis) VALUES
('Merci pour votre commentaire positif, nous sommes ravis que vous ayez apprécié votre séjour !', 6),
('Nous sommes désolés que vous ayez rencontré des problèmes, nous allons faire de notre mieux pour les résoudre.', 12),
('Nous sommes navrés que votre expérience n''ait pas été à la hauteur de vos attentes.', 18),
('Nous prendrons note de vos suggestions pour améliorer notre service.', 24),
('Nous sommes enchantés que votre séjour ait été à la hauteur de vos attentes !', 30),
('Nous prenons en compte vos remarques et ferons tout notre possible pour nous améliorer.', 36),
('Nous sommes heureux que vous ayez apprécié votre croisière à bord de notre bateau.', 42),
('Nous sommes désolés des désagréments que vous avez rencontrés, nous allons enquêter sur ce problème.', 48),
('Merci pour votre commentaire positif, nous sommes ravis que vous ayez apprécié votre séjour !', 54),
('Nous sommes désolés que vous ayez rencontré des problèmes, nous allons faire de notre mieux pour les résoudre.', 60),
('Nous sommes navrés que votre expérience n''ait pas été à la hauteur de vos attentes.', 66),
('Nous prendrons note de vos suggestions pour améliorer notre service.', 72),
('Nous sommes enchantés que votre séjour ait été à la hauteur de vos attentes !', 78),
('Nous prenons en compte vos remarques et ferons tout notre possible pour nous améliorer.', 84),
('Nous sommes heureux que vous ayez apprécié votre croisière à bord de notre bateau.', 90),
('Nous sommes désolés des désagréments que vous avez rencontrés, nous allons enquêter sur ce problème.', 96),
('Merci pour votre commentaire positif, nous sommes ravis que vous ayez apprécié votre séjour !', 102),
('Nous sommes désolés que vous ayez rencontré des problèmes, nous allons faire de notre mieux pour les résoudre.', 108),
('Nous sommes navrés que votre expérience n''ait pas été à la hauteur de vos attentes.', 114),
('Nous prendrons note de vos suggestions pour améliorer notre service.', 120),
('Nous sommes enchantés que votre séjour ait été à la hauteur de vos attentes !', 126),
('Nous prenons en compte vos remarques et ferons tout notre possible pour nous améliorer.', 132),
('Nous sommes heureux que vous ayez apprécié votre croisière à bord de notre bateau.', 138),
('Nous sommes désolés des désagréments que vous avez rencontrés, nous allons enquêter sur ce problème.', 144),
('Merci pour votre commentaire positif, nous sommes ravis que vous ayez apprécié votre séjour !', 150),
('Nous sommes désolés que vous ayez rencontré des problèmes, nous allons faire de notre mieux pour les résoudre.', 156),
('Nous sommes navrés que votre expérience n''ait pas été à la hauteur de vos attentes.', 162),
('Nous prendrons note de vos suggestions pour améliorer notre service.', 168);

INSERT INTO _signalement(commentaire, id_avis) VALUES
('L''avis semble contenir des informations mensongères.', 2),
('Il semble y avoir des inexactitudes dans cet avis.', 4),
('Nous avons reçu des informations contradictoires concernant cet avis.', 6),
('Ce commentaire ne correspond pas à notre expérience avec ce client.', 8),
('Nous avons des preuves que cet avis est trompeur.', 10),
('Cet avis semble être une tentative de nuire à notre réputation.', 12),
('Nous avons identifié des incohérences dans ce commentaire.', 14),
('Des informations incorrectes ont été fournies dans cet avis.', 16),
('Nous avons des raisons de croire que cet avis est mal intentionné.', 18),
('Ce commentaire semble être basé sur des informations erronées.', 20),
('Il semble y avoir des mensonges dans cet avis.', 22),
('Nous remettons en question la véracité de cet avis.', 24),
('Ce commentaire semble être biaisé.', 26),
('Des informations trompeuses ont été fournies dans cet avis.', 28),
('Nous suspectons que cet avis est frauduleux.', 30),
('Nous avons des doutes quant à l''authenticité de cet avis.', 32),
('Ce commentaire semble être une attaque injuste.', 34),
('Nous avons des raisons de croire que cet avis est partial.', 36),
('Nous pensons que cet avis est basé sur des préjugés.', 38),
('Ce commentaire ne reflète pas fidèlement notre service.', 40);

INSERT INTO _activite_logement(id_logement, activite, id_distance)
VALUES 
(1, 'Plage', 3),
(1, 'Karting', 2),
(2, 'Golf', 1),
(3, 'Bar (Chez TonTon)', 2);

INSERT INTO _amenagement_logement (id_logement, id_amenagement) VALUES
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6), (1, 7), (1, 8),
(2, 2), (2, 3), (2, 4), (2, 5), (2, 6), (2, 7), (2, 8), (2, 9),
(3, 3), (3, 4), (3, 5), (3, 6), (3, 7), (3, 8), (3, 9), (3, 10),
(4, 4), (4, 5), (4, 6), (4, 7), (4, 8), (4, 9), (4, 10), (4, 11),
(5, 5), (5, 6), (5, 7), (5, 8), (5, 9), (5, 10), (5, 11), (5, 12),
(6, 6), (6, 7), (6, 8), (6, 9), (6, 10), (6, 11), (6, 12),
(7, 7), (7, 8), (7, 9), (7, 10), (7, 11), (7, 12),
(8, 8), (8, 9), (8, 10), (8, 11), (8, 12),
(9, 9), (9, 10), (9, 11), (9, 12),
(10, 10), (10, 11), (10, 12),
(11, 11), (11, 12),
(12, 12),
(13, 1), (13, 2), (13, 3), (13, 4), (13, 5), (13, 6), (13, 7), (13, 8),
(14, 2), (14, 3), (14, 4), (14, 5), (14, 6), (14, 7), (14, 8), (14, 9),
(15, 3), (15, 4), (15, 5), (15, 6), (15, 7), (15, 8), (15, 9), (15, 10),
(16, 4), (16, 5), (16, 6), (16, 7), (16, 8), (16, 9), (16, 10), (16, 11),
(17, 5), (17, 6), (17, 7), (17, 8), (17, 9), (17, 10), (17, 11), (17, 12),
(18, 6), (18, 7), (18, 8), (18, 9), (18, 10), (18, 11), (18, 12),
(19, 7), (19, 8), (19, 9), (19, 10), (19, 11), (19, 12),
(20, 8), (20, 9), (20, 10), (20, 11), (20, 12),
(21, 9), (21, 10), (21, 11), (21, 12),
(22, 10), (22, 11), (22, 12),
(23, 11), (23, 12),
(24, 12),
(25, 1), (25, 2), (25, 3), (25, 4), (25, 5), (25, 6), (25, 7), (25, 8),
(26, 2), (26, 3), (26, 4), (26, 5), (26, 6), (26, 7), (26, 8), (26, 9),
(27, 3), (27, 4), (27, 5), (27, 6), (27, 7), (27, 8), (27, 9), (27, 10),
(28, 4), (28, 5), (28, 6), (28, 7), (28, 8), (28, 9), (28, 10), (28, 11),
(29, 5), (29, 6), (29, 7), (29, 8), (29, 9), (29, 10), (29, 11), (29, 12),
(30, 6), (30, 7), (30, 8), (30, 9), (30, 10), (30, 11), (30, 12),
(31, 7), (31, 8), (31, 9), (31, 10), (31, 11), (31, 12),
(32, 8), (32, 9), (32, 10), (32, 11), (32, 12),
(33, 9), (33, 10), (33, 11), (33, 12),
(34, 10), (34, 11), (34, 12),
(35, 11), (35, 12),
(36, 12),
(37, 1), (37, 2), (37, 3), (37, 4), (37, 5), (37, 6), (37, 7), (37, 8),
(38, 2), (38, 3), (38, 4), (38, 5), (38, 6), (38, 7), (38, 8), (38, 9),
(39, 3), (39, 4), (39, 5), (39, 6), (39, 7), (39, 8), (39, 9), (39, 10),
(40, 4), (40, 5), (40, 6), (40, 7), (40, 8), (40, 9), (40, 10), (40, 11),
(41, 5), (41, 6), (41, 7), (41, 8), (41, 9), (41, 10), (41, 11), (41, 12),
(42, 6), (42, 7), (42, 8), (42, 9), (42, 10), (42, 11), (42, 12),
(43, 7), (43, 8), (43, 9), (43, 10), (43, 11), (43, 12),
(44, 8), (44, 9), (44, 10), (44, 11), (44, 12),
(45, 9), (45, 10), (45, 11), (45, 12);

INSERT INTO _reservation (date_debut, date_fin, nb_occupant, taxe_sejour, taxe_commission, prix_ht, prix_ttc, date_annulation, annulation,id_logement,id_client)
VALUES
('2024-07-15', '2024-07-22', 6, 20.00, 15.00, 1200.00, 1400.00, NULL, false,1,1),
('2024-08-20', '2024-08-27', 3, 12.00, 9.00, 700.00, 805.00, '2024-08-18', true,2,3);

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
