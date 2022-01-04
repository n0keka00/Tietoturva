/* luontilauseet tietokantaan*/

create database n0keka00;
use n0keka00;

create table user_ (
    username varchar(50) primary key,
    password varchar(255) not null
);

create table user_info (
    username varchar(50) primary key,
    info varchar(50) not null
);