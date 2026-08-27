<?php

declare(strict_types=1);

namespace Tests;

use App\Models\Agency;
use PHPUnit\Framework\TestCase;

/**
 * Teste les opérations d'écriture du modèle Agency (create, update,
 * delete) directement sur la base covoiturage_db (cf. .env).
 *
 * Chaque test crée ses propres données (nom unique via uniqid()) et
 * les nettoie dans tearDown(), pour ne pas polluer le jeu d'essai ni
 * dépendre de l'ordre d'exécution des tests.
 */
class agencyTest extends TestCase
{
    private Agency $agencyModel;

    /** @var int[] Identifiants créés pendant le test, à nettoyer après. */
    private array $createdIds = [];

    protected function setUp(): void
    {
        $this->agencyModel = new Agency();
    }

    protected function tearDown(): void
    {
        foreach ($this->createdIds as $id) {
            $this->agencyModel->delete($id);
        }
        $this->createdIds = [];
    }

    /**
     * Ecriture : Agency::create() doit insérer une nouvelle ligne
     * récupérable ensuite via getAll().
     */
    public function testCreateInsertsNewAgency(): void
    {
        $name = 'Ville de Test ' . uniqid();

        $result = $this->agencyModel->create($name);
        $this->assertTrue($result, 'La création de l\'agence doit réussir.');

        $created = $this->findByName($name);
        $this->assertNotFalse($created, 'L\'agence créée doit être retrouvable.');
        $this->createdIds[] = (int) $created['id_agency'];

        $this->assertSame($name, $created['name']);
    }

    /**
     * Ecriture : Agency::update() doit modifier le nom d'une agence
     * existante, sans changer son identifiant.
     */
    public function testUpdateModifiesExistingAgency(): void
    {
        $originalName = 'Ville Avant ' . uniqid();
        $this->agencyModel->create($originalName);
        $created = $this->findByName($originalName);
        $id = (int) $created['id_agency'];
        $this->createdIds[] = $id;

        $newName = 'Ville Après ' . uniqid();
        $result = $this->agencyModel->update($id, $newName);
        $this->assertTrue($result, 'La mise à jour doit réussir.');

        $updated = $this->agencyModel->getById($id);
        $this->assertNotFalse($updated);
        $this->assertSame($newName, $updated['name']);
        $this->assertSame($id, (int) $updated['id_agency']);
    }

    /**
     * Ecriture : Agency::delete() doit retirer définitivement la ligne.
     */
    public function testDeleteRemovesAgency(): void
    {
        $name = 'Ville à Supprimer ' . uniqid();
        $this->agencyModel->create($name);
        $created = $this->findByName($name);
        $id = (int) $created['id_agency'];

        $result = $this->agencyModel->delete($id);
        $this->assertTrue($result, 'La suppression doit réussir.');

        $this->assertFalse(
            $this->agencyModel->getById($id),
            'L\'agence supprimée ne doit plus être retrouvable.'
        );

        // Déjà supprimée : pas la peine que tearDown() retente.
    }

    /**
     * @return array<string, mixed>|false
     */
    private function findByName(string $name): array|false
    {
        foreach ($this->agencyModel->getAll() as $agency) {
            if ($agency['name'] === $name) {
                return $agency;
            }
        }

        return false;
    }
}
