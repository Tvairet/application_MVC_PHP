USE `covoiturage_db`;


-- AGENCES (Insertions des villes)
INSERT INTO `agency` (`name`) VALUES
    ('Paris'),
    ('Lyon'),
    ('Marseille'),
    ('Toulouse'),
    ('Nice'),
    ('Nantes'),
    ('Strasbourg'),
    ('Montpellier'),
    ('Bordeaux'),
    ('Lille'),
    ('Rennes'),
    ('Reims');

-- EMPLOYES (Insertion des employés)
-- Mot de passe en clair pour TOUS les comptes : Password123!
-- Le mot de passe admin est différent
-- Hash bcrypt généré pour les mots de passe

INSERT INTO `user` (`last_name`, `first_name`, `email`, `phone`, `password`, `is_admin`) VALUES
    ('Admin', 'Super', 'admin@entreprise.com', '0102030405', '$2b$10$w3o33an9/Pznj.kTk5GeXeabAPNg/Ibij0rK3kNEavqOU3sVtZyH2', TRUE),
    ('Martin', 'Alexandre', 'alexandre.martin@email.fr', '0612345678', '$2b$10$w/fclmNWQM9WyQ/YgpYDJuDkekEdJu1DcWGNaRrCsRlY9duJUBzx2', FALSE),
    ('Dubois', 'Sophie', 'sophie.dubois@email.fr', '0698765432', '$2b$10$w/fclmNWQM9WyQ/YgpYDJuDkekEdJu1DcWGNaRrCsRlY9duJUBzx2', FALSE),
    ('Bernard', 'Julien', 'julien.bernard@email.fr', '0622446688', '$2b$10$w/fclmNWQM9WyQ/YgpYDJuDkekEdJu1DcWGNaRrCsRlY9duJUBzx2', FALSE),
    ('Moreau', 'Camille', 'camille.moreau@email.fr', '0611223344', '$2b$10$w/fclmNWQM9WyQ/YgpYDJuDkekEdJu1DcWGNaRrCsRlY9duJUBzx2',FALSE),
    ('Lefèvre', 'Lucie', 'lucie.lefevre@email.fr', '0777889900', '$2b$10$w/fclmNWQM9WyQ/YgpYDJuDkekEdJu1DcWGNaRrCsRlY9duJUBzx2', FALSE),
    ('Leroy', 'Thomas', 'thomas.leroy@email.fr', '0655443322', '$2b$10$w/fclmNWQM9WyQ/YgpYDJuDkekEdJu1DcWGNaRrCsRlY9duJUBzx2', FALSE),
    ('Roux', 'Chloé', 'chloe.roux@email.fr', '0633221199', '$2b$10$w/fclmNWQM9WyQ/YgpYDJuDkekEdJu1DcWGNaRrCsRlY9duJUBzx2', FALSE),
    ('Petit', 'Maxime', 'maxime.petit@email.fr', '0766778899', '$2b$10$w/fclmNWQM9WyQ/YgpYDJuDkekEdJu1DcWGNaRrCsRlY9duJUBzx2', FALSE),
    ('Garnier', 'Laura', 'laura.garnier@email.fr', '0688776655', '$2b$10$w/fclmNWQM9WyQ/YgpYDJuDkekEdJu1DcWGNaRrCsRlY9duJUBzx2', FALSE),
    ('Dupuis', 'Antoine', 'antoine.dupuis@email.fr', '0744556677', '$2b$10$w/fclmNWQM9WyQ/YgpYDJuDkekEdJu1DcWGNaRrCsRlY9duJUBzx2', FALSE),
    ('Lefebvre', 'Emma', 'emma.lefebvre@email.fr', '0699887766', '$2b$10$w/fclmNWQM9WyQ/YgpYDJuDkekEdJu1DcWGNaRrCsRlY9duJUBzx2', FALSE),
    ('Fontaine', 'Louis', 'louis.fontaine@email.fr', '0655667788', '$2b$10$w/fclmNWQM9WyQ/YgpYDJuDkekEdJu1DcWGNaRrCsRlY9duJUBzx2', FALSE),
    ('Chevalier', 'Clara', 'clara.chevalier@email.fr', '0788990011', '$2b$10$w/fclmNWQM9WyQ/YgpYDJuDkekEdJu1DcWGNaRrCsRlY9duJUBzx2', FALSE),
    ('Robin', 'Nicolas', 'nicolas.robin@email.fr', '0644332211', '$2b$10$w/fclmNWQM9WyQ/YgpYDJuDkekEdJu1DcWGNaRrCsRlY9duJUBzx2', FALSE),
    ('Gauthier', 'Marine', 'marine.gauthier@email.fr', '0677889922', '$2b$10$w/fclmNWQM9WyQ/YgpYDJuDkekEdJu1DcWGNaRrCsRlY9duJUBzx2', FALSE),
    ('Fournier', 'Pierre', 'pierre.fournier@email.fr', '0722334455', '$2b$10$w/fclmNWQM9WyQ/YgpYDJuDkekEdJu1DcWGNaRrCsRlY9duJUBzx2', FALSE),
    ('Girard', 'Sarah', 'sarah.girard@email.fr', '0688665544', '$2b$10$w/fclmNWQM9WyQ/YgpYDJuDkekEdJu1DcWGNaRrCsRlY9duJUBzx2', FALSE),
    ('Lambert', 'Hugo', 'hugo.lambert@email.fr', '0611223366', '$2b$10$w/fclmNWQM9WyQ/YgpYDJuDkekEdJu1DcWGNaRrCsRlY9duJUBzx2', FALSE),
    ('Masson', 'Julie', 'julie.masson@email.fr', '0733445566', '$2b$10$w/fclmNWQM9WyQ/YgpYDJuDkekEdJu1DcWGNaRrCsRlY9duJUBzx2', FALSE),
    ('Henry', 'Arthur', 'arthur.henry@email.fr', '0666554433', '$2b$10$w/fclmNWQM9WyQ/YgpYDJuDkekEdJu1DcWGNaRrCsRlY9duJUBzx2', FALSE);

