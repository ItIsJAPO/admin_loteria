create database nipf character set utf8 collate utf8_general_ci;

use nipf;

set foreign_key_checks = 0;

drop table if exists roles;
create table roles (
	id int unsigned not null primary key,
  name varchar(100) not null
) character set utf8;

insert into roles values (1, 'Administrador');

drop table if exists login;
create table login (
	id int unsigned not null primary key auto_increment,
    user_theme int UNSIGNED DEFAULT null COMMENT'1= default 2=verde bosque 3=azul cielo 4=mora azul 5=rosa flamingo 6=nocturno',
    token varchar(100),
    role_id int unsigned not null,
    created datetime not null,
    username varchar(250) not null unique,
    password text,
    status tinyint(1) unsigned not null comment '1: activa, 2:bloqueado, 3: eliminado',
    
    foreign key (role_id) references roles(id)
) character set utf8;
INSERT INTO `login` (`id`,`user_theme`,`token`,`role_id`,`created`,`username`,`password`,`status`)
VALUES (1,1,'que sad',1,'2018-09-09 13:13:20','admin@grupoicarus.com.mx','$P$BQ7WWadGWzFiIgqVBGpCIV9U0wi4eN0',1);

drop table if exists users;
create table users (
	id int unsigned not null primary key auto_increment,
    login_id int unsigned not null,
    name varchar(100) not null,
    last_name varchar(100),
    profile_picture varchar(250) comment 'foto de perfil',
    
    foreign key (login_id) references login(id)
) character set utf8;
INSERT INTO `users` (`id`,`login_id`,`name`,`last_name`,`profile_picture`) VALUES (1,1,'Admin','total',NULL);

drop table if exists log;
create table log (
	identifier varchar(60) not null unique,
	user_id int unsigned,
    params_post text not null,
    params_get text not null,
    action varchar(300) not null,
    ip varchar(20) not null,
    url text not null,
    created datetime not null,
    foreign key (user_id) references users(id)
);

set foreign_key_checks = 1;