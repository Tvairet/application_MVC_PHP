-- BD : MySQL / MariaDB
-- =====================================================================

CREATE DATABASE IF NOT EXISTS `covoiturage_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `covoiturage_db`;

-- Suppression des tables existantes pour éviter les conflits lors des tests
DROP TABLE IF EXISTS `ride`;
DROP TABLE IF EXISTS `user`;
DROP TABLE IF EXISTS `agency`;

-- Table AGENCE
CREATE TABLE agency (
    `id_agency`   INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table USER

CREATE TABLE `user` (
    `id_user` INT AUTO_INCREMENT PRIMARY KEY,
    `first_name` VARCHAR(100) NOT NULL,
    `last_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `phone` VARCHAR(20) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `is_admin` BOOLEAN NOT NULL DEFAULT FALSE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table TRAJET

CREATE TABLE `ride` (
    `id_trajet`          INT AUTO_INCREMENT PRIMARY KEY,
    `gdh_depart`         DATETIME NOT NULL,
    `gdh_arrivee`       DATETIME NOT NULL,
    `nb_places_total`    INT NOT NULL,
    `nb_places_dispo`    INT NOT NULL,
    `id_agence_depart`   INT NOT NULL,
    `id_agence_arrivee`  INT NOT NULL,
    `id_user`         INT NOT NULL,

    CONSTRAINT `fk_trajet_agence_depart`
        FOREIGN KEY (`id_agence_depart`) REFERENCES `agency` (`id_agency`)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT `fk_trajet_agence_arrivee`
        FOREIGN KEY (`id_agence_arrivee`) REFERENCES `agency` (`id_agency`)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT `fk_trajet_employe`
        FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT `chk_trajet_agences_differentes`
        CHECK (`id_agence_depart` <> `id_agence_arrivee`),

    CONSTRAINT `chk_trajet_dates_coherentes`
        CHECK (`gdh_arrivee` > `gdh_depart`),

    CONSTRAINT `chk_trajet_places_total_positif`
        CHECK (`nb_places_total` > 0),

    CONSTRAINT `chk_trajet_places_dispo_coherentes`
        CHECK (`nb_places_dispo` >= 0 AND `nb_places_dispo` <= `nb_places_total`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Index utiles aux requêtes fréquentes de l'application

-- Page d'accueil : tri par date de départ croissante + filtre "places restantes"
CREATE INDEX idx_trajet_gdh_depart ON ride (gdh_depart);
CREATE INDEX idx_trajet_places_dispo ON ride (nb_places_dispo);

-- Recherche des trajets d'un employé ("mes trajets")
CREATE INDEX idx_trajet_user ON ride (id_user);

-- Jointures sur les agences
CREATE INDEX idx_trajet_agence_depart ON ride (id_agence_depart);
CREATE INDEX idx_trajet_agence_arrivee ON ride (id_agence_arrivee);