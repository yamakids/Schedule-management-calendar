create database todo_calendar;
grant all on todo_calendar.* to dbuser@localhost identified by '********';
use todo_calendar;

create table users (
    id int not null auto_increment,
    name varchar(255) unique,
    email varchar(255) unique,
    password varchar(255),
    created datetime,
    modified datetime,
    primary key(id)
);

