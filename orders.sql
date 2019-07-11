create table orders
(
  order_id int auto_increment
    primary key,
  amount int null,
  customer_key int null,
  create_date int null,
  payment_id int(20) null,
  status text null,
  error_code text null,
  rebill_id int(20) null,
  pan text null,
  user_id int null,
  tariff int null,
  processed tinyint default 0 null
);

###Изменение 1
alter table orders add cron tinyint null;

###Изменение 1
alter table orders change cron first_pay tinyint default 0 null;