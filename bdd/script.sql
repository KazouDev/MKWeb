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
  commune VARCHAR(255) NOT NULL,
  code_postal VARCHAR(255) NOT NULL,
  nom_voie VARCHAR(255) NOT NULL,
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

INSERT INTO _adresse (pays, region, departement, commune, code_postal, numero, rep, nom_voie, longitude, latitude) VALUES
('France', 'Bretagne', 'Côte d''Armor', 'Aucaleuc', '22100', '3', '', 'Le Vieux Bourg', '-2.131387', '48.456655'),
('France', 'Bretagne', 'Côte d''Armor', 'Bégard', '22140', '9', '', 'Rue Ker Avel', '-3.293041', '48.6266'),
('France', 'Bretagne', 'Côte d''Armor', 'Bégard', '22140', '14', '', 'Kozh Ti', '-3.308896', '48.616321'),
('France', 'Bretagne', 'Côte d''Armor', 'Yvias', '22930', '16', '', 'Lotissement Saint Judoce', '-3.052112', '48.713311'),
('France', 'Bretagne', 'Côte d''Armor', 'Vildé-Guingalan', '22980', '12', '', 'Vaucouleurs', '-2.130424', '48.43735'),
('France', 'Bretagne', 'Côte d''Armor', 'Yffiniac', '22120', '6', '', 'Rue de la Ville Blanche', '-2.656187', '48.477747'),
('France', 'Bretagne', 'Côte d''Armor', 'Yffiniac', '22120', '2', '', 'Rue des Fleurs', '-2.715415', '48.466077'),
('France', 'Bretagne', 'Côte d''Armor', 'Le Vieux-Marché', '22420', '3', '', 'Lanneg Roz ar C''hlan', '-3.46021', '48.606877'),
('France', 'Bretagne', 'Côte d''Armor', 'Uzel', '22460', '3', '', 'Rue Abbe Lesage', '-2.836502', '48.284536'),
('France', 'Bretagne', 'Côte d''Armor', 'Trévron', '22100', '4', '', 'Le Creux', '-2.093221', '48.376611'),
('France', 'Bretagne', 'Côte d''Armor', 'Trévou-Tréguignec', '22660', '6', '', 'Rue de Kermorgan', '-3.343852', '48.821108'),
('France', 'Bretagne', 'Côte d''Armor', 'Tréveneuc', '22410', '34', '', 'Rue de Kercadoret', '-2.86298', '48.666777'),
('France', 'Bretagne', 'Côte d''Armor', 'Tréogan', '22340', '1', '', 'Kervern', '-3.540269', '48.184345'),
('France', 'Bretagne', 'Côte d''Armor', 'Trémuson', '22440', '13', '', 'La Grande Roche', '-2.834813', '48.528448'),
('France', 'Bretagne', 'Côte d''Armor', 'Trémeur', '22250', '7', '', 'Lotissement Puits Gaulois', '-2.26419', '48.35011'),
('France', 'Bretagne', 'Côte d''Armor', 'Trélivan', '22100', '1', '', 'Lieu Dit Bougault', '-2.098974', '48.42968'),
('France', 'Bretagne', 'Côte d''Armor', 'Trélivan', '22100', '9', '', 'Rue des Lilas', '-2.113693', '48.435214'),
('France', 'Bretagne', 'Côte d''Armor', 'Tréguier', '22220', '8', '', 'Rue des Isles', '-3.236457', '48.780879'),
('France', 'Bretagne', 'Côte d''Armor', 'Tréguier', '22220', '5', '', 'Rue de Crublen', '-3.239756', '48.781099'),
('France', 'Bretagne', 'Côte d''Armor', 'Trégueux', '22950', '8', '', 'La Cloture', '-2.734797', '48.477424'),
('France', 'Bretagne', 'Finistère', 'Arzano', '29300', '28', 'bis', 'Rue de Keravel', '-3.436063', '47.902366'),
('France', 'Bretagne', 'Finistère', 'Audierne', '29770', '13', '', 'Rue Parc Allec', '-4.563974', '48.030713'),
('France', 'Bretagne', 'Finistère', 'Audierne', '29770', '4', '', 'Rue René Autret', '-4.549174', '48.025775'),
('France', 'Bretagne', 'Finistère', 'Bannalec', '29380', '64', '', 'Keransquer', '-3.672783', '47.951578'),
('France', 'Bretagne', 'Finistère', 'Bannalec', '29380', '699', '', 'Ty Nevez Rozhuel', '-3.716312', '47.933316'),
('France', 'Bretagne', 'Finistère', 'Bannalec', '29380', '30', '', 'Rue Eugène Lorec', '-3.69929', '47.926848'),
('France', 'Bretagne', 'Finistère', 'Le Trévoux', '29380', '567', '', 'Lieu Dit Keriscat', '-3.648688', '47.901225'),
('France', 'Bretagne', 'Finistère', 'Pont-de-Buis-lès-Quimerch', '29590', '1', '', 'Le Stum', '-4.14755', '48.246604'),
('France', 'Bretagne', 'Finistère', 'Pont-de-Buis-lès-Quimerch', '29590', '4', '', 'rue de la Résistance', '-4.093426', '48.260069'),
('France', 'Bretagne', 'Finistère', 'Trémaouézan', '29800', '11', 'bis', 'Les Courlis', '-4.250347', '48.501855'),
('France', 'Bretagne', 'Finistère', 'Tréméven', '29300', '97', '', 'Pretanou', '-3.525556', '47.910445'),
('France', 'Bretagne', 'Finistère', 'Le Tréhou', '29450', '3', '', 'Kernonen', '-4.141054', '48.388898'),
('France', 'Bretagne', 'Finistère', 'Trégunc', '29910', '320', '', 'Lieu Dit Croissant Bouillet', '-3.844702', '47.880871'),
('France', 'Bretagne', 'Finistère', 'Trégunc', '29910', '8', '', 'Impasse des Glénan', '-3.871108', '47.848442'),
('France', 'Bretagne', 'Finistère', 'Trégunc', '29910', '4', '', 'Rue Louis Sellin', '-3.87809', '47.856988'),
('France', 'Bretagne', 'Finistère', 'Trégunc', '29910', '550', '', 'Route de Kerléo', '-3.841687', '47.811817'),
('France', 'Bretagne', 'Finistère', 'Trégourez', '29970', '5', '', 'Follezou', '-3.876832', '48.119342'),
('France', 'Bretagne', 'Finistère', 'Tréflez', '29430', '6', '', 'Prat Coz', '-4.245358', '48.603578'),
('France', 'Bretagne', 'Finistère', 'Tourch', '29140', '1', '', 'Bel Air', '-3.822827', '48.004475'),
('France', 'Bretagne', 'Finistère', 'Treffiagat', '29730', '1', '', 'Rue du Vieux Moulin', '-4.258352', '47.800916'),
('France', 'Bretagne', 'Ille-et-Vilaine', 'Amanlis', '35150', '5', '', 'Rue des Camélias', '-1.476991', '48.002609'),
('France', 'Bretagne', 'Ille-et-Vilaine', 'Acigné', '35690', '10', '', 'Rue du Clos Richard', '-1.541687', '48.132152'),
('France', 'Bretagne', 'Ille-et-Vilaine', 'Acigné', '35690', '3', 'b', 'Rue Victor Pannetier', '-1.535709', '48.132621'),
('France', 'Bretagne', 'Ille-et-Vilaine', 'Val-Couesnon', '35460', '7', '', 'L''Écu', '-1.477855', '48.425188'),
('France', 'Bretagne', 'Ille-et-Vilaine', 'Val-Couesnon', '35460', '35', '', 'Les Hautes Rottes', '-1.418691', '48.401064'),
('France', 'Bretagne', 'Ille-et-Vilaine', 'Le Tronchet', '35540', '20', '', 'Le Rocher Argan', '-1.837304', '48.50199'),
('France', 'Bretagne', 'Ille-et-Vilaine', 'Pont-Péan', '35131', '6', '', 'Allée François Mauriac', '-1.707905', '48.014983'),
('France', 'Bretagne', 'Ille-et-Vilaine', 'Vitré', '35500', '8', '', 'Allée Pierre Lefevre', '-1.220589', '48.132641'),
('France', 'Bretagne', 'Ille-et-Vilaine', 'Vitré', '35500', '68', '', 'Boulevard de Châteaubriant', '-1.210394', '48.115115'),
('France', 'Bretagne', 'Ille-et-Vilaine', 'Vitré', '35500', '2', '', 'Chemin des Perrines', '-1.179321', '48.120266'),
('France', 'Bretagne', 'Ille-et-Vilaine', 'Vitré', '35500', '35', '', 'Rue Françoise Dolto', '-1.231653', '48.126608'),
('France', 'Bretagne', 'Ille-et-Vilaine', 'Vitré', '35500', '50', '', 'Rue de Plagué', '-1.180745', '48.118474'),
('France', 'Bretagne', 'Ille-et-Vilaine', 'Vitré', '35500', '8', '', 'Allee de Villaudin', '-1.212073', '48.130788'),
('France', 'Bretagne', 'Ille-et-Vilaine', 'Vitré', '35500', '2', '', 'Place Villajoyosa', '-1.209592', '48.120596'),
('France', 'Bretagne', 'Ille-et-Vilaine', 'Visseiche', '35130', '2', '', 'Lieu dit le Bignon', '-1.297957', '47.958933'),
('France', 'Bretagne', 'Ille-et-Vilaine', 'Vieux-Vy-sur-Couesnon', '35490', '40', '', 'La Perriere', '-1.491549', '48.33785'),
('France', 'Bretagne', 'Ille-et-Vilaine', 'Vezin-le-Coquet', '35132', '3', '', 'Rue du Champ Morio', '-1.756728', '48.12124'),
('France', 'Bretagne', 'Ille-et-Vilaine', 'Vezin-le-Coquet', '35132', '2', '', 'le Champ Noël', '-1.732101', '48.117256'),
('France', 'Bretagne', 'Ille-et-Vilaine', 'Vern-sur-Seiche', '35770', '97', '', 'Rue de Châteaubriant', '-1.602116', '48.03964'),
('France', 'Bretagne', 'Ille-et-Vilaine', 'Vern-sur-Seiche', '35770', '21', '', 'Avenue de Solidor', '-1.61559', '48.046809'),
('France', 'Bretagne', 'Morbihan', 'Allaire', '56350', '8', '', 'Impasse des Bengalis', '-2.16798', '47.634074'),
('France', 'Bretagne', 'Morbihan', 'Ambon', '56190', '12', '', 'Lotissement le Prinhuel', '-2.563304', '47.555222'),
('France', 'Bretagne', 'Morbihan', 'Ambon', '56190', '4', '', 'ZAC Espace Littoral', '-2.506348', '47.559448'),
('France', 'Bretagne', 'Morbihan', 'Arzal', '56190', '26', '', 'Parc Loisir du Galitour', '-2.379848', '47.518231'),
('France', 'Bretagne', 'Morbihan', 'Arradon', '56610', '13', '', 'Rue Pont Er Ver', '-2.818693', '47.628624'),
('France', 'Bretagne', 'Morbihan', 'Arradon', '56610', '11', '', 'Chemin du Herbon', '-2.844554', '47.632571'),
('France', 'Bretagne', 'Morbihan', 'La Vraie-Croix', '56250', '16', '', 'Kerbrazic', '-2.539756', '47.694735'),
('France', 'Bretagne', 'Morbihan', 'Le Bono', '56400', '16', '', 'Rue de l''Ile du Grand Vézit', '-2.942658', '47.636269'),
('France', 'Bretagne', 'Morbihan', 'Sainte-Anne-d''Auray', '56400', '20', '', 'Lotissement Beauséjour', '-2.956958', '47.698351'),
('France', 'Bretagne', 'Morbihan', 'Sainte-Anne-d''Auray', '56400', '29', '', 'Lieu Dit Pen Prat', '-2.945195', '47.697692'),
('France', 'Bretagne', 'Morbihan', 'La Trinité-Porhoët', '56490', '8', '', 'Rue de Loudeac', '-2.552251', '48.097517'),
('France', 'Bretagne', 'Morbihan', 'La Trinité-sur-Mer', '56470', '25', '', 'Domaine de Kerhino', '-3.031203', '47.580799'),
('France', 'Bretagne', 'Morbihan', 'La Trinité-sur-Mer', '56470', '1496', '', 'Rue de Kerisper', '-3.030926', '47.593664'),
('France', 'Bretagne', 'Morbihan', 'Vannes', '56000', '19', '', 'Rue de la Métairie', '-2.777952', '47.640173'),
('France', 'Bretagne', 'Morbihan', 'Vannes', '56000', '6', '', 'Allée Denis Diderot', '-2.748521', '47.652858'),
('France', 'Bretagne', 'Morbihan', 'Vannes', '56000', '103', '', 'Rue Jean Jaurès', '-2.750994', '47.639351'),
('France', 'Bretagne', 'Morbihan', 'Vannes', '56000', '8', '', 'Rue Robert Schuman', '-2.770588', '47.651178'),
('France', 'Bretagne', 'Morbihan', 'Vannes', '56000', '34', '', 'Impasse du Petit Conleau', '-2.783463', '47.636171'),
('France', 'Bretagne', 'Morbihan', 'Vannes', '56000', '4', '', 'Allée du Noroît', '-2.771338', '47.639905'),
('France', 'Bretagne', 'Morbihan', 'Vannes', '56000', '9', 'bis', 'Rue Louis Pasteur', '-2.763011', '47.655071');

