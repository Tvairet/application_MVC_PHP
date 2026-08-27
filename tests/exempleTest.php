<?php

declare(strict_types=1);

namespace Tests;

use App\Models\Agency;
use PHPUnit\Framework\TestCase;

/**
 * Test de démarrage : vérifie que l'environnement de test est
 * correctement configuré avant d'exécuter les tests métier
 * (agencyTest, rideTest).
 */
class exempleTest extends TestCase
{
    /**
     * Vérifie qu'un modèle peut être instancié (donc que la connexion
     * PDO héritée de Model s'établit sans lever d'exception).
     */
    public function testDatabaseConnectionIsAvailable(): void
    {
        $agency = new Agency();

        $this->assertInstanceOf(Agency::class, $agency);
    }

    /**
     * Vérifie qu'une requête simple aboutit, preuve que la base
     * `covoiturage_db` est bien accessible et que le schéma existe.
     */
    public function testAgencyTableIsReachable(): void
    {
        $agency = new Agency();

        $agencies = $agency->getAll();

        $this->assertIsArray($agencies);
    }
}