-- TRAJETS
-- Un trajet passé et un trajet complet (0 place dispo) sont inclus pour
-- vérifier qu'ils n'apparaissent pas sur la page d'accueil.
-- id_agence / id_employe référencent l'ordre d'insertion ci-dessus
-- (1 = Paris ... 12 = Reims ; 1 = Alexandre Martin ... 20 = Arthur Henry).
-- ---------------------------------------------------------------------
INSERT INTO `ride`
    (`gdh_depart`, `gdh_arrivee`, `nb_places_total`, `nb_places_dispo`,
     `id_agence_depart`, `id_agence_arrivee`, `id_user`)
VALUES
    ('2026-08-20 08:00:00', '2026-08-20 12:30:00', 4, 2, 1, 2, 3),  -- PASSÉ : ne doit pas apparaître
    ('2026-08-27 07:30:00', '2026-08-27 11:00:00', 3, 0, 2, 1, 4),  -- COMPLET : ne doit pas apparaître
    ('2026-08-26 07:45:00', '2026-08-26 12:15:00', 4, 3, 1, 2, 2),
    ('2026-08-28 09:00:00', '2026-08-28 13:30:00', 3, 1, 4, 9, 5),
    ('2026-08-29 06:30:00', '2026-08-29 10:00:00', 4, 2, 9, 4, 6),
    ('2026-09-01 08:15:00', '2026-09-01 14:45:00', 4, 4, 3, 1, 7),
    ('2026-09-02 07:00:00', '2026-09-02 09:30:00', 3, 2, 6, 2, 8),
    ('2026-09-03 13:00:00', '2026-09-03 17:00:00', 4, 1, 2, 6, 9),
    ('2026-09-05 08:00:00', '2026-09-05 12:00:00', 4, 3, 1, 9, 10),
    ('2026-09-08 06:45:00', '2026-09-08 09:15:00', 3, 3, 4, 3, 11),
    ('2026-09-09 07:30:00', '2026-09-09 11:00:00', 4, 2, 5, 7, 12),
    ('2026-09-10 08:00:00', '2026-09-10 10:30:00', 3, 1, 10, 1, 13),
    ('2026-09-11 06:00:00', '2026-09-11 09:00:00', 4, 4, 8, 3, 14),
    ('2026-09-12 09:00:00', '2026-09-12 12:00:00', 4, 2, 11, 6, 15),
    ('2026-09-15 07:00:00', '2026-09-15 09:45:00', 3, 2, 12, 1, 16);