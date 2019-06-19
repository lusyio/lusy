create table user_achievements
(
  achiev_id int auto_increment
    primary key,
  user_id int null,
  achievement text null,
  datetime int null
);
create table achievement_rules
(
  achievement_id int auto_increment
    primary key,
  achievement_name text null,
  multiple tinyint null,
  conditions text null,
  output_name text null
);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name) VALUES (1, 'MEETING', 0, '{"profileFilled":{"value":true,"condition": "equal"}}', 'Знакомство');
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name) VALUES (2, 'INVITOR', 0, '{"inviteSent":{"value":true,"condition": "equal"}}', 'Приглашатор');
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name) VALUES (3, 'FINISHER_1', 0, '{"taskDone":{"value":10,"condition": "more"}}', 'Решебник 1');
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name) VALUES (4, 'FINISHER_2', 0, '{"taskDone":{"value":50,"condition": "more"}}', 'Решебник 2');
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name) VALUES (5, 'FINISHER_3', 0, '{"taskDone":{"value":100,"condition": "more"}}', 'Решебник 3');
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name) VALUES (6, 'DELEGATING_1', 0, '{"taskCreate":{"value":10,"condition": "more"}}', 'Манагер');
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name) VALUES (7, 'DELEGATING_2', 0, '{"taskCreate":{"value":50,"condition": "more"}}', 'Акула бизнеса');
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name) VALUES (8, 'DELEGATING_3', 0, '{"taskCreate":{"value":100,"condition": "more"}}', 'LVL100 BO$$');