CREATE DATABASE ipss_et;

CREATE USER 'ipss_et' @'localhost' IDENTIFIED BY 'l4cl4v3-1p55-3T';

GRANT ALL PRIVILEGES ON ipss_et.* TO 'ipss_et' @'localhost';

FLUSH PRIVILEGES;

USE ipss_et;

CREATE TABLE mantenedor (
    id INT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    activo BOOLEAN NOT NULL DEFAULT FALSE
);

SELECT
    id,
    nombre,
    activo
FROM
    mantenedor;

-- POST
INSERT INTO
    mantenedor (id, nombre)
VALUES
    (1, 'Ejemplo 1'),
    (2, 'Ejemplo 2'),
    (3, 'Ejemplo 3');

-- PATCH / ENABLE
UPDATE
    mantenedor
SET
    activo = true
WHERE
    id = 3;

-- PATCH / DISABLE
UPDATE
    mantenedor
SET
    activo = false
WHERE
    id = 3;

-- PUT
UPDATE
    mantenedor
SET
    nombre = 'Example 3'
WHERE
    id = 3;

-- DELETE
DELETE FROM
    mantenedor
WHERE
    id = 3;

-- EXAMEN TRANSVERSAL

CREATE TABLE carrusel(
    id INT PRIMARY KEY,
    imagen TEXT NOT NULL,
    titulo TEXT NOT NULL,
    descripcion TEXT NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT FALSE
);

INSERT INTO carrusel (id, imagen, titulo, descripcion, activo) VALUES 
(1, 'https://s1.1zoom.me/b3442/230/Footbal_Ball_Lawn_542686_1920x1080.jpg', 'Titulo 1', 'Descripción 1', true),
(2, 'https://s1.1zoom.me/big0/202/Footbal_Men_Fields_Sky_489036.jpg', 'Titulo 2', 'Descripción 2', false),
(3, 'https://wallpapers.com/images/hd/soccer-4k-odjp65awemx29xlx.jpg', 'Titulo 3', 'Descripción 3', true),
(4, 'https://www.1zoom.ru/big2/529/240992-Sepik.jpg', 'Titulo 4', 'Descripción 4', true);

CREATE TABLE nuestra_historia(
    id INT PRIMARY KEY,
    texto TEXT NOT NULL,
    imagen TEXT NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT FALSE
);

INSERT INTO nuestra_historia (id, texto, imagen, activo) VALUES 
(1, 'Texto Nuestra Historia...', 'https://img.freepik.com/vector-premium/equipo-ninos-futbol-dibujos-animados-estadio_29190-4982.jpg', false),
(2, 'Esta idea, nace entre un grupo de entrenadores que vieron la necesidad de entregar un espacio de entrenamiento futbolístico a los amantes de este deporte que quieren seguir aprendiendo y desarrollándose deportivamente además de pasar un buen rato jugando este hermoso deporte.', 'https://fc.sonkei.cl/images/sonkei/grupo_todocompetidor.png',true);

CREATE TABLE entrenamiento_lugar(
    id INT PRIMARY KEY,
    nombre TEXT NOT NULL,
    direccion TEXT NOT NULL,
    comuna TEXT NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT FALSE
);

INSERT INTO entrenamiento_lugar (id, nombre, direccion, comuna, activo) VALUES 
(1, 'Lugar Base', 'Calle Nombre #numero', 'Comuna', false),
(2, 'Bongo Club', 'América #670', 'San Bernardo', true);

CREATE TABLE entrenamientos_proximos(
    id INT PRIMARY KEY,
    fecha TEXT NOT NULL,
    hora TEXT NOT NULL,
    entrenamiento_lugar_id INT NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT FALSE,
    FOREIGN KEY (entrenamiento_lugar_id) REFERENCES entrenamiento_lugar (id)
);

INSERT INTO entrenamientos_proximos (id, fecha, hora, entrenamiento_lugar_id, activo) VALUES 
(1, '08/06/2020', '20:00 - 21:00', 1, false),
(2, '10/06/2024', '20:00 - 21:30', 2, true),
(3, '12/06/2024', '20:00 - 21:00', 2, true),
(4, '17/06/2024', '20:00 - 21:30', 2, true),
(5, '19/06/2024', '20:00 - 21:00', 2, true),
(6, '24/06/2024', '20:00 - 21:30', 2, true),
(7, '26/06/2024', '20:00 - 21:00', 2, true);

