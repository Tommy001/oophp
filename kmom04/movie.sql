CREATE DATABASE IF NOT EXISTS toja14;

USE toja14;

SET NAMES 'utf8';

--
-- Drop all tables in the right order.
--
DROP TABLE IF EXISTS op_k4_Movie2Genre;
DROP TABLE IF EXISTS op_k4_Genre;
DROP TABLE IF EXISTS op_k4_User;
DROP TABLE IF EXISTS op_k4_Movie;


--
-- Create table for my own movie database
--
DROP TABLE IF EXISTS op_k4_Movie;
CREATE TABLE op_k4_Movie
(
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    title VARCHAR(100) NOT NULL,
    director VARCHAR(100),
    length INT DEFAULT NULL, -- Length in minutes
    year INT NOT NULL DEFAULT 1900,
    plot TEXT, -- Short intro to the movie
    image VARCHAR(100) DEFAULT NULL, -- Link to an image
    subtext CHAR(3) DEFAULT NULL, -- swe, fin, en, etc
    speech CHAR(3) DEFAULT NULL, -- swe, fin, en, etc
    quality CHAR(3) DEFAULT NULL,
    format CHAR(3) DEFAULT NULL -- mp4, divx, etc
) ENGINE INNODB CHARACTER SET utf8;


INSERT INTO op_k4_Movie (title, year, image) VALUES
    ('Pulp fiction', 1994, 'img/movie/pulp-fiction.jpg'),
    ('American Pie', 1999, 'img/movie/american-pie.jpg'),
    ('Pokémon The Movie 2000', 1999, 'img/movie/pokemon.jpg'),    
    ('Kopps', 2003, 'img/movie/kopps.jpg'),
    ('From Dusk Till Dawn', 1996, 'img/movie/from-dusk-till-dawn.jpg')
;


--
-- Add tables for genre
--
DROP TABLE IF EXISTS op_k4_Genre;
CREATE TABLE op_k4_Genre
(
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    name CHAR(20) NOT NULL -- crime, svenskt, college, drama, etc
) ENGINE INNODB CHARACTER SET utf8;

INSERT INTO op_k4_Genre (name) VALUES 
    ('comedy'), ('romance'), ('college'), 
    ('crime'), ('drama'), ('thriller'), 
    ('animation'), ('adventure'), ('family'), 
    ('svenskt'), ('action'), ('horror')
;

DROP TABLE IF EXISTS op_k4_Movie2Genre;
CREATE TABLE op_k4_Movie2Genre
(
    idMovie INT NOT NULL,
    idGenre INT NOT NULL,

    FOREIGN KEY (idMovie) REFERENCES op_k4_Movie (id),
    FOREIGN KEY (idGenre) REFERENCES op_k4_Genre (id),

    PRIMARY KEY (idMovie, idGenre)
) ENGINE INNODB;


INSERT INTO op_k4_Movie2Genre (idMovie, idGenre) VALUES
    (1, 1),
    (1, 5),
    (1, 6),
    (2, 1),
    (2, 2),
    (2, 3),
    (3, 7),    
    (3, 8),    
    (3, 9),    
    (4, 11),
    (4, 1),
    (4, 10),
    (4, 9),
    (5, 11),
    (5, 4),
    (5, 12)
;

DROP VIEW IF EXISTS op_k4_VMovie;

CREATE VIEW op_k4_VMovie
AS
SELECT 
    M.*,
    GROUP_CONCAT(G.name) AS genre
FROM op_k4_Movie AS M
    LEFT OUTER JOIN op_k4_Movie2Genre AS M2G
        ON M.id = M2G.idMovie

    LEFT OUTER JOIN op_k4_Genre AS G
         ON M2G.idGenre = G.id
GROUP BY M.id
;


--
-- Table for user
--
DROP TABLE IF EXISTS op_k4_User;

CREATE TABLE op_k4_User
(
  id INT AUTO_INCREMENT PRIMARY KEY,
  acronym CHAR(12) UNIQUE NOT NULL,
  name VARCHAR(80),
  password CHAR(32),
  salt INT NOT NULL
) ENGINE INNODB CHARACTER SET utf8;

INSERT INTO op_k4_User (acronym, name, salt) VALUES 
  ('doe', 'John/Jane Doe', unix_timestamp()),
  ('admin', 'Administrator', unix_timestamp())
;

UPDATE op_k4_User SET password = md5(concat('doe', salt)) WHERE acronym = 'doe';
UPDATE op_k4_User SET password = md5(concat('admin', salt)) WHERE acronym = 'admin';