INSERT INTO users (last_name, first_name, birth_date, gender, username, password, profile_image, email, phone, id) VALUES
('Dupont', 'Jean', '1944-03-15', 'Mr', 'JeanD', '$2y$10$BDX6AiuIWhjCXxTO3rXhMObumJjQsn6waQVEJPGcX3hZXolu4J9M.', './img/compte/profile_jeand.jpg', 'jean.dupont@gmail.com', '06 12 34 56 78', 1),
('Martin', 'Marie', '1947-05-23', 'Mme', 'MarieM', '$2y$10$lVCsVLWcTTKb4qHytuN6C.iHPHcLwjuxWBmDJ99GP.z3EYWYY80SG', './img/compte/profile_mariem.jpg', 'marie.martin@yahoo.com', '06 23 45 67 89', 2),
('Lefevre', 'Pierre', '1950-08-19', 'Mr', 'PierreL', '$2y$10$tACfPV0DiKt1PQJDGaiUUOUdlbK7vUjfs/3fpQF4zfY5NCWe6/hWG', './img/compte/profile_pierrel.jpg', 'pierre.lefevre@icloud.com', '06 34 56 78 90', 3),
('Bernard', 'Sophie', '1953-11-05', 'Mme', 'SophieB', '$2y$10$X/3LCBxqfR9r5e.9g7x5QOCh05UOqTzH8vibS3ej7Q9RqjyGNHFcG', './img/compte/profile_sophieb.jpg', 'sophie.bernard@free.com', '06 45 67 89 01', 4),
('Dubois', 'Jacques', '1956-01-29', 'Mr', 'JacquesD', '$2y$10$G8a1JQ0zbcJ9kvcnK7PEmeDuoyXfXpyhBJaxHfaR0BKoxo9hHNsnG', './img/compte/profile_jacquesd.jpg', 'jacques.dubois@orange.com', '06 56 78 90 12', 5),
('Moreau', 'Camille', '1959-04-17', 'Mme', 'CamilleM', '$2y$10$ZoN3zC0CCtjt6F.7e6xTiOdyzL24G.tGDKqyqf8tB2wjZGNv.0e7G', './img/compte/profile_camillem.jpg', 'camille.moreau@gmail.com', '06 67 89 01 23', 6),
('Petit', 'Louis', '1962-07-08', 'Mr', 'LouisP', '$2y$10$h8Z1E0u8pz8fmluTIMmVCeCatHuYZDDrBG72PtoCYq3pIoeVhju.y', './img/compte/profile_louisp.jpg', 'louis.petit@yahoo.com', '06 78 90 12 34', 7),
('Laurent', 'Emma', '1965-09-13', 'Mme', 'EmmaL', '$2y$10$jUIyKS8XNs6P1r1.SNVqJOf2YHbKCTaXld/pvaUY9In2hYO96JsBm', './img/compte/profile_emmal.jpg', 'emma.laurent@icloud.com', '06 89 01 23 45', 8),
('Simon', 'Paul', '1968-12-21', 'Mr', 'PaulS', '$2y$10$ynUvK9JU6ntfpzQiXuuYh.Lxsrx2pyPmWkuF/jprSQjq8UJgNhFRa', './img/compte/profile_pauls.jpg', 'paul.simon@free.com', '06 90 12 34 56', 9),
('Michel', 'Julie', '1971-02-06', 'Mme', 'JulieM', '$2y$10$nbfO7fVz.cLWQb./iQ0if.OMq/28XP7242eu5t4TT6/YuixKSX4W2', './img/compte/profile_juliem.jpg', 'julie.michel@orange.com', '06 01 23 45 67', 10),
('Richard', 'Henri', '1974-03-25', 'Mr', 'HenriR', '$2y$10$5r9Rh515omuBc2X7JZ1rF.oDyBYw9kjs4iCQteSU1iqjxN1e/tFLq', './img/compte/profile_henrir.jpg', 'henri.richard@gmail.com', '06 12 34 56 78', 11),
('Robert', 'Claire', '1977-06-30', 'Mme', 'ClaireR', '$2y$10$wmBNUc9n57Rj8qPY.6dR0.ycS2koW5WA9S7oH7ew9uouWyBwv4XnS', './img/compte/profile_clairer.jpg', 'claire.robert@yahoo.com', '06 23 45 67 89', 12),
('Durand', 'Marc', '1980-08-14', 'Mr', 'MarcD', '$2y$10$ATRehU5G2xlt4kzNSoXfAenFnTf.ZkXtg6kGu7NWFOo0/9lFyUBWm', './img/compte/profile_marcd.jpg', 'marc.durand@icloud.com', '06 34 56 78 90', 13),
('Dubreuil', 'Isabelle', '1983-11-28', 'Mme', 'IsabelleD', '$2y$10$7MEsMb0Kce.zo/3VkABdVuYWJhc9A0reB5uqVDJ2evEbeEcJTEPqe', './img/compte/profile_isabelled.jpg', 'isabelle.dubreuil@free.com', '06 45 67 89 01', 14),
('Legrand', 'Philippe', '1986-01-19', 'Mr', 'PhilippeL', '$2y$10$mffJbcbkiuh9bvjTIEkTlOXL8506lxc6yTXKmmruC2Je7Bn386CH6', './img/compte/profile_philippel.jpg', 'philippe.legrand@orange.com', '06 56 78 90 12', 15),
('Fournier', 'Elise', '1989-04-07', 'Mme', 'EliseF', '$2y$10$zPr.R6f6rN7sReKUSVfA7upOE5a2SXkvMHitg1PdrPZAYL7BMmNT6', './img/compte/profile_elisef.jpg', 'elise.fournier@gmail.com', '06 67 89 01 23', 16),
('Girard', 'Nicolas', '1992-07-16', 'Mr', 'NicolasG', '$2y$10$FNMyu/szqISCBtADaiaQHe6idYKPl91KLxbej4xLenyR4HZOc/A9e', './img/compte/profile_nicolasg.jpg', 'nicolas.girard@yahoo.com', '06 78 90 12 34', 17),
('Roux', 'Alice', '1995-09-09', 'Mme', 'AliceR', '$2y$10$wublmNYifKiVWpHWTonkhuMRfvstvuUqMnHpJIpuNanKQ5eYESpvy', './img/compte/profile_alicer.jpg', 'alice.roux@icloud.com', '06 89 01 23 45', 18),
('Pires', 'Antoine', '1998-12-31', 'Mr', 'AntoineP', '$2y$10$/Y5/.pkxdBOWuBPQfjO4Yu.czeE8UJmiIgDEAZXB9rtry8a06moby', './img/compte/profile_antoinep.jpg', 'antoine.pires@free.com', '06 90 12 34 56', 19),
('Blanc', 'Lucie', '2001-02-11', 'Mme', 'LucieB', '$2y$10$uRb1xxvQ.JRjeTSYtoB3V..3uhSvMe8qHQLhwx25rR64bAeVNuQEm', './img/compte/profile_lucieb.jpg', 'lucie.blanc@orange.com', '06 01 23 45 67', 20),
('Bertrand', 'Victor', '2004-04-24', 'Mr', 'VictorB', '$2y$10$vk/6FJbgFo//bdWrcF40Lea3tY58oJRTL701kSxT0Xbgy4wlgt6I.', './img/compte/profile_victorb.jpg', 'victor.bertrand@gmail.com', '06 12 34 56 78', 21),
('Clement', 'Eva', '2006-06-20', 'Mme', 'EvaC', '$2y$10$harSdmibF87VRIg/.o9Uau6m6SlbSmPfr69gp3OgBrSoyJSu.gWBm', './img/compte/profile_evac.jpg', 'eva.clement@yahoo.com', '06 23 45 67 89', 22),
('Noel', 'Gabriel', '2007-08-04', 'Mr', 'GabrielN', '$2y$10$4khDZMHfM6h3C.BDcOCpbeQiBeQV8uiAx/3UlOvjRGkNe4Vmm9uDm', './img/compte/profile_gabrieln.jpg', 'gabriel.noel@icloud.com', '06 34 56 78 90', 23),
('Roussel', 'Mathilde', '2008-10-19', 'Mme', 'MathildeR', '$2y$10$fi5YRye9iZYF3.JhN0zhpeBCjWEgaG2R501q2HuoMMnwnM0MiuWJu', './img/compte/profile_mathilder.jpg', 'mathilde.roussel@free.com', '06 45 67 89 01', 24),
('Chevalier', 'Alexandre', '2009-12-03', 'Mr', 'AlexandreC', '$2y$10$KCbBdnpool6zDpfsqPEHEeB933OKKdXPnYGs9USanrtiskkzBfVK2', './img/compte/profile_alexandrec.jpg', 'alexandre.chevalier@orange.com', '06 56 78 90 12', 25),
('Lucas', 'Laura', '2011-01-25', 'Mme', 'LauraL', '$2y$10$MZXPiQ0rC1DWl/rahOLgkuiTbtHa7UT3bcuv1wA2O1pGtU5kpPyd6', './img/compte/profile_laural.jpg', 'laura.lucas@gmail.com', '06 67 89 01 23', 26),
('Lemoine', 'Théo', '2012-04-09', 'Mr', 'ThéoL', '$2y$10$sUO7avnSSQg9Pi5cwshUKuKUksszDv2x6hnrtspDtkrt8kg2iXW.O', './img/compte/profile_theol.jpg', 'theo.lemoine@yahoo.com', '06 78 90 12 34', 27),
('Garcia', 'Juliette', '2013-06-25', 'Mme', 'JulietteG', '$2y$10$LiSaWQLQX5Wq/Mkv3cKNyuhmT7ooBZrYfGJHjaV4382Was1gW5wMq', './img/compte/profile_julietteg.jpg', 'juliette.garcia@icloud.com', '06 89 01 23 45', 28),
('Michon', 'Maxime', '2014-09-08', 'Mr', 'MaximeM', '$2y$10$kqtg21wp1EZ5oJiXiZeIBul160wh6djwJX4kg.fX5rtii6NSWEGcq', './img/compte/profile_maximem.jpg', 'maxime.michon@free.com', '06 90 12 34 56', 29),
('Barbier', 'Emma', '2015-11-23', 'Mme', 'EmmaB', '$2y$10$o4cNE8Zn0m3t/TVOCmO.PuWHBYkwcBe/dEFqXlX2Atqqz.549OR2K', './img/compte/profile_emmab.jpg', 'emma.barbier@orange.com', '06 01 23 45 67', 30),
('Carpentier', 'Hugo', '2017-01-06', 'Mr', 'HugoC', '$2y$10$muRNmnbX8Y6nPrgavczPbeg5E9ZGiOdKfPqTKoDUlGipImoVYpKxi', './img/compte/profile_hugoc.jpg', 'hugo.carpentier@gmail.com', '06 12 34 56 78', 31),
('Renard', 'Chloé', '2018-03-22', 'Mme', 'ChloéR', '$2y$10$0MoaDKJx95O8kV8uGmXfyOp4w1xQeAwe1sWdUO30lKPU/7iSY748C', './img/compte/profile_chloer.jpg', 'chloe.renard@yahoo.com', '06 23 45 67 89', 32),
('Meyer', 'Nathan', '2019-06-05', 'Mr', 'NathanM', '$2y$10$JBIC.ZcbvH4S5U6FJ2FM1Oui3saNpBxJF5GZNInqZHAXLqAlcGyfS', './img/compte/profile_nathanm.jpg', 'nathan.meyer@icloud.com', '06 34 56 78 90', 33),
('Leclerc', 'Inès', '2020-08-20', 'Mme', 'InèsL', '$2y$10$DNjTfBwTdmAEYOru2ynyN.9keYPq93zClg8qOMR.Jw1rM2RSwNoLS', './img/compte/profile_inesl.jpg', 'ines.leclerc@free.com', '06 45 67 89 01', 34),
('Riviere', 'Matheo', '2021-11-04', 'Mr', 'MatheoR', '$2y$10$cIuajMmZ5CT9PTALvEfpleWTW/QNtkvPnjzOaWpZaAL0qDY1DTXCi', './img/compte/profile_matheor.jpg', 'matheo.riviere@orange.com', '06 56 78 90 12', 35),
('Henry', 'Clara', '2023-01-18', 'Mme', 'ClaraH', '$2y$10$mOLqmq/kIZ7cQx9csIFWsOnuXgeUUbOS8TaotjuUNe0VnJOtzm0WG', './img/compte/profile_clarah.jpg', 'clara.henry@gmail.com', '06 67 89 01 23', 36),
('Pascal', 'Lucas', '2024-04-03', 'Mr', 'LucasP', '$2y$10$pkSXLVagQy/Osmr4X0i/N.3TYWKMSysfUqzQ5bmO.zF1nqmLqzZmW', './img/compte/profile_lucasp.jpg', 'lucas.pascal@yahoo.com', '06 78 90 12 34', 37),
('Brun', 'Manon', '2025-06-18', 'Mme', 'ManonB', '$2y$10$bt1MMRH5A70/Y4TDy03Kzu1OUTkwgYcLck3u5Rw2ukA5ECJTzCHKO', './img/compte/profile_manonb.jpg', 'manon.brun@icloud.com', '06 89 01 23 45', 38),
('Gautier', 'Romain', '2026-09-01', 'Mr', 'RomainG', '$2y$10$BdfH50pyR6zslSytNRhsb.Xx1dTpy6y9DdVtL3sMUNe55QP0RhEzC', './img/compte/profile_romaing.jpg', 'romain.gautier@free.com', '06 90 12 34 56', 39),
('Hubert', 'Léa', '2027-11-16', 'Mme', 'LéaH', '$2y$10$fnqA585WvE0G0cidyuEv2O.lrINHQrJX6rJhmFRZyvK7X89BshSyq', './img/compte/profile_leah.jpg', 'lea.hubert@orange.com', '06 01 23 45 67', 40),
('Louis', 'Maxence', '2029-02-01', 'Mr', 'MaxenceL', '$2y$10$HseoLyO4A/CPy6X1KvhEBeZRqvLePWFx2qjaxIoXHtIKfkBP3cZAG', './img/compte/profile_maxencel.jpg', 'maxence.louis@gmail.com', '06 12 34 56 78', 41),
('Renault', 'Louise', '2030-04-18', 'Mme', 'LouiseR', '$2y$10$0GY/mAe8X41Cq2p4QeW9sukyWlGmdOFrfj.67Wla9PbiMYtDbxQQq', './img/compte/profile_louiser.jpg', 'louise.renault@yahoo.com', '06 23 45 67 89', 42);

