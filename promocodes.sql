create table promocodes
(
  promocode_id int auto_increment
    primary key,
  promocode_name text null,
  days_to_add int null,
  is_multiple tinyint null,
  valid_until int null,
  used tinyint null
);

