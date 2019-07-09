create table company_tariff
(
  company_id int not null
    primary key,
  tariff int default 0 null,
  payday int null,
  is_card_binded tinyint null,
  rebill_id text null,
  pan text null
);

