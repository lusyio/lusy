create table achievement_rules
(
  achievement_id int auto_increment
    primary key,
  achievement_name text null,
  multiple tinyint null,
  conditions text null,
  hidden tinyint null,
  periodic tinyint null
);

create table user_achievements
(
  achiev_id int auto_increment
    primary key,
  user_id int null,
  achievement text null,
  datetime int null
);

INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, hidden, periodic) VALUES (1, 'meeting', 0, '{"profileFilled":{"value":true,"condition": "equal"}}', 0, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, hidden, periodic) VALUES (2, 'invitor', 0, '{"inviteSent":{"value":true,"condition": "equal"}}', 0, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, hidden, periodic) VALUES (3, 'taskDone_1', 0, '{"taskDone":{"value":1,"condition": "moreOrEqual"}}', 0, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, hidden, periodic) VALUES (4, 'taskDone_10', 0, '{"taskDone":{"value":10,"condition": "moreOrEqual"}}', 0, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, hidden, periodic) VALUES (5, 'taskDone_50', 0, '{"taskDone":{"value":50,"condition": "moreOrEqual"}}', 0, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, hidden, periodic) VALUES (6, 'taskDone_100', 0, '{"taskDone":{"value":100,"condition": "moreOrEqual"}}', 0, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, hidden, periodic) VALUES (7, 'taskDone_200', 0, '{"taskDone":{"value":200,"condition": "moreOrEqual"}}', 0, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, hidden, periodic) VALUES (8, 'taskDone_500', 0, '{"taskDone":{"value":500,"condition": "moreOrEqual"}}', 0, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, hidden, periodic) VALUES (9, 'taskCreate_10', 0, '{"taskCreate":{"value":10,"condition": "moreOrEqual"}}', 0, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, hidden, periodic) VALUES (10, 'taskCreate_50', 0, '{"taskCreate":{"value":50,"condition": "moreOrEqual"}}', 0, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, hidden, periodic) VALUES (11, 'taskCreate_100', 0, '{"taskCreate":{"value":100,"condition": "moreOrEqual"}}', 0, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, hidden, periodic) VALUES (12, 'taskCreate_200', 0, '{"taskCreate":{"value":200,"condition": "moreOrEqual"}}', 0, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, hidden, periodic) VALUES (13, 'taskCreate_500', 0, '{"taskCreate":{"value":500,"condition": "moreOrEqual"}}', 0, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, hidden, periodic) VALUES (14, 'taskOverdue_1', 0, '{"taskOverdue":{"value":1,"condition": "moreOrEqual"}}', 1, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, hidden, periodic) VALUES (15, 'taskPostpone_1', 0, '{"taskPostpone":{"value":1,"condition": "moreOrEqual"}}', 0, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, hidden, periodic) VALUES (16, 'taskDoneWithCoworker_1', 0, '{"taskDoneWithCoworker":{"value":1,"condition": "moreOrEqual"}}', 0, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, hidden, periodic) VALUES (17, 'message_1', 0, '{"message":{"value":1,"condition": "moreOrEqual"}}', 0, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, hidden, periodic) VALUES (18, 'bugReport_1', 0, '{"bugReport":{"value":1,"condition": "moreOrEqual"}}', 1, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, hidden, periodic) VALUES (19, 'selfTask_1', 0, '{"selfTask":{"value":1,"condition": "moreOrEqual"}}', 1, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, hidden, periodic) VALUES (20, 'comment_1000', 0, '{"comment":{"value":1000,"condition": "moreOrEqual"}}', 1, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, hidden, periodic) VALUES (21, 'taskOverduePerMonth_0', 1, '{"taskOverduePerMonth":{"value":0,"condition": "equal"}}', 0, 1);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, hidden, periodic) VALUES (22, 'taskDonePerMonth_leader', 1, '{"taskDonePerMonthPlace":{"value": 1,"condition": "equal"}}', 0, 1);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, hidden, periodic) VALUES (23, 'taskDonePerMonth_500', 1, '{"taskDonePerMonth":{"value":500,"condition": "equal"}}', 0, 1);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, hidden, periodic) VALUES (24, 'taskCreatePerDay_30', 0, '{"taskCreateToday":{"value":30,"condition": "moreOrEqual"}}', 0, 0);
INSERT INTO achievement_rules (achievement_id, achievement_name, multiple, conditions, hidden, periodic) VALUES (25, 'taskInwork_20', 0, '{"taskInwork":{"value":20,"condition": "moreOrEqual"}}', 0, 0);