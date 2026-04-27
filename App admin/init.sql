CREATE DATABASE IF NOT EXISTS arvutipood CHARACTER SET utf8mb4;
USE arvutipood;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nimi VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    parool VARCHAR(255) NOT NULL,
    roll ENUM('admin','kasutaja') DEFAULT 'kasutaja',
    loodud DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tooted (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nimi VARCHAR(200) NOT NULL,
    kirjeldus TEXT,
    hind DECIMAL(10,2) NOT NULL,
    laos INT DEFAULT 0,
    kategooria VARCHAR(100),
    loodud DATETIME DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (nimi, email, parool, roll) VALUES
('Admin', 'admin@arvutipood.ee', 'admin123', 'admin'),
('Mari Maasikas', 'mari@example.com', 'parool123', 'kasutaja');

INSERT INTO tooted (nimi, kirjeldus, hind, laos, kategooria) VALUES
('Dell XPS 15', 'Sulearvuti Intel i7 16GB RAM', 1299.99, 5, 'Sulearvutid'),
('Samsung 27 monitor', '4K UHD monitor', 459.99, 12, 'Monitorid'),
('Logitech MX Master 3', 'Juhtmevaba hiir', 89.99, 25, 'Lisaseadmed');
