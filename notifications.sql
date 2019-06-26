create table user_notifications
(
  user_id int not null
    primary key,
  task_create tinyint default 0 null,
  task_overdue tinyint default 0 null,
  comment tinyint default 0 null,
  task_review tinyint default 0 null,
  task_postpone tinyint default 0 null,
  message tinyint default 0 null,
  achievement tinyint default 0 null
);
