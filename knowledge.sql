create table knowledge_articles
(
  article_id int auto_increment,
  article_name text null,
  article_text text null,
  constraint knowledge_articles_pk
    primary key (article_id)
);