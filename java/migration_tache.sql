-- ============================================================
-- migration_tache.sql
-- SQL script to add the `tache` table to the database.
--
-- Run this once on your MySQL/MariaDB database:
--   mysql -u root -p gestion_de_project < migration_tache.sql
--
-- This does NOT touch any existing tables.
-- ============================================================

USE gestion_de_project;

-- Create the `tache` table
-- This table stores all tasks created by encadrants
CREATE TABLE IF NOT EXISTS tache (

    -- Auto-incremented primary key
    Id_Tache INT NOT NULL AUTO_INCREMENT PRIMARY KEY,

    -- Task title (required)
    titre_tache VARCHAR(255) NOT NULL,

    -- Full description / instructions for the student
    description_tache TEXT,

    -- The deadline for submission
    date_limite_tache DATE,

    -- The group this task is assigned to
    -- Links to the `groupe` table (existing table)
    Id_Groupe INT NOT NULL,

    -- The encadrant who created this task
    -- Links to the `encadrant` table (existing table)
    Id_Encad INT NOT NULL,

    -- Status of the task:
    --   'en attente' = waiting for student submission
    --   'soumis'     = student submitted a file
    --   'accepte'    = encadrant accepted the submission
    --   'refuse'     = encadrant refused the submission
    statut_tache ENUM('en attente', 'soumis', 'accepte', 'refuse') NOT NULL DEFAULT 'en attente',

    -- Name of the uploaded PDF file (filled when student submits)
    fichier_soumis VARCHAR(255) DEFAULT NULL,

    -- When the task was created
    date_creation_tache DATETIME DEFAULT NULL,

    -- When the student submitted their file
    date_soumission_tache DATETIME DEFAULT NULL,

    -- Foreign key: links to groupe table
    CONSTRAINT fk_tache_groupe
        FOREIGN KEY (Id_Groupe) REFERENCES groupe(Id_Groupe)
        ON DELETE CASCADE,

    -- Foreign key: links to encadrant table
    CONSTRAINT fk_tache_encadrant
        FOREIGN KEY (Id_Encad) REFERENCES encadrant(Id_Encad)
        ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- Verify the table was created
-- ============================================================
DESCRIBE tache;
