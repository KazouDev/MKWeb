-- Table
DROP SCHEMA IF EXISTS sae CASCADE;
CREATE SCHEMA sae;

SET search_path TO sae;

CREATE TABLE _utilisateur (
  id SERIAL PRIMARY KEY,
  nom VARCHAR(255) NOT NULL,
  prenom VARCHAR(255) NOT NULL,
  pseudo VARCHAR(255) NOT NULL,
  date_naissance DATE NOT NULL,
  civilite VARCHAR(4) NOT NULL,
  mot_de_passe VARCHAR(255) NOT NULL,
  photo_profile VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  telephone VARCHAR(255) NOT NULL,
  id_adresse INT NOT NULL
);

CREATE TABLE _compte_proprietaire (
  id SERIAL PRIMARY KEY,
  piece_id_recto VARCHAR(255),
  piece_id_verso VARCHAR(255),
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
  ville VARCHAR(255) NOT NULL,
  rue VARCHAR(255) NOT NULL,
  complement_1 VARCHAR(255),
  complement_2 VARCHAR(255),
  complement_3 VARCHAR(255),
  lattitude FLOAT,
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
  date_debut DATE NOT NULL,
  date_fin DATE NOT NULL,
  nb_occupant FLOAT NOT NULL,
  taxe_sejour INT NOT NULL,
  taxe_commission INT NOT NULL,
  prix_ht INT NOT NULL,
  prix_ttc INT NOT NULL,
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
EXECUTE FUNCTION insert_cal_trigger();


-- PEUPLEMENT
INSERT INTO _categorie_logement (id, categorie) VALUES
(1, 'Appartement'),
(2, 'Maison'),
(3, 'Villa'),
(4, 'Chalet'),
(5, 'Bateau'),
(6, 'Logement insolite');

INSERT INTO _distance (id, perimetre) VALUES
(1, 'sur place'),
(2, 'moins de 5 km'),
(3, 'moins de 10 km'),
(4, 'moins de 20 km'),
(5, '20 km ou plus');

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

INSERT INTO _langue(langue)
VALUES
('Français'),
('Allemand'),
('Espagnol'),
('Anglais'),
('Chinois');

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
<<<<<<< HEAD
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
=======
('Lefevre', 'Sophie', '1990-11-07', 'Mme', 'SophieLef', 'Lefevre@123', '/img/compte/sophie.jpg', 'sophie.lefevre@gmail.com', '0678901234', 1),
('Baptiste', 'Jean', '1987-09-15', 'Mr', 'JB1987', 'JB1987Pass', '/img/compte/jean.jpg', 'jean.baptiste@gmail.com', '0698765432', 2),
('Dubois', 'Marie', '2000-06-18', 'Mme', 'MarieD', 'Dubois2020!', '/img/compte/marie.jpg', 'marie.dubois@gmail.com', '0623456789', 3);

INSERT INTO _utilisateur (nom, prenom, date_naissance, civilite, pseudo, mot_de_passe, photo_profile, email, telephone, id_adresse) 
VALUES 
('Dupuis', 'Emma', '1975-09-22', 'Mme', 'EmmaD', 'AdminPass', '/img/compte/emma.jpg', 'emma.dupuis@gmail.com', '0678901234', 4);

INSERT INTO _utilisateur (nom, prenom, date_naissance, civilite, pseudo, mot_de_passe, photo_profile, email, telephone, id_adresse) 
VALUES 
('Leclerc', 'Pierre', '1983-12-10', 'Mr', 'PierreLeclerc', 'Pierre123', '/img/compte/pierre.jpg', 'pierre.leclerc@gmail.com', '0678901234', 5),
('Durand', 'Marie', '1990-06-25', 'Mme', 'MarieD', 'Marie456', '/img/compte/marie.jpg', 'marie.durand@gmail.com', '0612345678', 6),
('Girard', 'Paul', '1978-08-20', 'Mr', 'PaulG', 'Paul1980', '/img/compte/paul.jpg', 'paul.girard@gmail.com', '0678904567', 7),
('Martin', 'Luc', '1972-03-28', 'Mme', 'LucM', 'Luc1972', '/img/compte/luc.jpg', 'luc.martin@gmail.com', '0678901357', 8);
>>>>>>> 3b319973ff874adc1b63ea4f9b9febf0648b492d

INSERT INTO _compte_client (id) VALUES (1), (2), (3);

INSERT INTO _compte_admin (id) VALUES (4);

INSERT INTO _compte_proprietaire (id, piece_id_recto, piece_id_verso, IBAN, BIC, Titulaire) 
VALUES 
(5 ,'/img/piece/ID_Leclerc_recto.jpg', '/img/piece/ID_Leclerc_verso.jpg', 'FR7612345678901234567890123', 'AGRIFRPPXXX', 'Pierre Leclerc'),
(6, '/img/piece/ID_Durand_recto.jpg', '/img/piece/ID_Durand_verso.jpg', 'FR7612345678901234567890456', 'BNPFRPPXXX', 'Marie Durand'),
(7, '/img/piece/ID_Girard_recto.jpg', '/img/piece/ID_Girard_verso.jpg', 'FR7612345678901234567890789', 'CEPAFRPPXXX', 'Paul Girard'),
(8, '/img/piece/ID_Martin_recto.jpg', '/img/piece/ID_Martin_verso.jpg', 'FR7612345678901234567890000', 'ABCDFRPPXXX', 'Luc Martin');

INSERT INTO _langue_proprietaire(id_proprietaire,id_langue)
VALUES
(5,1),
(6,2),
(7,3),
(8,4);

INSERT INTO _logement(titre, description, accroche, base_tarif, surface, nb_max_personne, nb_chambre, nb_lit_double, nb_lit_simple, periode_preavis, en_ligne, id_proprietaire, id_adresse, id_categorie, id_type)
VALUES
('Villa avec piscine', 'Belle villa avec piscine dans un quartier calme. Vue magnifique sur la campagne.', 'Vivez des vacances de rêve dans cette villa spacieuse avec piscine.', 150, 200, 8, 4, 3, 2, 30, true, 5,5, 3, 5),
('Maison en pierre avec piscine', 'Charmante maison en pierre rénovée avec piscine privée. Idéale pour des vacances en famille ou entre amis.', 'Profitez du charme de cette maison en pierre avec piscine et jardin arboré.', 180, 180, 6, 3, 2, 2, 45, false, 6,6, 2, 4),
('Appartement cosy', 'Appartement cosy en plein cœur de la ville. Parfait pour découvrir la vie locale.', 'Découvrez le charme de la ville en séjournant dans cet appartement confortable.', 80, 70, 4, 2, 1, 1, 15, true, 7,7, 1, 1);

INSERT INTO _avis(commentaire, note, id_logement, id_client)
VALUES
('Appartement pas propre, c\"est inadmissible', 0.5, 3, 2),
('Villa impeccable, rien à redire, je recommande', 5, 1, 1);

INSERT INTO _reponse_avis(commentaire,id_avis)
VALUES
('Vous êtes un menteur l"appartement est très propre',1);

INSERT INTO _signalement(commentaire,id_avis)
VALUES
('Avis mensongé',1);

INSERT INTO _activite_logement(id_logement, activite, id_distance)
VALUES 
(1, 'Plage', 3),
(1, 'Karting', 2),
(2, 'Golf', 1),
(3, 'Bar (Chez TonTon)', 2);

INSERT INTO _amenagement_logement (id_logement, id_amenagement)
VALUES
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5),
(2, 1), (2, 2), (2, 3), (2, 4), (2, 5), (2, 6), (2, 7), (2, 8), (2, 9),
(3, 10);

