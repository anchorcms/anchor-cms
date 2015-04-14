CREATE TABLE IF NOT EXISTS "anchor_categories" (
	"id" int(6) NOT NULL ,
	"title" varchar(150) NOT NULL,
	"slug" varchar(40) NOT NULL,
	"description" text NOT NULL,
	PRIMARY KEY ("id")
);

CREATE TABLE IF NOT EXISTS "anchor_comments" (
	"id" int(6) NOT NULL ,
	"post" int(6) NOT NULL,
	"status" text  NOT NULL,
	"date" datetime NOT NULL,
	"name" varchar(140) NOT NULL,
	"email" varchar(140) NOT NULL,
	"text" text NOT NULL,
	PRIMARY KEY ("id")
);

CREATE INDEX "anchor_comments_status" ON "anchor_comments" ("status");
CREATE INDEX "anchor_comments_post" ON "anchor_comments" ("post");

CREATE TABLE IF NOT EXISTS "anchor_extend" (
	"id" integer NOT NULL,
	"type" text NOT NULL,
	"field" text NOT NULL,
	"key" text NOT NULL,
	"label" text NOT NULL,
	"attributes" text NOT NULL,
	PRIMARY KEY ("id")
);

CREATE TABLE IF NOT EXISTS "anchor_meta" (
	"key" varchar(140) NOT NULL,
	"value" text NOT NULL,
	PRIMARY KEY ("key")
);

CREATE TABLE IF NOT EXISTS "anchor_page_meta" (
	"id" int(6) NOT NULL ,
	"page" int(6) NOT NULL,
	"extend" int(6) NOT NULL,
	"data" text NOT NULL,
	PRIMARY KEY ("id")
);

CREATE INDEX "anchor_page_meta_extend" ON "anchor_page_meta" ("extend");
CREATE INDEX "anchor_page_meta_page" ON "anchor_page_meta" ("page");

CREATE TABLE IF NOT EXISTS "anchor_pages" (
	"id" int(6) NOT NULL ,
	"parent" int(6) NOT NULL,
	"slug" varchar(150) NOT NULL,
	"name" varchar(64) NOT NULL,
	"title" varchar(150) NOT NULL,
	"content" longtext NOT NULL,
	"status" text  NOT NULL,
	"redirect" text NOT NULL,
	"show_in_menu" tinyint(1) NOT NULL,
	"menu_order" int(4) NOT NULL,
	PRIMARY KEY ("id")
);

CREATE INDEX "anchor_pages_slug" ON "anchor_pages" ("slug");
CREATE INDEX "anchor_pages_status" ON "anchor_pages" ("status");

CREATE TABLE IF NOT EXISTS "anchor_plugins" (
	"id" int(6) NOT NULL ,
	"path" varchar(180) NOT NULL,
	"name" varchar(180) NOT NULL,
	"description" text NOT NULL,
	"version" varchar(80) NOT NULL,
	PRIMARY KEY ("id")
);

CREATE TABLE IF NOT EXISTS "anchor_post_meta" (
	"id" int(6) NOT NULL ,
	"post" int(6) NOT NULL,
	"extend" int(6) NOT NULL,
	"data" text NOT NULL,
	PRIMARY KEY ("id")
);

CREATE INDEX "anchor_post_meta_extend" ON "anchor_post_meta" ("extend");
CREATE INDEX "anchor_post_meta_post" ON "anchor_post_meta" ("post");

CREATE TABLE IF NOT EXISTS "anchor_posts" (
	"id" int(6) NOT NULL ,
	"title" varchar(150) NOT NULL,
	"slug" varchar(150) NOT NULL,
	"description" text NOT NULL,
	"html" longtext NOT NULL,
	"css" text NOT NULL,
	"js" text NOT NULL,
	"created" datetime NOT NULL,
	"author" int(6) NOT NULL,
	"category" int(6) NOT NULL,
	"status" text  NOT NULL,
	"comments" tinyint(1) NOT NULL,
	PRIMARY KEY ("id")
);

CREATE INDEX "anchor_posts_slug" ON "anchor_posts" ("slug");
CREATE INDEX "anchor_posts_status" ON "anchor_posts" ("status");

CREATE TABLE IF NOT EXISTS "anchor_sessions" (
	"id" char(32) NOT NULL,
	"expire" int(10) NOT NULL,
	"data" text NOT NULL,
	PRIMARY KEY ("id")
);

CREATE TABLE IF NOT EXISTS "anchor_users" (
	"id" int(6) NOT NULL ,
	"username" varchar(100) NOT NULL,
	"password" text NOT NULL,
	"email" varchar(140) NOT NULL,
	"real_name" varchar(140) NOT NULL,
	"bio" text NOT NULL,
	"status" text  NOT NULL,
	"role" text  NOT NULL,
	PRIMARY KEY ("id")
);
