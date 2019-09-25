alter table tasks add repeat_task int null;

alter table tasks add repeat_type int default 0 null;

alter table tasks alter column repeat_type set default null;