CREATE TABLE IF NOT EXISTS "anchor_categories" (
	"id" INTEGER PRIMARY KEY,
	"title" TEXT NOT NULL,
	"slug" TEXT NOT NULL,
	"description" TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS "anchor_comments" (
	"id" INTEGER PRIMARY KEY,
	"post" INTEGER NOT NULL,
	"status" TEXT  NOT NULL,
	"date" NUMERIC NOT NULL,
	"name" TEXT NOT NULL,
	"email" TEXT NOT NULL,
	"text" TEXT NOT NULL
);

CREATE INDEX "anchor_comments_status" ON "anchor_comments" ("status");
CREATE INDEX "anchor_comments_post" ON "anchor_comments" ("post");

CREATE TABLE IF NOT EXISTS "anchor_extend" (
	"id" INTEGER PRIMARY KEY,
	"type" TEXT NOT NULL,
	"field" TEXT NOT NULL,
	"key" TEXT NOT NULL,
	"label" TEXT NOT NULL,
	"attributes" TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS "anchor_meta" (
	"key" TEXT PRIMARY KEY,
	"value" TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS "anchor_page_meta" (
	"id" INTEGER PRIMARY KEY,
	"page" INTEGER NOT NULL,
	"extend" INTEGER NOT NULL,
	"data" TEXT NOT NULL
);

CREATE INDEX "anchor_page_meta_extend" ON "anchor_page_meta" ("extend");
CREATE INDEX "anchor_page_meta_page" ON "anchor_page_meta" ("page");

CREATE TABLE IF NOT EXISTS "anchor_pages" (
	"id" INTEGER PRIMARY KEY,
	"parent" INTEGER NOT NULL,
	"slug" TEXT NOT NULL,
	"name" TEXT NOT NULL,
	"title" TEXT NOT NULL,
	"content" TEXT NOT NULL,
	"status" TEXT  NOT NULL,
	"redirect" TEXT NOT NULL,
	"show_in_menu" INTEGER NOT NULL,
	"menu_order" INTEGER NOT NULL
);

CREATE INDEX "anchor_pages_slug" ON "anchor_pages" ("slug");
CREATE INDEX "anchor_pages_status" ON "anchor_pages" ("status");

CREATE TABLE IF NOT EXISTS "anchor_plugins" (
	"id" INTEGER PRIMARY KEY,
	"path" TEXT NOT NULL,
	"name" TEXT NOT NULL,
	"description" TEXT NOT NULL,
	"version" TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS "anchor_post_meta" (
	"id" INTEGER PRIMARY KEY,
	"post" INTEGER NOT NULL,
	"extend" INTEGER NOT NULL,
	"data" TEXT NOT NULL
);

CREATE INDEX "anchor_post_meta_extend" ON "anchor_post_meta" ("extend");
CREATE INDEX "anchor_post_meta_post" ON "anchor_post_meta" ("post");

CREATE TABLE IF NOT EXISTS "anchor_posts" (
	"id" INTEGER PRIMARY KEY,
	"title" TEXT NOT NULL,
	"slug" TEXT NOT NULL,
	"description" TEXT NOT NULL,
	"html" TEXT NOT NULL,
	"css" TEXT NOT NULL,
	"js" TEXT NOT NULL,
	"created" NUMERIC NOT NULL,
	"author" INTEGER NOT NULL,
	"category" INTEGER NOT NULL,
	"status" TEXT  NOT NULL,
	"comments" INTEGER NOT NULL
);

CREATE INDEX "anchor_posts_slug" ON "anchor_posts" ("slug");
CREATE INDEX "anchor_posts_status" ON "anchor_posts" ("status");

CREATE TABLE IF NOT EXISTS "anchor_sessions" (
	"id" TEXT NOT NULL,
	"expire" NUMERIC NOT NULL,
	"data" TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS "anchor_users" (
	"id" INTEGER PRIMARY KEY,
	"username" TEXT NOT NULL,
	"password" TEXT NOT NULL,
	"email" TEXT NOT NULL,
	"real_name" TEXT NOT NULL,
	"bio" TEXT NOT NULL,
	"status" TEXT  NOT NULL,
	"role" TEXT  NOT NULL
);