CREATE TABLE jugador_posicion(
    id INT PRIMARY KEY,
    nombre TEXT NOT NULL,
    abreviado TEXT NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT FALSE
);

INSERT INTO jugador_posicion (id, nombre, abreviado, activo) VALUES 
(1, 'Aguatero', 'AG', false),
(2, 'Portero', 'PO',true),
(3, 'Defensa', 'DEF',true),
(4, 'Mediocampista', 'MC',true),
(5, 'Delantero Centro', 'DC',true);

CREATE TABLE red_social (
    id INT PRIMARY KEY,
    nombre TEXT NOT NULL,
    icono TEXT NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT FALSE
);

INSERT INTO red_social (id, nombre, icono, activo) VALUES 
(1, 'Facebook', 'fa fa-facebook', true),
(2, 'Instagram', 'fa fa-instagram', true),
(3, 'Linkedin', 'fa fa-linkedin', true),
(4, 'Website', 'fa fa-globe', true);

CREATE TABLE jugador(
    id INT PRIMARY KEY,
    nombre TEXT NOT NULL,
    apellido TEXT NOT NULL,
    profesion TEXT NOT NULL,
    posicion_id INT NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT FALSE,
    FOREIGN KEY (posicion_id) REFERENCES jugador_posicion (id)
);

INSERT INTO jugador (id, nombre, apellido, profesion, posicion_id, activo) VALUES 
(1, 'Pedro', 'Postigo', 'Técnico eléctrico', 2, true),
(2, 'Gustavo', 'Ferreiro', 'Técnico Aire Acondicionados', 3, true),
(3, 'Antonio', 'Sánchez', 'Ingeniero Industrial', 3, true),
(4, 'Angel', 'Morón', 'Barbero', 4, true),
(5, 'Leonardo', 'Sarmiento', 'Chef', 4, true),
(6, 'Alberto', 'Dávila', 'Técnico Analista Programador', 5, true),
(7, 'Sebastián', 'Cabezas', 'Ingeniero en Computación e Informática', 5, true);

CREATE TABLE jugador_rrss (
    id INT PRIMARY KEY,
    jugador_id INT NOT NULL,
    red_social_id INT NOT NULL,
    valor TEXT NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT FALSE,
    FOREIGN KEY (jugador_id) REFERENCES jugador (id),
    FOREIGN KEY (red_social_id) REFERENCES red_social (id)
);

INSERT INTO jugador_rrss (id, jugador_id, red_social_id, valor, activo) VALUES 
(1, 1, 1, 'Facebook', true),
(2, 2, 1, 'Facebook', true),
(3, 3, 1, 'Facebook', true),
(4, 4, 1, 'Facebook', true),
(5, 5, 1, 'Facebook', true),
(6, 6, 1, 'Facebook', true),
(7, 7, 1, 'Facebook', true),
(8, 1, 1, 'Instagram', true),
(9, 2, 1, 'Instagram', true),
(10, 3, 1, 'Instagram', true),
(11, 4, 1, 'Instagram', true),
(12, 5, 1, 'Instagram', true),
(13, 6, 1, 'facInstagramebook', true);

CREATE TABLE redes_sociales (
    id INT PRIMARY KEY,
    nombre TEXT NOT NULL,
    icono TEXT NOT NULL,
    valor TEXT NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT FALSE
);

INSERT INTO redes_sociales (id, nombre, icono, valor, activo) VALUES 
(1, 'Facebook', 'fa fa-facebook', 'https://fc.sonkei.cl/#', true),
(2, 'Instagram', 'fa fa-instagram', 'https://fc.sonkei.cl/#', true);

CREATE TABLE sobre_nosotros (
    id INT PRIMARY KEY,
    logo_color TEXT NOT NULL,
    descripcion TEXT NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT FALSE
);

INSERT INTO sobre_nosotros (id, logo_color, descripcion, activo) VALUES (1, 'https://fc.sonkei.cl/images/logo_v2.webp', 'El respeto en el sentido de tratar a todos por igual sin importar su procedencia, religión, sexo, color de piel o estatus social. Consiste en hablar de forma cortés actuando siempre con amabilidad, en devolver una sonrisa, en procurar no molestar a otras personas con nuestros actos, en no anteponer el beneficio propio al ajeno.', true);