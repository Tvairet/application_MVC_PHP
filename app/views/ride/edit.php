<?php ob_start(); ?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-secondary text-white py-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fa-solid fa-pen-to-square me-2"></i>Modifier le trajet</h4>
                <span class="badge bg-light text-dark">ID: <?= $ride['id_trajet'] ?></span>
            </div>
            <div class="card-body p-4">
                <form action="<?= BASE_URL ?>/ride/update/<?= $ride['id_trajet'] ?>" method="POST">
                    
                    <div class="alert alert-warning mb-4">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i><strong>Attention :</strong> Si vous réduisez le nombre de places, assurez-vous de ne pas dépasser le nombre de places déjà réservées.
                    </div>

                    <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">Détails du trajet</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="id_agence_depart" class="form-label fw-bold">Agence de départ</label>
                            <select class="form-select" id="id_agence_depart" name="id_agence_depart" required>
                                <option value="">Choisir une ville...</option>
                                <?php foreach ($agencies as $agency): ?>
                                    <option value="<?= $agency['id_agency'] ?>" <?= $agency['id_agency'] == $ride['id_agence_depart'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($agency['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="id_agence_arrivee" class="form-label fw-bold">Agence d'arrivée</label>
                            <select class="form-select" id="id_agence_arrivee" name="id_agence_arrivee" required>
                                <option value="">Choisir une ville...</option>
                                <?php foreach ($agencies as $agency): ?>
                                    <option value="<?= $agency['id_agency'] ?>" <?= $agency['id_agency'] == $ride['id_agence_arrivee'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($agency['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="gdh_depart" class="form-label fw-bold">Date et heure de départ</label>
                            <!-- Format de la date pour le datetime-local : YYYY-MM-DDThh:mm -->
                            <input type="datetime-local" class="form-control" id="gdh_depart" name="gdh_depart" 
                                   value="<?= date('Y-m-d\TH:i', strtotime($ride['gdh_depart'])) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="gdh_arrivee" class="form-label fw-bold">Date et heure d'arrivée</label>
                            <input type="datetime-local" class="form-control" id="gdh_arrivee" name="gdh_arrivee" 
                                   value="<?= date('Y-m-d\TH:i', strtotime($ride['gdh_arrivee'])) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="nb_places_total" class="form-label fw-bold">Nombre total de places</label>
                            <input type="number" class="form-control" id="nb_places_total" name="nb_places_total" min="1" max="9" 
                                   value="<?= $ride['nb_places_total'] ?>" required>
                            <small class="text-muted d-block mt-1">Actuellement : <?= $ride['nb_places_dispo'] ?> place(s) libre(s)</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?= BASE_URL ?>/" class="btn btn-outline-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary fw-bold"><i class="fa-solid fa-save me-2"></i>Enregistrer les modifications</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/../layouts/main.php'; 
?>