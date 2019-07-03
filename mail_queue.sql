create table mail_queue
(
  queue_id int auto_increment,
  function_name text null,
  args text null,
  start_time int null,
  constraint mail_queue_pk
    primary key (queue_id)
);