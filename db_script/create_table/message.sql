use smarttoy;

drop table if exists `st_message`;
create table `st_message`(
`id` int(11) not null  primary key AUTO_INCREMENT,
`content` varchar(2000) default  ' ',
`from_user` varchar (30) not null  default  '',
`to_user` varchar (30) not null default  '',
`created_time` timestamp not null default now()
)ENGINE=INNODB DEFAULT charset = utf8;