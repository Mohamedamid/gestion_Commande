# create database 

CREATE DATABASE gestion_management;

USE gestion_management;

# create table usres

CREATE TABLE users (
	id int AUTO_INCREMENT PRIMARY KEY ,
	name varchar(20) not null ,
	email varchar(50) not null ,
	password varchar(50) not null ,
	type varchar(10) not null
);

# create table command

CREATE TABLE command (
	id_command int AUTO_INCREMENT PRIMARY KEY ,
	date_cmd date not null,
	idCleint int ,
	FOREIGN KEY (idCleint) REFERENCES users(id),
	price_total float not null
);

# craete table product

CREATE TABLE product (
	id_product int AUTO_INCREMENT PRIMARY KEY ,
	name varchar(50) not null ,
	description varchar(255) not null ,
	price float not null,
	quantity int not null
);

# create table product_management

CREATE TABLE product_management (
	idPM int not null PRIMARY KEY ,
	quantity int not null,
	idProduct int ,
	FOREIGN KEY (idProduct) REFERENCES product(id_product) ,
	idCommand int ,
	FOREIGN KEY (idCommand) REFERENCES command(id_command)
);