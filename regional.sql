create database regional default character set utf8 collate utf8_general_ci;
set names 'utf8';
use regional;


create table user (
  id  int(11) unsigned not null auto_increment primary key comment '用户id',
  email varchar(128) not null comment '用户邮箱',
  name varchar(64) not null comment '用户姓名',
  passwd varchar(64) not null comment '密码',
  signature text comment '用户签名',
  region varchar(64) comment '用户归属地',
  avatar text comment '用户头像',
  unique(email)
)engine=innodb default charset=utf8;


create table region (
  id int(11) unsigned not null auto_increment primary key comment '地区id',
  name varchar(128) not null comment '地区名',
  introduction text not null comment '地区简介',
  dialect_id varchar(128) not null comment '方言id',
  image varchar (512) not null comment '地区图片',
  address varchar(256) not null comment '详细地址',
  index(dialect_id)
)engine=innodb default charset=utf8;


create table history (
  id int(11) unsigned not null auto_increment primary key comment '历史id',
  region_id int(11) unsigned not null comment '地区id',
  title varchar(256) not null comment '历史事件名称',
  content text not null comment '历史事件内容',
  image varchar (512) null comment '历史事件图片',
  index(region_id)
)engine=innodb default charset=utf8;


create table food (
  id int(11) unsigned not null auto_increment primary key comment '美食id',
  region_id int(11) unsigned not null comment '地区id',
  name varchar(128) not null comment '美食名称',
  content text not null comment '美食简介',
  image varchar(512) null comment '美食图片',
  index(region_id)
)engine=innodb default charset=utf8;


create table scenery (
  id int(11) unsigned not null auto_increment primary key comment '景点id',
  region_id int(11) unsigned not null comment '地区id',
  title varchar(128) not null comment '景点名称',
  content text not null comment '景点简介',
  image varchar(512) null comment '景点图片',
  index(region_id)
)engine=innodb default charset=utf8;


create table user_contribute (
  id int(11) unsigned not null auto_increment primary key comment '投稿文章id',
  user_id int(11) unsigned not null comment '用户id',
  region_id int(11) unsigned not null comment '地区id',
  tag_id int(11) unsigned not null comment '标签id',
  title varchar(128) not null comment '投稿标题',
  content text not null comment '投稿内容',
  index(region_id,tag_id)
)engine=innodb default charset=utf8;


create table tag (
  id int(4) unsigned not null auto_increment primary key comment '标签id',
  name varchar(16) not null comment 'tag名称'
)engine=innodb default charset=utf8;


create table dialect (
  id int(4) unsigned not null auto_increment primary key comment '方言id',
  name varchar(16) not null comment '方言名称'
)engine=innodb default charset=utf8;


create table user_focus (
  id int(11) unsigned not null auto_increment primary key comment '关注',
  user_id varchar(16) not null comment '用户id',
  region_id int(11) unsigned not null comment '关注地区id'
)engine=innodb default charset=utf8;

grant select , update, delete, insert on regional.* to regional@localhost identified by 'regional123';



