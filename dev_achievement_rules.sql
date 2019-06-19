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

INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name) VALUES (1, 'MEETING', 0, '{"profileFilled":{"value":true,"condition": "equal"}}', null);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name) VALUES (2, 'INVITOR', 0, '{"inviteSent":{"value":true,"condition": "equal"}}', null);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name) VALUES (3, 'TASKDONE_1', 0, '{"taskDone":{"value":0,"condition": "more"}}', null);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name) VALUES (4, 'TASKDONE_10', 0, '{"taskDone":{"value":9,"condition": "more"}}', null);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name) VALUES (5, 'TASKDONE_50', 0, '{"taskDone":{"value":49,"condition": "more"}}', null);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name) VALUES (6, 'TASKDONE_100', 0, '{"taskDone":{"value":99,"condition": "more"}}', null);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name) VALUES (7, 'TASKDONE_1000', 0, '{"taskDone":{"value":999,"condition": "more"}}', null);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name) VALUES (8, 'TASKCREATE_10', 0, '{"taskCreate":{"value":9,"condition": "more"}}', null);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name) VALUES (9, 'TASKCREATE_50', 0, '{"taskCreate":{"value":49,"condition": "more"}}', null);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name) VALUES (10, 'TASKCREATE_100', 0, '{"taskCreate":{"value":99,"condition": "more"}}', null);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name) VALUES (11, 'TASKCREATE_1000', 0, '{"taskCreate":{"value":999,"condition": "more"}}', null);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name) VALUES (12, 'TASKOVERDUE_1', 0, '{"taskOverdue":{"value":0,"condition": "more"}}', null);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name) VALUES (13, 'TASKPOSTPONE_1', 0, '{"taskPostpone":{"value":0,"condition": "more"}}', null);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name) VALUES (14, 'TASKDONEWITHCOWORKER_1', 0, '{"taskDoneWithCoworker":{"value":0,"condition": "more"}}', null);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name) VALUES (15, 'MESSAGE_1', 0, '{"message":{"value":0,"condition": "more"}}', null);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name) VALUES (16, 'BUGREPORT_1', 0, '{"bugReport":{"value":0,"condition": "more"}}', null);