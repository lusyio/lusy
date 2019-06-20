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
  output_name text null,
  periodic tinyint null
);

INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name, periodic) VALUES (1, 'MEETING', 0, '{"profileFilled":{"value":true,"condition": "equal"}}', null, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name, periodic) VALUES (2, 'INVITOR', 0, '{"inviteSent":{"value":true,"condition": "equal"}}', null, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name, periodic) VALUES (3, 'TASKDONE_1', 0, '{"taskDone":{"value":0,"condition": "more"}}', null, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name, periodic) VALUES (4, 'TASKDONE_10', 0, '{"taskDone":{"value":9,"condition": "more"}}', null, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name, periodic) VALUES (5, 'TASKDONE_50', 0, '{"taskDone":{"value":49,"condition": "more"}}', null, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name, periodic) VALUES (6, 'TASKDONE_100', 0, '{"taskDone":{"value":99,"condition": "more"}}', null, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name, periodic) VALUES (7, 'TASKDONE_200', 0, '{"taskDone":{"value":199,"condition": "more"}}', null, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name, periodic) VALUES (8, 'TASKDONE_500', 0, '{"taskDone":{"value":499,"condition": "more"}}', null, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name, periodic) VALUES (9, 'TASKCREATE_10', 0, '{"taskCreate":{"value":9,"condition": "more"}}', null, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name, periodic) VALUES (10, 'TASKCREATE_50', 0, '{"taskCreate":{"value":49,"condition": "more"}}', null, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name, periodic) VALUES (11, 'TASKCREATE_100', 0, '{"taskCreate":{"value":99,"condition": "more"}}', null, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name, periodic) VALUES (12, 'TASKCREATE_200', 0, '{"taskCreate":{"value":199,"condition": "more"}}', null, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name, periodic) VALUES (13, 'TASKCREATE_500', 0, '{"taskCreate":{"value":499,"condition": "more"}}', null, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name, periodic) VALUES (14, 'TASKOVERDUE_1', 0, '{"taskOverdue":{"value":0,"condition": "more"}}', null, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name, periodic) VALUES (15, 'TASKPOSTPONE_1', 0, '{"taskPostpone":{"value":0,"condition": "more"}}', null, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name, periodic) VALUES (16, 'TASKDONEWITHCOWORKER_1', 0, '{"taskDoneWithCoworker":{"value":0,"condition": "more"}}', null, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name, periodic) VALUES (17, 'MESSAGE_1', 0, '{"message":{"value":0,"condition": "more"}}', null, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name, periodic) VALUES (18, 'BUGREPORT_1', 0, '{"bugReport":{"value":0,"condition": "more"}}', null, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name, periodic) VALUES (19, 'SELFTASK_1', 0, '{"selfTask":{"value":0,"condition": "more"}}', null, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name, periodic) VALUES (20, 'COMMENT_1000', 0, '{"comment":{"value":999,"condition": "more"}}', null, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name, periodic) VALUES (21, 'TASKOVERDUEPERMONTH_0', 1, '{"taskOverduePerMonth":{"value":0,"condition": "equal"}}', null, 1);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name, periodic) VALUES (22, 'TASKDONEPERMONTH_LEADER', 1, '{"taskDonePerMonth":{"value":0,"condition": "equal"}}', null, 1);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name, periodic) VALUES (23, 'TASKDONEPERMONTH_500', 1, '{"taskDonePerMonth":{"value":0,"condition": "equal"}}', null, 1);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, output_name, periodic) VALUES (24, 'TASKCREATEPERDAY_30', 0, '{"taskCreateToday":{"value":29,"condition": "more"}}', null, 0);