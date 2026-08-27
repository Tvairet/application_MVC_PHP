<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Agency;
use App\Models\Ride;

/**
 * Class RideController
 * Contrôleur pour la gestion des trajets (Création, Edition, Suppression)
 */
class RideController extends Controller
{
    /**
     * Affiche le formulaire de création d'un nouveau trajet
     * 
     * @return void
     */
    public function create(): void
    {
        $agencyModel = new Agency();
        $agencies = $agencyModel->getAll();

        $this->render('ride/create', [
            'title' => 'Créer un trajet - Covoiturage Pro',
            'agencies' => $agencies
        ]);
    }

    /**
     * Traite la soumission du formulaire de création d'un trajet
     * Valide les données (agences, dates, places) et enregistre en BDD
     * 
     * @return void
     */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_agence_depart = filter_input(INPUT_POST, 'id_agence_depart', FILTER_VALIDATE_INT);
            $id_agence_arrivee = filter_input(INPUT_POST, 'id_agence_arrivee', FILTER_VALIDATE_INT);
            $gdh_depart = $_POST['gdh_depart'] ?? '';
            $gdh_arrivee = $_POST['gdh_arrivee'] ?? '';
            $nb_places_total = filter_input(INPUT_POST, 'nb_places_total', FILTER_VALIDATE_INT);

            // Validation des champs requis
            if (!$id_agence_depart || !$id_agence_arrivee || empty($gdh_depart) || empty($gdh_arrivee) || !$nb_places_total) {
                $_SESSION['flash_message'] = "Tous les champs sont requis et doivent être valides.";
                $_SESSION['flash_type'] = "danger";
                header('Location: ' . BASE_URL . '/ride/create');
                exit;
            }

            // Vérification que les agences sont différentes
            if ($id_agence_depart === $id_agence_arrivee) {
                $_SESSION['flash_message'] = "L'agence de départ et d'arrivée doivent être différentes.";
                $_SESSION['flash_type'] = "warning";
                header('Location: ' . BASE_URL . '/ride/create');
                exit;
            }

            // Vérification de la cohérence des dates
            $dep_time_obj = strtotime($gdh_depart);
            $arr_time_obj = strtotime($gdh_arrivee);
            $now = time();

            if ($dep_time_obj < $now) {
                $_SESSION['flash_message'] = "La date de départ ne peut pas être dans le passé.";
                $_SESSION['flash_type'] = "warning";
                header('Location: ' . BASE_URL . '/ride/create');
                exit;
            }

            if ($arr_time_obj <= $dep_time_obj) {
                $_SESSION['flash_message'] = "La date d'arrivée doit être ultérieure à la date de départ.";
                $_SESSION['flash_type'] = "warning";
                header('Location: ' . BASE_URL . '/ride/create');
                exit;
            }

            // Nombre de places positif
            if ($nb_places_total <= 0) {
                $_SESSION['flash_message'] = "Le nombre de places doit être supérieur à 0.";
                $_SESSION['flash_type'] = "warning";
                header('Location: ' . BASE_URL . '/ride/create');
                exit;
            }

            // Enregistrement en base
            $rideModel = new Ride();
            $success = $rideModel->insert([
                'gdh_depart' => $gdh_depart,
                'gdh_arrivee' => $gdh_arrivee,
                'nb_places_total' => $nb_places_total,
                'nb_places_dispo' => $nb_places_total, // Au départ, toutes les places sont disponibles
                'id_agence_depart' => $id_agence_depart,
                'id_agence_arrivee' => $id_agence_arrivee,
                'id_user' => $_SESSION['user']['id_user']
            ]);

