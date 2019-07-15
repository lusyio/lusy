create table chat
(
  message_id int auto_increment
    primary key,
  text text null,
  author_id int null,
  datetime int null
);


alter table users
  add last_viewed_chat_message int default 0 null;

## Изменение 15.07

alter table chat add company_id int null;