PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE "{{prefix}}categories" (
  "id" INTEGER,
  "title" varchar(150) NOT NULL,
  "slug" varchar(40) NOT NULL,
  "description" text NOT NULL,
  PRIMARY KEY ("id")
);
INSERT INTO "{{prefix}}categories" VALUES(1,'Uncategorised','uncategorised','Ain''t no category here.');
CREATE TABLE "{{prefix}}category_meta" (
  "id" INTEGER,
  "category" int(6) NOT NULL,
  "extend" int(6) NOT NULL,
  "data" text NOT NULL,
  PRIMARY KEY ("id")
);
CREATE TABLE "{{prefix}}comments" (
  "id" INTEGER,
  "post" int(6) NOT NULL,
  "status" text  NOT NULL,
  "date" datetime NOT NULL,
  "name" varchar(140) NOT NULL,
  "email" varchar(140) NOT NULL,
  "text" text NOT NULL,
  PRIMARY KEY ("id")
);
CREATE TABLE "{{prefix}}extend" (
  "id" INTEGER,
  "type" text  NOT NULL,
  "pagetype" varchar(140) NOT NULL DEFAULT 'all',
  "field" text  NOT NULL,
  "key" varchar(160) NOT NULL,
  "label" varchar(160) NOT NULL,
  "attributes" text NOT NULL,
  PRIMARY KEY ("id")
);
CREATE TABLE "{{prefix}}meta" (
  "key" varchar(140) NOT NULL,
  "value" text NOT NULL,
  PRIMARY KEY ("key")
);
INSERT INTO "{{prefix}}meta" VALUES('auto_published_comments','0');
INSERT INTO "{{prefix}}meta" VALUES('comment_moderation_keys','');
INSERT INTO "{{prefix}}meta" VALUES('comment_notifications','0');
INSERT INTO "{{prefix}}meta" VALUES('date_format','jS M, Y');
INSERT INTO "{{prefix}}meta" VALUES('home_page','1');
INSERT INTO "{{prefix}}meta" VALUES('posts_page','1');
INSERT INTO "{{prefix}}meta" VALUES('posts_per_page','6');
CREATE TABLE "{{prefix}}page_meta" (
  "id" INTEGER,
  "page" int(6) NOT NULL,
  "extend" int(6) NOT NULL,
  "data" text NOT NULL,
  PRIMARY KEY ("id")
);
CREATE TABLE "{{prefix}}pages" (
  "id" INTEGER,
  "parent" int(6) NOT NULL,
  "slug" varchar(150) NOT NULL,
  "pagetype" varchar(140) NOT NULL DEFAULT 'all',
  "name" varchar(64) NOT NULL,
  "title" varchar(150) NOT NULL,
  "content" text NOT NULL,
  "status" text  NOT NULL,
  "redirect" text NOT NULL,
  "show_in_menu" tinyint(1) NOT NULL,
  "menu_order" int(4) NOT NULL,
  PRIMARY KEY ("id")
);
INSERT INTO "{{prefix}}pages" VALUES(1,0,'posts','all','Posts','My posts and thoughts','Welcome!','published','',1,0);
CREATE TABLE "{{prefix}}pagetypes" (
  "key" varchar(32) NOT NULL,
  "value" varchar(32) NOT NULL
);
INSERT INTO "{{prefix}}pagetypes" VALUES('all','All Pages');
CREATE TABLE "{{prefix}}post_meta" (
  "id" INTEGER,
  "post" int(6) NOT NULL,
  "extend" int(6) NOT NULL,
  "data" text NOT NULL,
  PRIMARY KEY ("id")
);
CREATE TABLE "{{prefix}}posts" (
  "id" INTEGER,
  "title" varchar(150) NOT NULL,
  "slug" varchar(150) NOT NULL,
  "description" text NOT NULL,
  "html" mediumtext NOT NULL,
  "css" text NOT NULL,
  "js" text NOT NULL,
  "created" datetime NOT NULL,
  "author" int(6) NOT NULL,
  "category" int(6) NOT NULL,
  "status" text  NOT NULL,
  "comments" tinyint(1) NOT NULL,
  PRIMARY KEY ("id")
);
INSERT INTO "{{prefix}}posts" VALUES(1,'Hello World','hello-world','This is the first post.','Hello World!

This is the first post.','','','{{now}}',1,1,'published',0);
CREATE TABLE "{{prefix}}sessions" (
  "id" char(32),
  "expire" int(10) NOT NULL,
  "data" text NOT NULL,
  PRIMARY KEY ("id")
);
CREATE TABLE "{{prefix}}user_meta" (
  "id" INTEGER,
  "user" int(6) NOT NULL,
  "extend" int(6) NOT NULL,
  "data" text NOT NULL,
  PRIMARY KEY ("id")
);
CREATE TABLE "{{prefix}}users" (
  "id" INTEGER,
  "username" varchar(100) NOT NULL,
  "password" text NOT NULL,
  "email" varchar(140) NOT NULL,
  "real_name" varchar(140) NOT NULL,
  "bio" text NOT NULL,
  "status" text  NOT NULL,
  "role" text  NOT NULL,
  PRIMARY KEY ("id")
);
CREATE INDEX "{{prefix}}comments_post" ON "{{prefix}}comments" ("post");
CREATE INDEX "{{prefix}}comments_status" ON "{{prefix}}comments" ("status");
CREATE INDEX "{{prefix}}pages_status" ON "{{prefix}}pages" ("status");
CREATE INDEX "{{prefix}}pages_slug" ON "{{prefix}}pages" ("slug");
CREATE INDEX "{{prefix}}post_meta_post" ON "{{prefix}}post_meta" ("post");
CREATE INDEX "{{prefix}}post_meta_extend" ON "{{prefix}}post_meta" ("extend");
CREATE INDEX "{{prefix}}page_meta_page" ON "{{prefix}}page_meta" ("page");
CREATE INDEX "{{prefix}}page_meta_extend" ON "{{prefix}}page_meta" ("extend");
CREATE INDEX "{{prefix}}posts_status" ON "{{prefix}}posts" ("status");
CREATE INDEX "{{prefix}}posts_slug" ON "{{prefix}}posts" ("slug");
CREATE INDEX "{{prefix}}user_meta_item" ON "{{prefix}}user_meta" ("user");
CREATE INDEX "{{prefix}}user_meta_extend" ON "{{prefix}}user_meta" ("extend");
CREATE INDEX "{{prefix}}category_meta_item" ON "{{prefix}}category_meta" ("category");
CREATE INDEX "{{prefix}}category_meta_extend" ON "{{prefix}}category_meta" ("extend");
COMMIT;