INSERT INTO _compte_client (id) VALUES 
(23), (24), (25), (26), (27), (28), (29), (30), (31), (32),
(33), (34), (35), (36), (37), (38), (39), (40), (41), (42);

INSERT INTO _compte_admin (id) VALUES 
(21), (22);

INSERT INTO _compte_proprietaire (id, piece_id_recto, piece_id_verso, IBAN, BIC, Titulaire) VALUES 
(1, './img/piece/1_Dupont_recto.jpg', './img/piece/1_Dupont_verso.jpg', 'FR7612345678901234567890123', 'AGRIFRPPXXX', 'Dupont Jean'),
(2, './img/piece/2_Martin_recto.jpg', './img/piece/2_Martin_verso.jpg', 'FR7612345678901234567890124', 'AGRIFRPPXXX', 'Martin Marie'),
(3, './img/piece/3_Lefevre_recto.jpg', './img/piece/3_Lefevre_verso.jpg', 'FR7612345678901234567890125', 'AGRIFRPPXXX', 'Lefevre Pierre'),
(4, './img/piece/4_Bernard_recto.jpg', './img/piece/4_Bernard_verso.jpg', 'FR7612345678901234567890126', 'AGRIFRPPXXX', 'Bernard Sophie'),
(5, './img/piece/5_Dubois_recto.jpg', './img/piece/5_Dubois_verso.jpg', 'FR7612345678901234567890127', 'AGRIFRPPXXX', 'Dubois Jacques'),
(6, './img/piece/6_Moreau_recto.jpg', './img/piece/6_Moreau_verso.jpg', 'FR7612345678901234567890128', 'AGRIFRPPXXX', 'Moreau Camille'),
(7, './img/piece/7_Petit_recto.jpg', './img/piece/7_Petit_verso.jpg', 'FR7612345678901234567890129', 'AGRIFRPPXXX', 'Petit Louis'),
(8, './img/piece/8_Laurent_recto.jpg', './img/piece/8_Laurent_verso.jpg', 'FR7612345678901234567890130', 'AGRIFRPPXXX', 'Laurent Emma'),
(9, './img/piece/9_Simon_recto.jpg', './img/piece/9_Simon_verso.jpg', 'FR7612345678901234567890131', 'AGRIFRPPXXX', 'Simon Paul'),
(10, './img/piece/10_Michel_recto.jpg', './img/piece/10_Michel_verso.jpg', 'FR7612345678901234567890132', 'AGRIFRPPXXX', 'Michel Julie'),
(11, './img/piece/11_Richard_recto.jpg', './img/piece/11_Richard_verso.jpg', 'FR7612345678901234567890133', 'AGRIFRPPXXX', 'Richard Henri'),
(12, './img/piece/12_Robert_recto.jpg', './img/piece/12_Robert_verso.jpg', 'FR7612345678901234567890134', 'AGRIFRPPXXX', 'Robert Claire'),
(13, './img/piece/13_Durand_recto.jpg', './img/piece/13_Durand_verso.jpg', 'FR7612345678901234567890135', 'AGRIFRPPXXX', 'Durand Marc'),
(14, './img/piece/14_Dubreuil_recto.jpg', './img/piece/14_Dubreuil_verso.jpg', 'FR7612345678901234567890136', 'AGRIFRPPXXX', 'Dubreuil Isabelle'),
(15, './img/piece/15_Legrand_recto.jpg', './img/piece/15_Legrand_verso.jpg', 'FR7612345678901234567890137', 'AGRIFRPPXXX', 'Legrand Philippe'),
(16, './img/piece/16_Fournier_recto.jpg', './img/piece/16_Fournier_verso.jpg', 'FR7612345678901234567890138', 'AGRIFRPPXXX', 'Fournier Elise'),
(17, './img/piece/17_Girard_recto.jpg', './img/piece/17_Girard_verso.jpg', 'FR7612345678901234567890139', 'AGRIFRPPXXX', 'Girard Nicolas'),
(18, './img/piece/18_Roux_recto.jpg', './img/piece/18_Roux_verso.jpg', 'FR7612345678901234567890140', 'AGRIFRPPXXX', 'Roux Alice'),
(19, './img/piece/19_Pires_recto.jpg', './img/piece/19_Pires_verso.jpg', 'FR7612345678901234567890141', 'AGRIFRPPXXX', 'Pires Antoine'),
(20, './img/piece/20_Blanc_recto.jpg', './img/piece/20_Blanc_verso.jpg', 'FR7612345678901234567890142', 'AGRIFRPPXXX', 'Blanc Lucie');

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

