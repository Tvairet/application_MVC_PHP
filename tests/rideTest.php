<?php

declare(strict_types=1);

namespace Tests;

use App\Models\Agency;
use App\Models\Ride;
use App\Models\User;
use PHPUnit\Framework\TestCase;

/**
 * Teste les opérations d'écriture du modèle Ride (insert, update,
 * delete) directement sur la base covoiturage_db (cf. .env).
 *
 * Utilise deux agences existantes et un utilisateur existant du jeu
 * d'essai (cf. database/seed.sql) comme données de référence.
 */
class rideTest extends TestCase
{
    /**
     * Sous-classe locale exposant $pdo (protected dans Model), pour
     * pouvoir récupérer l'ID auto-incrémenté du trajet qu'on vient de
     * créer via lastInsertId() sur la même connexion.
     */
    private object $rideModel;

    private int $idAgenceDepart;
    private int $idAgenceArrivee;
    private int $idUser;

    /** @var int[] Trajets créés pendant le test, à nettoyer après. */
    private array $createdIds = [];

    protected function setUp(): void
    {
        $this->rideModel = new class extends Ride {
            public function pdoLastInsertId(): int
            {
                return (int) $this->pdo->lastInsertId();
            }
        };

        $agencies = (new Agency())->getAll();
        $this->assertGreaterThanOrEqual(
            2,
            count($agencies),
            'Il faut au moins 2 agences en base pour tester Ride (cf. seed.sql).'
        );
        $this->idAgenceDepart = (int) $agencies[0]['id_agency'];
        $this->idAgenceArrivee = (int) $agencies[1]['id_agency'];

        $user = (new User())->findByEmail('sophie.dubois@email.fr');
        $this->assertNotFalse(
            $user,
            "L'utilisateur de test sophie.dubois@email.fr doit exister (cf. seed.sql)."
        );
        $this->idUser = (int) $user['id_user'];
    }

    protected function tearDown(): void
    {
        foreach ($this->createdIds as $id) {
            $this->rideModel->delete($id);
        }
        $this->createdIds = [];
    }

    /**
     * @return array<string, mixed>
     */
    private function validRideData(): array
    {
        return [
            'gdh_depart'        => '2027-01-15 08:00:00',
            'gdh_arrivee'       => '2027-01-15 12:00:00',
            'nb_places_total'   => 4,
            'nb_places_dispo'   => 4,
            'id_agence_depart'  => $this->idAgenceDepart,
            'id_agence_arrivee' => $this->idAgenceArrivee,
            'id_user'           => $this->idUser,
        ];
    }

    /**
     * Ecriture : Ride::insert() doit créer un trajet retrouvable
     * ensuite, avec les bonnes valeurs.
     */
    public function testInsertCreatesNewRide(): void
    {
        $data = $this->validRideData();

        $result = $this->rideModel->insert($data);
        $this->assertTrue($result, 'L\'insertion du trajet doit réussir.');

        $id = $this->rideModel->pdoLastInsertId();
        $this->createdIds[] = $id;

        $ride = $this->rideModel->findById($id);
        $this->assertNotFalse($ride);
        $this->assertSame($this->idAgenceDepart, (int) $ride['id_agence_depart']);
        $this->assertSame($this->idAgenceArrivee, (int) $ride['id_agence_arrivee']);
        $this->assertSame($this->idUser, (int) $ride['id_user']);
        $this->assertSame(4, (int) $ride['nb_places_dispo']);
    }

    /**
     * Ecriture : Ride::update() doit modifier les places disponibles
     * (cas d'usage réel : l'auteur ajuste son trajet après un appel).
     */
    public function testUpdateModifiesExistingRide(): void
    {
        $data = $this->validRideData();
        $this->rideModel->insert($data);
        $id = $this->rideModel->pdoLastInsertId();
        $this->createdIds[] = $id;

        $updatedData = $data;
        $updatedData['nb_places_dispo'] = 2;

        $result = $this->rideModel->update($id, $updatedData);
        $this->assertTrue($result, 'La mise à jour du trajet doit réussir.');

        $ride = $this->rideModel->findById($id);
        $this->assertSame(2, (int) $ride['nb_places_dispo']);
        $this->assertSame(4, (int) $ride['nb_places_total']);
    }

    /**
     * Ecriture : Ride::delete() doit retirer définitivement le trajet.
     */
    public function testDeleteRemovesRide(): void
    {
        $data = $this->validRideData();
        $this->rideModel->insert($data);
        $id = $this->rideModel->pdoLastInsertId();

        $result = $this->rideModel->delete($id);
        $this->assertTrue($result, 'La suppression du trajet doit réussir.');

        $this->assertFalse(
            $this->rideModel->findById($id),
            'Le trajet supprimé ne doit plus être retrouvable.'
        );

        // Déjà supprimé : pas la peine que tearDown() retente.
    }
}
