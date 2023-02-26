-- 3.- Creamos un usuario
create user 'usuario'@'localhost' identified by "clave";
-- 4.- Le damos permiso en la base de datos "proyecto"
grant all on proyecto.* to 'usuario'@'localhost';
flush privileges;