INSERT INTO _logement(titre, description, accroche, base_tarif, surface, nb_max_personne, nb_chambre, nb_lit_double, nb_lit_simple, periode_preavis, en_ligne, id_proprietaire, id_adresse, id_categorie, id_type) VALUES
('Appartement cosy en centre-ville', 'Un appartement confortable et bien situé au centre-ville, parfait pour une ou deux personnes. Ce logement offre un espace de vie moderne avec une cuisine entièrement équipée, une salle de bain rénovée et une chambre lumineuse avec un grand lit. Idéal pour les voyageurs souhaitant découvrir la ville à pied, tout en profitant du confort d’un intérieur douillet.', 'Réservez votre séjour en centre-ville.', 75, 25, 2, 1, 0, 2, 3, true, 1, 42, 1, 2),
('Maison spacieuse à la campagne', 'Une grande maison avec jardin à la campagne, idéale pour des vacances en famille. Cette maison comprend plusieurs chambres spacieuses, une cuisine moderne, une salle à manger conviviale et un salon confortable avec cheminée. Le jardin privé est parfait pour les barbecues et les jeux en plein air. À proximité, vous trouverez des sentiers de randonnée et des attractions locales pour toute la famille.', 'Vivez la campagne en grand.', 135, 120, 6, 3, 2, 2, 3, true, 2, 43, 2, 4),
('Villa d''exception avec vue sur mer', 'Une magnifique villa avec vue panoramique sur la mer, offrant luxe et confort. La villa dispose de grandes baies vitrées pour profiter de la vue, une piscine à débordement, et plusieurs terrasses pour se détendre. À l’intérieur, vous trouverez des équipements haut de gamme, des chambres élégantes, et une cuisine gastronomique. Idéale pour des vacances de luxe en bord de mer, avec un accès facile aux plages et aux restaurants.', 'Luxe et vue sur mer.', 300, 150, 8, 4, 2, 6, 5, true, 3, 44, 3, 1),
('Chalet de montagne authentique', 'Un chalet traditionnel en bois, situé dans une station de ski populaire. Le chalet combine charme rustique et commodités modernes, avec une grande cheminée, un espace spa avec sauna, et une cuisine équipée. Les chambres sont confortables avec des lits douillets, et le salon offre une vue imprenable sur les montagnes. Parfait pour les amateurs de ski et de nature, avec des pistes accessibles directement depuis le chalet.', 'Profitez de l''authenticité de la montagne.', 320, 110, 10, 5, 2, 8, 5, true, 4, 45, 4, 6),
('Bateau de charme sur la Seine', 'Un bateau confortable amarré sur la Seine, idéal pour un séjour romantique. Ce bateau offre une expérience unique avec une vue sur la ville depuis l’eau. Les aménagements incluent une chambre cosy, un salon avec grandes fenêtres, et une petite cuisine équipée. Profitez des soirées sur le pont, en admirant les lumières de la ville et en vous détendant au son de l’eau.', 'Séjour romantique sur la Seine.', 200, 30, 4, 2, 1, 2, 5, true, 5, 46, 5, 3),
('Logement insolite dans une cabane', 'Une cabane unique en pleine nature, parfaite pour un séjour dépaysant. Construite en bois avec des matériaux écologiques, cette cabane offre un espace de vie confortable avec une petite cuisine, une salle de bain moderne, et une chambre avec vue sur la forêt. Idéale pour ceux qui cherchent à se reconnecter avec la nature, avec des sentiers de randonnée et un environnement paisible.', 'Séjournez dans une cabane insolite.', 150, 20, 2, 1, 1, 1, 3, true, 6, 47, 6, 7),
('Appartement moderne avec balcon', 'Un appartement moderne avec un grand balcon et une vue sur la ville. L’intérieur est décoré avec goût et offre tout le confort nécessaire, incluant une cuisine équipée, un salon lumineux, et une chambre avec un lit king-size. Le balcon est parfait pour prendre un café le matin ou un verre le soir en admirant le coucher de soleil. Situé proche des commerces et des transports en commun, cet appartement est idéal pour les voyageurs urbains.', 'Vue imprenable sur la ville.', 90, 28, 4, 1, 2, 2, 3, true, 7, 48, 1, 5),
('Maison familiale avec piscine', 'Maison spacieuse avec piscine privée, idéale pour des vacances en famille. La maison dispose de plusieurs chambres, d’une cuisine moderne et équipée, d’un salon confortable, et d’une grande salle à manger. Le jardin est équipé d’un barbecue et d’une aire de jeux pour enfants. La piscine est chauffée et sécurisée, parfaite pour se rafraîchir durant les journées chaudes. Située dans un quartier calme, mais proche des attractions locales.', 'Profitez de la piscine privée.', 160, 100, 8, 4, 3, 4, 3, true, 8, 49, 2, 7),
('Villa d''exception avec jardin tropical', 'Une villa luxueuse entourée d''un jardin tropical, parfaite pour un séjour relaxant. La villa offre des espaces de vie spacieux, avec des baies vitrées donnant sur le jardin. Profitez de la piscine privée, du jacuzzi, et des multiples terrasses. Les chambres sont élégantes, chacune avec sa propre salle de bain. Le jardin est un véritable paradis, avec des plantes exotiques et des coins détente. Idéale pour des vacances de rêve loin de l''agitation.', 'Luxe et nature tropicale.', 420, 160, 10, 5, 3, 7, 7, true, 9, 50, 3, 2),
('Chalet familial en montagne', 'Chalet familial avec accès direct aux pistes de ski. Ce chalet offre tout le confort nécessaire pour un séjour en famille, avec plusieurs chambres, un grand salon avec cheminée, et une cuisine entièrement équipée. Après une journée de ski, détendez-vous dans le sauna ou profitez d''une soirée cinéma dans le salon. Les enfants apprécieront l''espace de jeux extérieur et les adultes pourront se relaxer sur la terrasse en admirant la vue.', 'Accès direct aux pistes.', 370, 120, 12, 6, 3, 9, 7, false, 10, 51, 4, 9),
('Bateau luxueux sur la Côte d''Azur', 'Un bateau de luxe offrant une expérience unique sur la Côte d''Azur. Ce bateau est équipé de cabines confortables, d''une cuisine moderne, et d''un salon spacieux avec vue sur la mer. Passez vos journées à explorer la côte, à nager dans la mer, ou à bronzer sur le pont. Le soir, profitez d''un dîner sur le pont en admirant le coucher du soleil. Parfait pour une escapade romantique ou une aventure entre amis.', 'Luxueuse expérience sur la Côte d''Azur.', 350, 35, 4, 2, 1, 2, 6, true, 11, 52, 5, 5),
('Logement insolite dans une yourte', 'Séjournez dans une yourte authentique en pleine nature. Cette yourte offre une expérience unique avec un intérieur chaleureux et confortable, équipé de tout le nécessaire pour un séjour agréable. Profitez de la tranquillité et de la beauté naturelle environnante. Idéale pour ceux qui cherchent une escapade insolite et écologique, avec des possibilités de randonnée et d''exploration à proximité.', 'Séjour unique dans une yourte.', 250, 25, 3, 1, 1, 2, 5, true, 12, 53, 6, 4),
('Appartement cosy proche des transports', 'Appartement bien situé à proximité des transports en commun. Cet appartement offre un espace de vie pratique avec une cuisine équipée, un salon confortable, et une chambre avec un lit double. Idéal pour les voyageurs d''affaires ou les touristes, avec un accès facile aux principales attractions de la ville. Profitez de la commodité des transports en commun tout en ayant un lieu de séjour confortable et accueillant.', 'Pratique et confortable.', 85, 20, 2, 1, 0, 2, 3, true, 13, 54, 1, 1),
('Maison moderne en banlieue', 'Maison moderne et confortable, idéale pour une famille. Cette maison dispose de plusieurs chambres, d''une cuisine moderne et équipée, d''un salon spacieux, et d''un jardin privé. Située dans une banlieue tranquille, mais proche des commerces et des écoles, cette maison est parfaite pour une vie familiale paisible. Les enfants peuvent jouer en sécurité dans le jardin tandis que les parents se détendent sur la terrasse.', 'Maison moderne et spacieuse.', 140, 90, 6, 3, 2, 2, 3, true, 14, 55, 2, 10),
('Villa de luxe avec spa privé', 'Villa luxueuse avec spa privé, parfaite pour des vacances de détente. La villa comprend plusieurs chambres élégantes, une cuisine gastronomique, un salon avec cheminée, et une salle de jeux. À l''extérieur, vous trouverez un jardin bien entretenu, une piscine chauffée, et un spa avec jacuzzi et sauna. Idéale pour des vacances relaxantes en famille ou entre amis, avec tout le confort et le luxe nécessaires pour un séjour mémorable.', 'Luxe et détente.', 450, 150, 10, 5, 3, 7, 7, true, 15, 56, 3, 8),
('Chalet romantique avec vue sur lac', 'Un chalet romantique situé au bord d''un lac, parfait pour une escapade en couple. Le chalet offre une vue imprenable sur le lac, avec une terrasse privée pour profiter des soirées tranquilles. L''intérieur est cosy et confortable, avec une cheminée, une cuisine équipée, et une chambre avec un lit king-size. Passez vos journées à explorer les environs ou à vous détendre au bord du lac.', 'Romantique et paisible.', 280, 100, 6, 3, 2, 4, 5, true, 16, 57, 4, 3),
('Bateau traditionnel en Bretagne', 'Découvrez la Bretagne depuis un bateau traditionnel confortable. Ce bateau offre une expérience unique avec des cabines douillettes, une petite cuisine, et un salon avec vue sur la mer. Explorez les côtes bretonnes, arrêtez-vous dans les petits ports de pêche, et savourez des fruits de mer frais. Idéal pour les amateurs de la mer et de la navigation, ce séjour promet aventure et détente.', 'Séjour authentique en Bretagne.', 240, 30, 4, 2, 1, 2, 5, true, 17, 58, 5, 1),
('Logement insolite dans un phare', 'Vivez une expérience unique en séjournant dans un phare rénové. Ce logement insolite offre une vue panoramique sur l''océan, avec des équipements modernes pour un séjour confortable. Profitez de la tranquillité et de la beauté des environs, explorez les sentiers côtiers, et découvrez l''histoire du phare. Idéal pour ceux qui cherchent un séjour insolite et mémorable, loin de l''agitation de la ville.', 'Expérience unique dans un phare.', 300, 40, 4, 2, 1, 2, 5, true, 18, 59, 6, 2),
('Appartement moderne avec terrasse', 'Appartement moderne avec une grande terrasse, idéal pour se détendre. L''appartement offre un espace de vie lumineux avec une cuisine équipée, un salon confortable, et une chambre avec un lit double. La terrasse est parfaite pour prendre le petit-déjeuner ou se relaxer après une journée bien remplie. Situé dans un quartier dynamique avec de nombreux commerces et restaurants à proximité.', 'Terrasse spacieuse.', 110, 28, 4, 1, 2, 2, 3, true, 19, 60, 1, 9),
('Maison avec vue sur les montagnes', 'Maison confortable avec une vue imprenable sur les montagnes. Cette maison offre plusieurs chambres, un grand salon avec cheminée, et une cuisine entièrement équipée. À l''extérieur, vous trouverez un jardin privé et une terrasse avec vue sur les montagnes. Idéale pour les amateurs de plein air, avec des sentiers de randonnée et des pistes de ski à proximité.', 'Vue imprenable sur les montagnes.', 180, 110, 8, 4, 3, 4, 3, true, 20, 61, 2, 5),
('Villa d''exception avec piscine à débordement', 'Une villa luxueuse avec piscine à débordement, offrant une vue spectaculaire sur les environs. La villa dispose de plusieurs chambres élégantes, d''une cuisine gastronomique, et de vastes espaces de vie avec des baies vitrées. La piscine à débordement et les terrasses sont parfaites pour se détendre et profiter du soleil. Idéale pour des vacances de rêve avec tout le confort moderne et un cadre exceptionnel.', 'Piscine à débordement et luxe.', 490, 160, 10, 5, 3, 7, 7, true, 1, 62, 3, 10),
('Chalet traditionnel avec sauna', 'Un chalet en bois traditionnel avec sauna, parfait pour se détendre après une journée de ski. Le chalet offre un intérieur chaleureux avec une grande cheminée, une cuisine équipée, et plusieurs chambres confortables. Le sauna est idéal pour se relaxer, et la terrasse offre une vue magnifique sur les montagnes. Parfait pour un séjour en famille ou entre amis, avec un accès facile aux pistes de ski.', 'Sauna et détente en montagne.', 360, 110, 12, 6, 3, 9, 7, true, 2, 63, 4, 3),
('Bateau contemporain à Nice', 'Bateau moderne et confortable, idéal pour un séjour à Nice. Ce bateau offre des cabines bien aménagées, une cuisine équipée, et un salon avec vue sur la mer. Passez vos journées à explorer la côte, à nager, ou à bronzer sur le pont. Le soir, profitez de la vue sur la baie de Nice depuis le confort de votre bateau. Idéal pour une escapade romantique ou une aventure entre amis.', 'Séjour moderne à Nice.', 260, 35, 4, 2, 1, 2, 5, true, 3, 64, 5, 8),
('Logement insolite dans un tipi', 'Séjournez dans un tipi authentique en pleine nature. Ce tipi offre une expérience unique avec un intérieur chaleureux et confortable, équipé de tout le nécessaire pour un séjour agréable. Profitez de la tranquillité et de la beauté naturelle environnante. Idéale pour ceux qui cherchent une escapade insolite et écologique, avec des possibilités de randonnée et d''exploration à proximité.', 'Séjour authentique dans un tipi.', 200, 20, 2, 1, 1, 1, 5, true, 4, 65, 6, 5),
('Appartement avec vue sur parc', 'Appartement lumineux avec une belle vue sur un parc. Cet appartement offre un espace de vie moderne avec une cuisine équipée, un salon confortable, et une chambre avec un lit double. Profitez de la vue sur le parc depuis le balcon, parfait pour se détendre après une journée bien remplie. Situé dans un quartier calme mais proche des commodités, cet appartement est idéal pour les voyageurs souhaitant un séjour paisible.', 'Vue sur parc.', 115, 29, 4, 1, 2, 2, 3, true, 5, 66, 1, 8),
('Maison avec salle de jeux', 'Maison familiale avec une grande salle de jeux pour les enfants. Cette maison dispose de plusieurs chambres, d''un salon spacieux, d''une cuisine équipée, et d''un jardin privé. La salle de jeux est équipée de nombreux jouets et jeux, parfaite pour occuper les enfants pendant que les adultes se détendent. Située dans un quartier résidentiel calme, proche des écoles et des commerces.', 'Grande salle de jeux.', 150, 100, 8, 4, 3, 4, 3, true, 6, 67, 2, 10),
('Villa d''exception en bord de mer', 'Villa luxueuse en bord de mer, parfaite pour des vacances de rêve. La villa dispose de plusieurs chambres élégantes, d''une cuisine gastronomique, et de vastes espaces de vie avec des baies vitrées. Le jardin offre un accès direct à la plage, et la terrasse est idéale pour se détendre et profiter du soleil. Idéale pour des vacances de rêve avec tout le confort moderne et un cadre exceptionnel.', 'Bord de mer et luxe.', 430, 150, 12, 6, 4, 7, 7, true, 7, 68, 3, 9),
('Chalet avec vue sur forêt', 'Chalet confortable avec une vue imprenable sur la forêt. Ce chalet offre plusieurs chambres, un grand salon avec cheminée, et une cuisine entièrement équipée. À l''extérieur, vous trouverez un jardin privé et une terrasse avec vue sur la forêt. Idéale pour les amateurs de plein air, avec des sentiers de randonnée et des pistes de ski à proximité.', 'Vue sur forêt.', 310, 110, 12, 6, 3, 9, 7, true, 8, 69, 4, 5),
('Bateau de luxe à Cannes', 'Bateau luxueux amarré à Cannes, idéal pour un séjour haut de gamme. Ce bateau est équipé de cabines confortables, d''une cuisine moderne, et d''un salon spacieux avec vue sur la mer. Passez vos journées à explorer la côte, à nager dans la mer, ou à bronzer sur le pont. Le soir, profitez d''un dîner sur le pont en admirant le coucher du soleil. Parfait pour une escapade romantique ou une aventure entre amis.', 'Séjour de luxe à Cannes.', 350, 40, 4, 2, 1, 2, 6, true, 9, 70, 5, 8),
('Logement insolite dans une yourte mongole', 'Une expérience unique vous attend dans cette yourte traditionnelle mongole. Nichée au cœur de la nature, cette yourte offre un espace confortable avec des meubles faits à la main, des tapis colorés et un poêle à bois pour vous réchauffer les nuits fraîches. À l''extérieur, découvrez un paysage préservé, idéal pour la randonnée, l''observation des étoiles ou tout simplement pour se ressourcer en pleine nature.', 'Authenticité et dépaysement garantis.', 190, 30, 3, 1, 1, 2, 5, true, 10, 71, 6, 4),
('Appartement avec vue sur la Tour Eiffel', 'Un appartement élégant offrant une vue imprenable sur la Tour Eiffel. Situé au dernier étage d''un immeuble haussmannien, cet appartement récemment rénové propose des espaces lumineux et modernes. Profitez du spectacle des lumières scintillantes de la Tour Eiffel depuis le confort de votre salon, et laissez-vous séduire par le charme unique de Paris.', 'Vue spectaculaire sur la Tour Eiffel.', 220, 40, 4, 2, 1, 2, 6, true, 11, 72, 1, 1),
('Maison contemporaine avec piscine intérieure', 'Une maison d''architecte offrant un cadre de vie exceptionnel avec une piscine intérieure. Lumineuse et spacieuse, cette maison contemporaine est dotée de grandes baies vitrées offrant une vue panoramique sur le paysage environnant. Plongez dans la piscine intérieure chauffée, détendez-vous dans le sauna ou profitez simplement du calme de la nature depuis la terrasse en bois.', 'Luxe et bien-être au rendez-vous.', 500, 200, 10, 5, 4, 8, 7, true, 12, 73, 2, 2),
('Villa moderne avec vue sur mer', 'Une villa d''architecte offrant des prestations haut de gamme et une vue imprenable sur la mer Méditerranée. Laissez-vous séduire par le design épuré de cette villa, avec ses grandes baies vitrées, ses espaces de vie lumineux et ses terrasses panoramiques. Profitez de la piscine à débordement, de la cuisine extérieure et des soirées sous les étoiles, pour des vacances inoubliables sur la Côte d''Azur.', 'Élégance et vue à couper le souffle.', 600, 250, 12, 6, 5, 9, 8, true, 13, 74, 3, 3),
('Chalet de luxe avec vue sur les Alpes', 'Un chalet d''exception niché au cœur des Alpes suisses, offrant un cadre idyllique pour des vacances au ski inoubliables. Décoré avec raffinement dans un style alpin chic, ce chalet dispose de tous les équipements haut de gamme pour un séjour confortable et luxueux. Après une journée sur les pistes, détendez-vous dans le jacuzzi extérieur en admirant les sommets enneigés.', 'Confort et charme alpin au rendez-vous.', 700, 300, 14, 7, 6, 10, 8, true, 14, 75, 4, 5),
('Bateau de croisière sur la Méditerranée', 'Un yacht de luxe vous attend pour une croisière d''exception en Méditerranée. Explorez les plus belles destinations de la côte méditerranéenne, depuis la Côte d''Azur jusqu''aux îles grecques, à bord de ce navire élégant et sophistiqué. Avec son équipage professionnel et ses équipements haut de gamme, ce yacht vous promet des vacances inoubliables sous le soleil de la Méditerranée.', 'Aventure et luxe en haute mer.', 800, 400, 16, 8, 8, 12, 10, true, 15, 76, 5, 6),
('Maison de vacances avec vue panoramique', 'Une maison de vacances perchée sur une colline, offrant une vue panoramique sur la campagne toscane. Cette demeure rustique et pleine de charme est le lieu idéal pour se ressourcer en pleine nature. Profitez de la piscine extérieure, des terrasses ombragées et des couchers de soleil spectaculaires, pour des vacances inoubliables en Toscane.', 'Authenticité et dolce vita au rendez-vous.', 450, 180, 10, 5, 4, 7, 7, true, 16, 77, 6, 7),
('Appartement de standing avec terrasse', 'Un appartement de standing avec une vaste terrasse offrant une vue imprenable sur la skyline de New York. Situé dans un quartier chic de Manhattan, cet appartement moderne et élégant vous séduira par son design raffiné et ses prestations haut de gamme. Détendez-vous dans le jacuzzi en contemplant les lumières de la ville qui ne dort jamais, pour une expérience inoubliable à Big Apple.', 'Vue époustouflante sur New York.', 1000, 300, 6, 3, 2, 4, 4, true, 17, 78, 1, 8),
('Maison de plage avec accès privé à la mer', 'Une villa de bord de mer offrant un accès privé à une plage de sable blanc, pour des vacances paradisiaques en famille ou entre amis. Cette maison spacieuse et lumineuse est idéalement située au bord de l''eau, offrant une vue imprenable sur l''océan depuis toutes les pièces. Profitez du jardin tropical, de la piscine à débordement et des soirées barbecue les pieds dans le sable, pour des vacances inoubliables sous le soleil des Caraïbes.', 'Plaisir et détente les pieds dans l''eau.', 1200, 400, 14, 7, 6, 10, 8, false, 18, 79, 2, 9), 
('Villa d''exception avec vue sur la baie', 'Une villa d''exception surplombant une magnifique baie turquoise, pour des vacances de luxe dans les îles grecques. Cette villa spectaculaire offre des espaces de vie élégants et raffinés, une piscine à débordement à débordement et des terrasses ensoleillées avec vue sur la mer. Profitez de l''accès privé à la plage, des sports nautiques et des couchers de soleil inoubliables, pour des vacances paradisiaques en famille ou entre amis.', 'Luxe et sérénité dans les îles grecques.', 1500, 500, 16, 8, 8, 12, 10, true, 19, 80, 3, 10),
('Chalet de montagne avec vue panoramique', 'Un chalet de montagne élégant offrant une vue panoramique sur les sommets enneigés, pour des vacances inoubliables dans les Alpes françaises. Ce chalet spacieux et confortable est décoré avec goût dans un style alpin chic, avec des matériaux naturels et des équipements haut de gamme. Détendez-vous près de la cheminée, profitez du sauna ou admirez le coucher de soleil depuis la terrasse en savourant un verre de vin chaud, pour des vacances authentiques et ressourçantes en montagne.', 'Confort et charme alpin au rendez-vous.', 800, 350, 12, 6, 6, 10, 8, true, 20, 1, 4, 5), 
('Bateau de luxe dans les îles', 'Un yacht de luxe vous attend pour une croisière inoubliable dans les plus belles îles du monde. Découvrez des destinations paradisiaques, des plages de sable blanc et des eaux cristallines à bord de ce navire d''exception. Avec son équipage professionnel et ses équipements haut de gamme, ce yacht vous promet des vacances de rêve sous le soleil des tropiques.', 'Aventure et luxe à bord.', 2000, 600, 18, 9, 10, 14, 12, true, 1, 2, 5, 6), 
('Maison d''architecte avec piscine à débordement', 'Une maison d''architecte d''inspiration méditerranéenne offrant un cadre de vie exceptionnel avec une piscine à débordement, pour des vacances de luxe sur la Côte d''Azur. Cette villa spectaculaire allie design contemporain, matériaux nobles et équipements haut de gamme, pour un confort absolu. Profitez de la vue panoramique sur la mer depuis les terrasses ensoleillées, détendez-vous dans le jacuzzi en admirant le coucher de soleil, et savourez des instants de bonheur inoubliables entre amis ou en famille.', 'Luxe et bien-être sur la Côte d''Azur.', 1800, 400, 14, 7, 6, 10, 8, true, 2, 3, 3, 3), 
('Appartement de luxe avec vue sur Central Park', 'Un appartement de luxe avec une vue imprenable sur Central Park, pour des vacances d''exception à New York. Niché au cœur de Manhattan, cet appartement élégant et raffiné vous séduira par son design sophistiqué et ses prestations haut de gamme. Profitez du spectacle de la nature au cœur de la ville qui ne dort jamais, depuis le confort de votre salon ou de votre chambre à coucher.', 'Vue panoramique sur Central Park.', 2500, 500, 8, 4, 4, 6, 6, true, 3, 4, 1, 8), 
('Villa de luxe avec plage privée', 'Une villa d''exception avec plage privée, pour des vacances de rêve dans les Caraïbes. Nichée au cœur d''un jardin tropical luxuriant, cette villa spectaculaire offre des espaces de vie élégants, une piscine à débordement et un accès direct à une plage de sable blanc. Profitez du soleil, de la mer turquoise et des couchers de soleil romantiques, pour des vacances inoubliables en famille ou entre amis.', 'Luxe et farniente sous les tropiques.', 3000, 700, 18, 9, 8, 14, 12, true, 4, 5, 2, 9), 
('Chalet de montagne de luxe avec spa', 'Un chalet de montagne d''exception avec spa privé, pour des vacances inoubliables dans les Alpes suisses. Ce chalet luxueux allie charme alpin traditionnel et confort moderne, avec des matériaux nobles, un mobilier haut de gamme et des équipements dernier cri. Profitez du spa avec sauna, hammam et jacuzzi, détendez-vous près de la cheminée ou admirez la vue panoramique sur les sommets enneigés depuis la terrasse en savourant un verre de vin chaud.', 'Confort et bien-être au cœur des Alpes.', 2200, 450, 12, 6, 6, 10, 8, false, 5, 6, 4, 5);

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
(8, 'Canoë', 3),
(9, 'Golf', 1),
(9, 'Accrobranche', 2),
(9, 'Baignade', 3),
(10, 'Randonnée', 1),
(10, 'Plage', 2),
(10, 'Voile', 3),
(11, 'Canoë', 1),
(11, 'Golf', 2),
(11, 'Équitation', 3),
(12, 'Baignade', 1),
(12, 'Accrobranche', 2),
(12, 'Randonnée', 3),
(13, 'Plage', 1),
(13, 'Voile', 2),
(13, 'Canoë', 3),
(14, 'Golf', 1),
(14, 'Équitation', 2),
(14, 'Baignade', 3),
(15, 'Randonnée', 1),
(15, 'Accrobranche', 2),
(15, 'Plage', 3),
(16, 'Voile', 1),
(16, 'Canoë', 2),
(16, 'Golf', 3),
(17, 'Équitation', 1),
(17, 'Baignade', 2),
(17, 'Accrobranche', 3),
(18, 'Randonnée', 1),
(18, 'Plage', 2),
(18, 'Voile', 3),
(19, 'Canoë', 1),
(19, 'Golf', 2),
(19, 'Équitation', 3),
(20, 'Baignade', 1),
(20, 'Accrobranche', 2),
(20, 'Randonnée', 3),
(21, 'Plage', 1),
(21, 'Voile', 2),
(21, 'Canoë', 3),
(22, 'Golf', 1),
(22, 'Équitation', 2),
(22, 'Baignade', 3),
(23, 'Randonnée', 1),
(23, 'Accrobranche', 2),
(23, 'Plage', 3),
(24, 'Voile', 1),
(24, 'Canoë', 2),
(24, 'Golf', 3),
(25, 'Équitation', 1),
(25, 'Baignade', 2),
(25, 'Accrobranche', 3),
(26, 'Randonnée', 1),
(26, 'Plage', 2),
(26, 'Voile', 3),
(27, 'Canoë', 1),
(27, 'Golf', 2),
(27, 'Équitation', 3),
(28, 'Baignade', 1),
(28, 'Accrobranche', 2),
(28, 'Randonnée', 3),
(29, 'Plage', 1),
(29, 'Voile', 2),
(29, 'Canoë', 3),
(30, 'Golf', 1),
(30, 'Équitation', 2),
(30, 'Baignade', 3),
(31, 'Randonnée', 1),
(31, 'Accrobranche', 2),
(31, 'Plage', 3),
(32, 'Voile', 1),
(32, 'Canoë', 2),
(32, 'Golf', 3),
(33, 'Équitation', 1),
(33, 'Baignade', 2),
(33, 'Accrobranche', 3),
(34, 'Randonnée', 1),
(34, 'Plage', 2),
(34, 'Voile', 3),
(35, 'Canoë', 1),
(35, 'Golf', 2),
(35, 'Équitation', 3),
(36, 'Baignade', 1),
(36, 'Accrobranche', 2),
(36, 'Randonnée', 3),
(37, 'Plage', 1),
(37, 'Voile', 2),
(37, 'Canoë', 3),
(38, 'Golf', 1),
(38, 'Équitation', 2),
(38, 'Baignade', 3),
(39, 'Randonnée', 1),
(39, 'Accrobranche', 2),
(39, 'Plage', 3),
(40, 'Voile', 1),
(40, 'Canoë', 2),
(40, 'Golf', 3),
(41, 'Équitation', 1),
(41, 'Baignade', 2),
(41, 'Accrobranche', 3),
(42, 'Randonnée', 1),
(42, 'Plage', 2),
(42, 'Voile', 3),
(43, 'Canoë', 1),
(43, 'Golf', 2),
(43, 'Équitation', 3),
(44, 'Baignade', 1),
(44, 'Accrobranche', 2),
(44, 'Randonnée', 3),
(45, 'Plage', 1),
(45, 'Voile', 2),
(45, 'Canoë', 3);

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

