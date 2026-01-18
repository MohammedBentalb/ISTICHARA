-- Enum types
CREATE TYPE specialite_avocat AS ENUM (
    'Droit penal',
    'civil',
    'famille',
    'affaires'
);

CREATE TYPE type_acte_huissier AS ENUM (
    'Signification',
    'execution',
    'constats'
);

-- Table: villes
CREATE TABLE villes (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW()
);

-- Table: avocats
CREATE TABLE avocats (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    ville_id INT,
    years_of_experience INT,
    specialite specialite_avocat NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW(),
    CONSTRAINT fk_avocat_ville
        FOREIGN KEY (ville_id)
        REFERENCES villes(id)
        ON DELETE SET NULL
);

-- Table: huissiers
CREATE TABLE huissiers (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    ville_id INT,
    years_of_experience INT,
    types_actes type_acte_huissier NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW(),
    CONSTRAINT fk_huissier_ville
        FOREIGN KEY (ville_id)
        REFERENCES villes(id)
        ON DELETE SET NULL
);


CREATE TYPE user_role AS ENUM (
    'ADMIN',
    'DOCTOR',
    'GUEST'
);

CREATE EXTENSION IF NOT EXISTS "pgcrypto";

CREATE TABLE users (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    name VARCHAR(150) NOT NULL,
    email VARCHAR(180) NOT NULL UNIQUE,
    password TEXT NOT NULL,
    role user_role NOT NULL,
    refresh_token TEXT NULL
);
