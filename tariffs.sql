create table tariffs
(
  tariff_id int null,
  tariff_name text null,
  price int null,
  period_in_months int null
);


INSERT INTO dev.tariffs (tariff_id, tariff_name, price, period_in_months) VALUES (0, 'Бесплатный', 0, 0);
INSERT INTO dev.tariffs (tariff_id, tariff_name, price, period_in_months) VALUES (1, 'Стартовый', 29900, 1);
INSERT INTO dev.tariffs (tariff_id, tariff_name, price, period_in_months) VALUES (2, 'Уверенный', 74700, 3);
INSERT INTO dev.tariffs (tariff_id, tariff_name, price, period_in_months) VALUES (3, 'Босс', 238800, 12);