INSERT INTO _reservation (date_debut, date_fin, nb_occupant, taxe_sejour, taxe_commission, prix_ht, prix_ttc, date_annulation, annulation, id_logement, id_client) VALUES
('2024-07-15', '2024-07-22', 6, 20.00, 15.00, 1200.00, 1400.00, NULL, false, 1, 23),
('2024-01-10', '2024-01-17', 4, 32.00, 12.00, 1200.00, 1244.00, NULL, false, 2, 24),
('2024-02-05', '2024-02-15', 3, 33.00, 15.00, 1500.00, 1548.00, '2023-12-10', true, 3, 25),
('2024-03-01', '2024-03-08', 2, 21.00, 7.00, 700.00, 728.00, NULL, false, 4, 26),
('2024-04-10', '2024-04-18', 1, 16.00, 4.00, 400.00, 420.00, '2024-02-10', true, 5, 27),
('2024-05-15', '2024-05-25', 5, 55.00, 25.00, 2500.00, 2580.00, NULL, false, 6, 28),
('2024-06-10', '2024-06-20', 6, 66.00, 30.00, 3000.00, 3096.00, NULL, false, 7, 29),
('2024-07-25', '2024-08-05', 4, 44.00, 20.00, 2000.00, 2064.00, '2024-05-25', true, 8, 30),
('2024-08-15', '2024-08-25', 3, 33.00, 15.00, 1500.00, 1548.00, NULL, false, 9, 31),
('2024-09-05', '2024-09-15', 2, 22.00, 10.00, 1000.00, 1032.00, NULL, false, 10, 32),
('2024-10-10', '2024-10-20', 5, 55.00, 25.00, 2500.00, 2580.00, NULL, false, 11, 33),
('2024-11-01', '2024-11-10', 6, 66.00, 30.00, 3000.00, 3096.00, NULL, false, 12, 34),
('2024-12-01', '2024-12-10', 4, 44.00, 20.00, 2000.00, 2064.00, '2024-10-01', true, 13, 35),
('2024-01-20', '2024-01-30', 3, 33.00, 15.00, 1500.00, 1548.00, NULL, false, 14, 36),
('2024-02-25', '2024-03-05', 2, 22.00, 10.00, 1000.00, 1032.00, NULL, false, 15, 37),
('2024-03-15', '2024-03-25', 1, 11.00, 5.00, 500.00, 516.00, NULL, false, 16, 38),
('2024-04-25', '2024-05-05', 5, 55.00, 25.00, 2500.00, 2580.00, NULL, false, 17, 39),
('2024-05-10', '2024-05-20', 4, 44.00, 20.00, 2000.00, 2064.00, NULL, false, 18, 40),
('2024-06-15', '2024-06-25', 3, 33.00, 15.00, 1500.00, 1548.00, NULL, false, 19, 41),
('2024-07-01', '2024-07-10', 2, 22.00, 10.00, 1000.00, 1032.00, NULL, false, 20, 42),
('2024-08-05', '2024-08-15', 6, 66.00, 30.00, 3000.00, 3096.00, NULL, false, 21, 23),
('2024-09-10', '2024-09-20', 5, 55.00, 25.00, 2500.00, 2580.00, NULL, false, 22, 24),
('2024-10-15', '2024-10-25', 4, 44.00, 20.00, 2000.00, 2064.00, NULL, false, 23, 25),
('2024-11-20', '2024-11-30', 3, 33.00, 15.00, 1500.00, 1548.00, NULL, false, 24, 26),
('2024-12-05', '2024-12-15', 2, 22.00, 10.00, 1000.00, 1032.00, NULL, false, 25, 27),
('2024-01-15', '2024-01-25', 1, 11.00, 5.00, 500.00, 516.00, NULL, false, 26, 28),
('2024-02-10', '2024-02-20', 6, 66.00, 30.00, 3000.00, 3096.00, NULL, false, 27, 29),
('2024-03-05', '2024-03-15', 5, 55.00, 25.00, 2500.00, 2580.00, NULL, false, 28, 30),
('2024-04-10', '2024-04-20', 4, 44.00, 20.00, 2000.00, 2064.00, NULL, false, 29, 31),
('2024-05-25', '2024-06-05', 3, 33.00, 15.00, 1500.00, 1548.00, NULL, false, 30, 32),
('2024-01-05', '2024-01-15', 3, 33.00, 15.00, 1500.00, 1548.00, NULL, false, 1, 23),
('2024-01-25', '2024-02-05', 2, 24.00, 12.00, 1200.00, 1236.00, NULL, false, 2, 24),
('2024-02-10', '2024-02-20', 4, 44.00, 20.00, 2000.00, 2064.00, NULL, false, 3, 25),
('2024-03-01', '2024-03-10', 1, 11.00, 5.00, 500.00, 516.00, NULL, false, 4, 26),
('2024-03-20', '2024-04-01', 6, 78.00, 30.00, 3000.00, 3108.00, NULL, false, 5, 27),
('2024-04-05', '2024-04-15', 5, 55.00, 25.00, 2500.00, 2580.00, NULL, false, 6, 28),
('2024-05-01', '2024-05-10', 3, 33.00, 15.00, 1500.00, 1548.00, NULL, false, 7, 29),
('2024-05-20', '2024-06-01', 4, 48.00, 24.00, 2400.00, 2472.00, NULL, false, 8, 30),
('2024-06-10', '2024-06-20', 2, 22.00, 10.00, 1000.00, 1032.00, NULL, false, 9, 31),
('2024-07-05', '2024-07-15', 6, 66.00, 30.00, 3000.00, 3096.00, NULL, false, 10, 32),
('2024-08-01', '2024-08-10', 4, 44.00, 20.00, 2000.00, 2064.00, NULL, false, 11, 33),
('2024-08-20', '2024-08-30', 5, 55.00, 25.00, 2500.00, 2580.00, NULL, false, 12, 34),
('2024-09-05', '2024-09-15', 1, 11.00, 5.00, 500.00, 516.00, NULL, false, 13, 35),
('2024-10-01', '2024-10-10', 3, 33.00, 15.00, 1500.00, 1548.00, NULL, false, 14, 36),
('2024-10-20', '2024-11-01', 2, 24.00, 12.00, 1200.00, 1236.00, NULL, false, 15, 37),
('2024-11-10', '2024-11-20', 6, 66.00, 30.00, 3000.00, 3096.00, NULL, false, 16, 38),
('2024-12-05', '2024-12-15', 4, 44.00, 20.00, 2000.00, 2064.00, NULL, false, 17, 39),
('2024-01-01', '2024-01-10', 2, 22.00, 10.00, 1000.00, 1032.00, NULL, false, 18, 40),
('2024-01-15', '2024-01-25', 5, 55.00, 25.00, 2500.00, 2580.00, NULL, false, 19, 41),
('2024-02-01', '2024-02-10', 3, 33.00, 15.00, 1500.00, 1548.00, NULL, false, 20, 42),
('2024-03-01', '2024-03-10', 4, 44.00, 20.00, 2000.00, 2064.00, NULL, false, 1, 23),
('2024-03-20', '2024-03-30', 6, 66.00, 30.00, 3000.00, 3096.00, NULL, false, 2, 24),
('2024-04-01', '2024-04-10', 2, 22.00, 10.00, 1000.00, 1032.00, NULL, false, 3, 25),
('2024-05-01', '2024-05-10', 5, 55.00, 25.00, 2500.00, 2580.00, NULL, false, 4, 26),
('2024-06-01', '2024-06-10', 3, 33.00, 15.00, 1500.00, 1548.00, NULL, false, 5, 27),
('2024-07-01', '2024-07-10', 4, 44.00, 20.00, 2000.00, 2064.00, NULL, false, 6, 28),
('2024-08-01', '2024-08-10', 6, 66.00, 30.00, 3000.00, 3096.00, NULL, false, 7, 29),
('2024-09-01', '2024-09-10', 2, 22.00, 10.00, 1000.00, 1032.00, NULL, false, 8, 30),
('2024-10-01', '2024-10-10', 3, 33.00, 15.00, 1500.00, 1548.00, NULL, false, 9, 31);

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