INSERT INTO _reservation (date_debut, date_fin, nb_occupant, taxe_sejour, taxe_commission, prix_ht, prix_ttc, date_annulation, annulation,id_logement,id_client)
VALUES
('2024-07-15', '2024-07-22', 6, 20.00, 15.00, 1200.00, 1400.00, NULL, false,1,1),
('2024-08-20', '2024-08-27', 3, 12.00, 9.00, 700.00, 805.00, '2024-08-18', true,2,3);

INSERT INTO _reservation_prix_par_nuit (prix,nb_nuit,id_reservation)
VALUES
(180,6,1),
(200,6,2);

<<<<<<< HEAD

=======
>>>>>>> 3b319973ff874adc1b63ea4f9b9febf0648b492d
INSERT INTO _image(src,principale,alt,id_logement)
VALUES
('/images/logement/1/salon2.png',true,'salon',1),
('/images/logement/2/maison.png',true,'maison',2),
('/images/logement/3/chambre2.png',true,'chambre',3);


INSERT INTO _image(src,alt,id_logement)
VALUES
('/images/logement/1/chambre2.png','chambre',1),
('/images/logement/1/cuisine.png','cuisine',1),
('/images/logement/1/lit.png','lit',1),
('/images/logement/1/table.png','table',1),
('/images/logement/2/chambre.png','chambre',2),
('/images/logement/2/cuisine.png','cuisine',2),
('/images/logement/2/piscine.png','lit',2),
('/images/logement/2/salon.png','table',2),
('/images/logement/2/terasse.png','table',2),
('/images/logement/3/chambre.png','cuisine',3),
('/images/logement/3/cuisine.png','lit',3),
('/images/logement/3/salon.png','table',3),
<<<<<<< HEAD
('/images/logement/3/sdb.png','table',3);
=======
('/images/logement/3/sdb.png','table',3);

SELECT insert_cal(1, '2024-05-11', 150, 'V');
>>>>>>> 3b319973ff874adc1b63ea4f9b9febf0648b492d
