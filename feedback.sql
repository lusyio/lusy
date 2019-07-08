create table feedback
(
  message_id int auto_increment,
  user_id int null,
  message_title text null,
  message_text text null,
  page_link text null,
  datetime int null,
  cause text null,
  constraint feedback_pk
    primary key (message_id)
);