create table finance_events
(
  fin_event_id int auto_increment
    primary key,
  event text null,
  event_datetime int null,
  company_id int null,
  orderId int null,
  amount int null,
  comment text null
);

### Изменение 1
alter table finance_events change orderId order_id int null;