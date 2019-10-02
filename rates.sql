create table rates
(
  rate_id int auto_increment,
  user_id int null,
  rate int null,
  constraint rates_pk
    primary key (rate_id)
);