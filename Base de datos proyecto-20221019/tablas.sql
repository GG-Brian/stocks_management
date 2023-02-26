-- 2.- Creamos las tablas
-- 2.1.1.- Tabla tienda
use proyecto;
create table if not exists tiendas(
 id int auto_increment primary key,
 nombre varchar(100) not null,
 tlf varchar(13) null
);
-- 2.1.2 .- Tabla familia
create table if not exists familias(
 cod varchar(6) primary key,
 nombre varchar(200) not null
);
-- 2.1.3.- Tabla producto
create table if not exists productos(
 id int auto_increment primary key,
 nombre varchar(200) not null,
 nombre_corto varchar(50) unique not null,
 descripcion text null,
 pvp decimal(10, 2) not null,
 familia varchar(6) not null,
 constraint fk_prod_fam foreign key(familia) references familias(cod) on update
cascade on delete cascade
);
-- 2.1.4 Tabla stocks
create table if not exists stocks(
 producto int,
 tienda int,
 unidades int unsigned not null,
 constraint pk_stock primary key(producto, tienda),
 constraint fk_stock_prod foreign key(producto) references productos(id) on update
cascade on delete cascade,
 constraint fk_stock_tienda foreign key(tienda) references tiendas(id) on update
cascade on delete cascade
);
-- 2.1.4 Tabla usuario
create table if not exists usuarios(
 usuario VARCHAR(20) PRIMARY KEY,
 clave VARCHAR(64) NOT NULL,
 nombrecompleto VARCHAR(200) NOT NULL,
 correo VARCHAR(50) NOT NULL,
 colorfondo VARCHAR(7) NOT NULL DEFAULT '#EFF5F5',
 tipoletra VARCHAR(30) NOT NULL DEFAULT 'Arial'
);
