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


CREATE TYPE user_role as ENUM (
	'ADMIN',
	'GUEST'
);


CREATE TABLE users(
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role user_role DEFAULT 'GUEST',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);