            if ($success) {
                $_SESSION['flash_message'] = "Votre trajet a été publié avec succès !";
                $_SESSION['flash_type'] = "success";
                header('Location: ' . BASE_URL . '/');
            } else {
                $_SESSION['flash_message'] = "Une erreur est survenue lors de la création du trajet.";
                $_SESSION['flash_type'] = "danger";
                header('Location: ' . BASE_URL . '/ride/create');
            }
            exit;
        }
    }

    /**
     * Affiche le formulaire de modification d'un trajet existant
     * Vérifie que l'utilisateur est bien l'auteur du trajet
     * 
     * @param int $id ID du trajet à modifier
     * @return void
     */
    public function edit(int $id): void
    {
        $rideModel = new Ride();
        $ride = $rideModel->findById($id);

        if (!$ride || $ride['id_user'] != $_SESSION['user']['id_user']) {
            $_SESSION['flash_message'] = "Vous n'êtes pas autorisé à modifier ce trajet.";
            $_SESSION['flash_type'] = "danger";
            header('Location: ' . BASE_URL . '/');
            exit;
        }

        $agencyModel = new Agency();
        $agencies = $agencyModel->getAll();

        $this->render('ride/edit', [
            'title' => 'Modifier le trajet - Covoiturage Pro',
            'ride' => $ride,
            'agencies' => $agencies
        ]);
    }

    /**
     * Traite la mise à jour d'un trajet
     * Gère la cohérence du nombre de places si des réservations existent
     * 
     * @param int $id ID du trajet à mettre à jour
     * @return void
     */
    public function update(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rideModel = new Ride();
            $ride = $rideModel->findById($id);

            // Vérification de sécurité (propriété)
            if (!$ride || $ride['id_user'] != $_SESSION['user']['id_user']) {
                $_SESSION['flash_message'] = "Vous n'êtes pas autorisé à modifier ce trajet.";
                $_SESSION['flash_type'] = "danger";
                header('Location: ' . BASE_URL . '/');
                exit;
            }

            $id_agence_depart = filter_input(INPUT_POST, 'id_agence_depart', FILTER_VALIDATE_INT);
            $id_agence_arrivee = filter_input(INPUT_POST, 'id_agence_arrivee', FILTER_VALIDATE_INT);
            $gdh_depart = $_POST['gdh_depart'] ?? '';
            $gdh_arrivee = $_POST['gdh_arrivee'] ?? '';
            $nb_places_total = filter_input(INPUT_POST, 'nb_places_total', FILTER_VALIDATE_INT);

            if (!$id_agence_depart || !$id_agence_arrivee || empty($gdh_depart) || empty($gdh_arrivee) || !$nb_places_total) {
                $_SESSION['flash_message'] = "Tous les champs sont requis.";
                $_SESSION['flash_type'] = "danger";
                header('Location: ' . BASE_URL . "/ride/edit/{$id}");
                exit;
            }

            if ($id_agence_depart === $id_agence_arrivee) {
                $_SESSION['flash_message'] = "L'agence de départ et d'arrivée doivent être différentes.";
                $_SESSION['flash_type'] = "warning";
                header('Location: ' . BASE_URL . "/ride/edit/{$id}");
                exit;
            }

            // Calculer la différence de places pour ajuster les places disponibles
            $seatsDiff = $nb_places_total - $ride['nb_places_total'];
            $newAvailableSeats = $ride['nb_places_dispo'] + $seatsDiff;

            if ($newAvailableSeats < 0) {
                $_SESSION['flash_message'] = "Vous ne pouvez pas réduire le nombre de places autant car certaines sont déjà réservées.";
                $_SESSION['flash_type'] = "danger";
                header('Location: ' . BASE_URL . "/ride/edit/{$id}");
                exit;
            }

            $success = $rideModel->update($id, [
                'gdh_depart' => $gdh_depart,
                'gdh_arrivee' => $gdh_arrivee,
                'nb_places_total' => $nb_places_total,
                'nb_places_dispo' => $newAvailableSeats,
                'id_agence_depart' => $id_agence_depart,
                'id_agence_arrivee' => $id_agence_arrivee
            ]);

            if ($success) {
                $_SESSION['flash_message'] = "Le trajet a été mis à jour.";
                $_SESSION['flash_type'] = "success";
                header('Location: ' . BASE_URL . '/');
            } else {
                $_SESSION['flash_message'] = "Une erreur est survenue lors de la modification.";
                $_SESSION['flash_type'] = "danger";
                header('Location: ' . BASE_URL . "/ride/edit/{$id}");
            }
            exit;
        }
    }

    /**
     * Supprime un trajet (annule la proposition)
     * 
     * @param int $id ID du trajet à supprimer
     * @return void
     */
    public function delete(int $id): void
    {
        $rideModel = new Ride();
        $ride = $rideModel->findById($id);

        if (!$ride || $ride['id_user'] != $_SESSION['user']['id_user']) {
            $_SESSION['flash_message'] = "Vous n'êtes pas autorisé à supprimer ce trajet.";
            $_SESSION['flash_type'] = "danger";
            header('Location: ' . BASE_URL . '/');
            exit;
        }

        $success = $rideModel->delete($id);

        if ($success) {
            $_SESSION['flash_message'] = "Le trajet a été supprimé.";
            $_SESSION['flash_type'] = "info";
        } else {
            $_SESSION['flash_message'] = "Une erreur est survenue lors de la suppression.";
            $_SESSION['flash_type'] = "danger";
        }

        header('Location: ' . BASE_URL . '/');
        exit;
    }
}