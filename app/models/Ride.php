<?php

namespace App\Models;

use App\Core\Model;

/**
 * Class Ride
 * Modèle pour gérer les accès à la table `ride`
 */
class Ride extends Model
{
    /**
     * Récupère tous les trajets futurs possédant encore des places disponibles
     *
     * @return array<int, array<string, mixed>> Liste des trajets disponibles
     */
        public function getAvailableRides(): array
    {
        $sql = "
            SELECT 
                r.id_trajet, 
                r.gdh_depart, 
                r.gdh_arrivee, 
                r.nb_places_total, 
                r.nb_places_dispo,
                r.id_user,
                dep_agency.name as departure_agency_name,
                arr_agency.name as arrival_agency_name,
                u.first_name,
                u.last_name,
                u.phone,
                u.email
            FROM `ride` r
            INNER JOIN `agency` dep_agency ON r.id_agence_depart = dep_agency.id_agency
            INNER JOIN `agency` arr_agency ON r.id_agence_arrivee = arr_agency.id_agency
            INNER JOIN `user` u ON r.id_user = u.id_user
            WHERE r.nb_places_dispo > 0 
              AND r.gdh_depart > NOW()
            ORDER BY r.gdh_depart ASC
        ";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }


    /**
     * Insère un nouveau trajet en base de données
     *
     * @param array<string, mixed> $data Données du trajet (dates, places, agences, user)
     * @return bool True en cas de succès, false sinon
     */
    public function insert(array $data): bool
    {
        $sql = "INSERT INTO `ride` 
                (`gdh_depart`, `gdh_arrivee`, `nb_places_total`, `nb_places_dispo`, `id_agence_depart`, `id_agence_arrivee`, `id_user`) 
                VALUES 
                (:gdh_depart, :gdh_arrivee, :nb_places_total, :nb_places_dispo, :id_agence_depart, :id_agence_arrivee, :id_user)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'gdh_depart' => $data['gdh_depart'],
            'gdh_arrivee' => $data['gdh_arrivee'],
            'nb_places_total' => $data['nb_places_total'],
            'nb_places_dispo' => $data['nb_places_dispo'],
            'id_agence_depart' => $data['id_agence_depart'],
            'id_agence_arrivee' => $data['id_agence_arrivee'],
            'id_user' => $data['id_user']
        ]);
    }

    /**
     * Récupère un trajet spécifique par son identifiant unique
     *
     * @param int $id ID du trajet
     * @return array<string, mixed>|false Les données du trajet ou false
     */
    public function findById(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `ride` WHERE `id_trajet` = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Met à jour les informations d'un trajet existant
     *
     * @param int $id ID du trajet à modifier
     * @param array<string, mixed> $data Nouvelles données du trajet
     * @return bool True en cas de succès, false sinon
     */
    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE `ride` 
                SET `gdh_depart` = :gdh_depart, 
                    `gdh_arrivee` = :gdh_arrivee, 
                    `nb_places_total` = :nb_places_total, 
                    `nb_places_dispo` = :nb_places_dispo, 
                    `id_agence_depart` = :id_agence_depart, 
                    `id_agence_arrivee` = :id_agence_arrivee 
                WHERE `id_trajet` = :id";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'gdh_depart' => $data['gdh_depart'],
            'gdh_arrivee' => $data['gdh_arrivee'],
            'nb_places_total' => $data['nb_places_total'],
            'nb_places_dispo' => $data['nb_places_dispo'],
            'id_agence_depart' => $data['id_agence_depart'],
            'id_agence_arrivee' => $data['id_agence_arrivee'],
            'id' => $id
        ]);
    }

    /**
     * Supprime un trajet de la base de données
     *
     * @param int $id ID du trajet à supprimer
     * @return bool True en cas de succès, false sinon
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM `ride` WHERE `id_trajet` = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Récupère l'intégralité des trajets (pour l'administration) avec jointures
     *
     * @return array<int, array<string, mixed>> Liste de tous les trajets
     */
    public function getAllRides(): array
    {
        $sql = "
            SELECT 
                r.*,
                dep_agency.name as departure_agency_name,
                arr_agency.name as arrival_agency_name,
                u.first_name,
                u.last_name
            FROM `ride` r
            INNER JOIN `agency` dep_agency ON r.id_agence_depart = dep_agency.id_agency
            INNER JOIN `agency` arr_agency ON r.id_agence_arrivee = arr_agency.id_agency
            INNER JOIN `user` u ON r.id_user = u.id_user
            ORDER BY r.gdh_depart DESC
        ";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
}