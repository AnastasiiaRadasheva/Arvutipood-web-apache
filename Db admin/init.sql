CREATE TABLE IF NOT EXISTS tooted (
  id SERIAL PRIMARY KEY,
  nimi VARCHAR(100) NOT NULL,
  kirjeldus TEXT,
  hind DECIMAL(10,2) NOT NULL,
  kogus INTEGER DEFAULT 0,
  photo VARCHAR(255) DEFAULT 'https://placehold.co/300x200?text=Toode',
  loodud TIMESTAMP DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS users (
  id SERIAL PRIMARY KEY,
  nimi VARCHAR(100) NOT NULL,
  email VARCHAR(150) UNIQUE NOT NULL,
  parool VARCHAR(255) NOT NULL,
  roll VARCHAR(20) DEFAULT 'user',
  loodud TIMESTAMP DEFAULT NOW()
);

CREATE ROLE admin_grupp;
CREATE ROLE editor_grupp;
CREATE ROLE lugemis_grupp;

GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO admin_grupp;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO admin_grupp;
GRANT SELECT, INSERT, UPDATE ON ALL TABLES IN SCHEMA public TO editor_grupp;
GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA public TO editor_grupp;
GRANT SELECT ON ALL TABLES IN SCHEMA public TO lugemis_grupp;

CREATE USER peakasutaja WITH PASSWORD 'admin_parool';
GRANT admin_grupp TO peakasutaja;
CREATE USER sisestaja WITH PASSWORD 'editor_parool';
GRANT editor_grupp TO sisestaja;
CREATE USER tavakasutaja WITH PASSWORD 'user_parool';
GRANT lugemis_grupp TO tavakasutaja;

INSERT INTO tooted (nimi, kirjeldus, hind, kogus) VALUES
  ('Sülearvuti Lenovo', 'Intel Core i5, 16GB RAM', 899.99, 5),
  ('Gaming Hiir Logitech', 'RGB, 25600 DPI', 79.99, 12),
  ('Mehaaniline Klaviatuur', 'Cherry MX Blue', 129.99, 8);

INSERT INTO users (nimi, email, parool, roll) VALUES
  ('Admin User', 'admin@poodplus.ee', 'admin123', 'admin'),
  ('Test Kasutaja', 'test@poodplus.ee', 'user123', 'user');
