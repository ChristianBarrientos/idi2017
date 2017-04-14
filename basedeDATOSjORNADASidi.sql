CREATE TABLE usuarios (
     id_usuario INTEGER AUTO_INCREMENT NOT NULL,
     dni_usuario INTEGER ,
     nombre VARCHAR(500) NOT NULL,
     apellido VARCHAR(500) NOT NULL,
     usuario VARCHAR(100),
     filiación VARCHAR(100),
     correo VARCHAR(100) NOT NULL,
     permiso ENUM('USUARIO','EVALUADOR','ADMIN'),
     pass VARCHAR (50) NOT NULL,
     PRIMARY KEY (id_usuario)
     ) ENGINE=InnoDB;

CREATE TABLE archivo (
    id_archivo INTEGER AUTO_INCREMENT NOT NULL,
    nombre VARCHAR (100),
    ubicacion varchar(500),
    tipo VARCHAR (100),
    KEY (id_archivo)
    ) ENGINE=InnoDB;


CREATE TABLE trabajoFinal (
     id_trabajoFinal INTEGER AUTO_INCREMENT NOT NULL,
     id_archivo INTEGER NOT NULL,
     KEY (id_trabajoFinal),
     FOREIGN KEY (id_archivo) REFERENCES archivo(id_archivo) ON DELETE NO ACTION ON UPDATE CASCADE
     ) ENGINE=InnoDB;



CREATE TABLE resumen (
     id_resumen INTEGER AUTO_INCREMENT NOT NULL,
     id_archivo INTEGER NOT NULL,
     KEY (id_resumen),
     FOREIGN KEY (id_archivo) REFERENCES archivo(id_archivo) ON DELETE NO ACTION ON UPDATE CASCADE
     ) ENGINE=InnoDB;

CREATE TABLE titulo (
     id_titulo INTEGER AUTO_INCREMENT NOT NULL,
     area VARCHAR(100) NOT NULL,
     subarea VARCHAR(100) NOT NULL,
     titulo VARCHAR(100) NOT NULL,
     autores VARCHAR(100) NOT NULL,
     id_trabajoFinal INTEGER,
     id_usuario INTEGER NOT NULL,
     tipo VARCHAR (200),
     KEY (id_titulo),
     FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_trabajoFinal) REFERENCES trabajoFinal(id_trabajoFinal) ON DELETE NO ACTION ON UPDATE CASCADE
     ) ENGINE=InnoDB;

CREATE TABLE titulo_resumen (
     id_titulo_resumen INTEGER AUTO_INCREMENT NOT NULL,
     id_titulo INTEGER NOT NULL,
     id_resumen INTEGER NOT NULL,
     KEY (id_titulo_resumen),
     FOREIGN KEY (id_titulo) REFERENCES titulo(id_titulo) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_resumen) REFERENCES resumen(id_resumen) ON DELETE NO ACTION ON UPDATE CASCADE
     ) ENGINE=InnoDB;

CREATE TABLE evaluador_titulo(
     id_evaluador_titulo INTEGER AUTO_INCREMENT NOT NULL,
     id_titulo INTEGER NOT NULL,
     id_usuario INTEGER NOT NULL,
     condicion VARCHAR(100),
     KEY (id_evaluador_titulo),
     FOREIGN KEY (id_titulo) REFERENCES titulo(id_titulo) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE NO ACTION ON UPDATE CASCADE
    )ENGINE=InnoDB;

CREATE TABLE devolucion_titulo(
     id_devolucion_titulo INTEGER AUTO_INCREMENT NOT NULL,
     condicion VARCHAR(100) NOT NULL,
     id_titulo INTEGER NOT NULL,
     id_archivo INTEGER NOT NULL,
     id_evaluador INTEGER NOT NULL,
     KEY (id_devolucion_titulo),
     FOREIGN KEY (id_titulo) REFERENCES titulo(id_titulo) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_archivo) REFERENCES archivo(id_archivo) ON DELETE NO ACTION ON UPDATE CASCADE,
     FOREIGN KEY (id_evaluador) REFERENCES evaluador_titulo(id_usuario) ON DELETE NO ACTION ON UPDATE CASCADE
    )ENGINE=InnoDB;

CREATE TABLE pendientes (
     id_pendiente INTEGER AUTO_INCREMENT NOT NULL,
     dni_usuario INTEGER NOT NULL,
     nombre VARCHAR(100) NOT NULL,
     apellido VARCHAR(100) NOT NULL,
     correo VARCHAR(100) NOT NULL,
     PRIMARY KEY (id_pendiente)
     ) ENGINE=InnoDB;

INSERT INTO usuarios(id_usuario,dni_usuario,nombre,apellido,correo,permiso,pass) VALUES (0,'39014','Admin','Admin','$idi@tecno.com','ADMIN','202cb962ac59075b964b07152d234b70')
  

    