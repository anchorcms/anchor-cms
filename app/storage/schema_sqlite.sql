
CREATE TABLE "{prefix}categories" (
	"id" INTEGER NOT NULL PRIMARY KEY,
	"title" TEXT NOT NULL,
	"slug" TEXT NOT NULL,
	"description" TEXT NOT NULL
);

CREATE INDEX "{prefix}categories_slug" ON "{prefix}categories" ("slug");

CREATE TABLE "{prefix}custom_fields" (
	"id" INTEGER NOT NULL PRIMARY KEY,
	"type" TEXT NOT NULL,
	"field" TEXT NOT NULL,
	"key" TEXT NOT NULL,
	"label" TEXT NOT NULL,
	"attributes" TEXT NOT NULL
);

CREATE INDEX "{prefix}custom_fields_type" ON "{prefix}custom_fields" ("type");

CREATE TABLE "{prefix}meta" (
	"key" TEXT NOT NULL PRIMARY KEY,
	"value" TEXT NOT NULL
);

CREATE TABLE "{prefix}page_meta" (
	"id" INTEGER NOT NULL PRIMARY KEY,
	"page" INTEGER NOT NULL,
	"extend" INTEGER NOT NULL,
	"data" TEXT NOT NULL
);

CREATE INDEX "{prefix}page_meta_extend" ON "{prefix}page_meta" ("extend");
CREATE INDEX "{prefix}page_meta_page" ON "{prefix}page_meta" ("page");

CREATE TABLE "{prefix}pages" (
	"id" INTEGER NOT NULL PRIMARY KEY,
	"parent" INTEGER NOT NULL,
	"slug" TEXT NOT NULL,
	"name" TEXT NOT NULL,
	"title" TEXT NOT NULL,
	"content" TEXT NOT NULL,
	"html" TEXT NOT NULL,
	"status" TEXT NOT NULL,
	"redirect" TEXT NOT NULL,
	"show_in_menu" INTEGER NOT NULL,
	"menu_order" INTEGER NOT NULL
);

CREATE INDEX "{prefix}pages_slug" ON "{prefix}pages" ("slug");
CREATE INDEX "{prefix}pages_status" ON "{prefix}pages" ("status");

CREATE TABLE "{prefix}post_meta" (
	"id" INTEGER NOT NULL PRIMARY KEY,
	"post" INTEGER NOT NULL,
	"custom_field_id" INTEGER NOT NULL,
	"data" TEXT NOT NULL
);

CREATE INDEX "{prefix}post_meta_custom_field_id" ON "{prefix}post_meta" ("custom_field_id");
CREATE INDEX "{prefix}post_meta_post" ON "{prefix}post_meta" ("post");

CREATE TABLE "{prefix}posts" (
	"id" INTEGER NOT NULL PRIMARY KEY,
	"title" TEXT NOT NULL,
	"slug" TEXT NOT NULL,
	"content" TEXT NOT NULL,
	"html" TEXT NOT NULL,
	"created" NUMERIC NOT NULL,
	"modified" NUMERIC NOT NULL,
	"author" INTEGER NOT NULL,
	"category" INTEGER NOT NULL,
	"status" TEXT NOT NULL
);

CREATE INDEX "{prefix}posts_slug" ON "{prefix}posts" ("slug");
CREATE INDEX "{prefix}posts_status" ON "{prefix}posts" ("status");

CREATE TABLE "{prefix}users" (
	"id" INTEGER NOT NULL PRIMARY KEY,
	"username" TEXT NOT NULL,
	"password" TEXT NOT NULL,
	"email" TEXT NOT NULL,
	"real_name" TEXT NOT NULL,
	"bio" TEXT NOT NULL,
	"status" TEXT NOT NULL,
	"role" TEXT NOT NULL
);

CREATE INDEX "{prefix}users_status" ON "{prefix}users" ("status");
CREATE INDEX "{prefix}users_usernames" ON "{prefix}users" ("username");
