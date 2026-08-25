-- BD : MySQL / MariaDB
-- =====================================================================

CREATE DATABASE IF NOT EXISTS 'covoiturage_db' DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE 'covoiturage_db';

-- Suppression des tables existantes pour éviter les conflits lors des tests
DROP TABLE IF EXISTS `ride`;
DROP TABLE IF EXISTS `user`;
DROP TABLE IF EXISTS `agency`;

-- Table AGENCE
CREATE TABLE agency (
    `id_agency`   INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- Table EMPLOYE
-- Données extraites du SIRH : pas de création/modif/suppression prévue
-- côté application, seulement lecture + authentification.
-- ---------------------------------------------------------------------
CREATE TABLE employe (
    id_employe    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom           VARCHAR(100) NOT NULL,
    prenom        VARCHAR(100) NOT NULL,
    email         VARCHAR(190) NOT NULL,
    telephone     VARCHAR(20)  NOT NULL,
    mot_de_passe  VARCHAR(255) NOT NULL COMMENT 'hash bcrypt (password_hash PHP)',
    role          ENUM('user', 'admin') NOT NULL DEFAULT 'user',

    CONSTRAINT uq_employe_email UNIQUE (email)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Table TRAJET
-- Chaque trajet a un auteur (employe), une agence de départ et une
-- agence d'arrivée. Les deux agences doivent être différentes et
-- l'arrivée doit être postérieure au départ.
-- ---------------------------------------------------------------------
CREATE TABLE trajet (
    id_trajet          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    gdh_depart         DATETIME NOT NULL,
    gdh_arrivee        DATETIME NOT NULL,
    nb_places_total    TINYINT UNSIGNED NOT NULL,
    nb_places_dispo    TINYINT UNSIGNED NOT NULL,
    id_agence_depart   INT UNSIGNED NOT NULL,
    id_agence_arrivee  INT UNSIGNED NOT NULL,
    id_employe         INT UNSIGNED NOT NULL,

    CONSTRAINT fk_trajet_agence_depart
        FOREIGN KEY (id_agence_depart) REFERENCES agence(id_agence)
        ON UPDATE CASCADE ON DELETE RESTRICT,

    CONSTRAINT fk_trajet_agence_arrivee
        FOREIGN KEY (id_agence_arrivee) REFERENCES agence(id_agence)
        ON UPDATE CASCADE ON DELETE RESTRICT,

    CONSTRAINT fk_trajet_employe
        FOREIGN KEY (id_employe) REFERENCES employe(id_employe)
        ON UPDATE CASCADE ON DELETE RESTRICT,

    CONSTRAINT chk_trajet_agences_differentes
        CHECK (id_agence_depart <> id_agence_arrivee),

    CONSTRAINT chk_trajet_dates_coherentes
        CHECK (gdh_arrivee > gdh_depart),

    CONSTRAINT chk_trajet_places_total_positif
        CHECK (nb_places_total > 0),

    CONSTRAINT chk_trajet_places_dispo_coherentes
        CHECK (nb_places_dispo >= 0 AND nb_places_dispo <= nb_places_total)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Index utiles aux requêtes fréquentes de l'application
-- ---------------------------------------------------------------------
-- Page d'accueil : tri par date de départ croissante + filtre "places restantes"
CREATE INDEX idx_trajet_gdh_depart ON trajet (gdh_depart);
CREATE INDEX idx_trajet_places_dispo ON trajet (nb_places_dispo);

-- Recherche des trajets d'un employé ("mes trajets")
CREATE INDEX idx_trajet_employe ON trajet (id_employe);

-- Jointures sur les agences
CREATE INDEX idx_trajet_agence_depart ON trajet (id_agence_depart);
CREATE INDEX idx_trajet_agence_arrivee ON trajet (id_agence_arrivee);