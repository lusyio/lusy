create table chat
(
  message_id int auto_increment
    primary key,
  text text null,
  author_id int null,
  datetime int null
);
