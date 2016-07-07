
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
CREATE INDEX "{prefix}custom_fields_field" ON "{prefix}custom_fields" ("field");

CREATE TABLE "{prefix}meta" (
	"id" INTEGER NOT NULL PRIMARY KEY,
	"key" TEXT NOT NULL,
	"value" TEXT NOT NULL
);

CREATE INDEX "{prefix}meta_key" ON "{prefix}meta" ("key");

CREATE TABLE "{prefix}page_meta" (
	"id" INTEGER NOT NULL PRIMARY KEY,
	"page" INTEGER NOT NULL,
	"custom_field" INTEGER NOT NULL,
	"data" TEXT NOT NULL
);

CREATE INDEX "{prefix}page_meta_custom_field" ON "{prefix}page_meta" ("custom_field");
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

CREATE INDEX "{prefix}pages_parent" ON "{prefix}pages" ("parent");
CREATE INDEX "{prefix}pages_slug" ON "{prefix}pages" ("slug");
CREATE INDEX "{prefix}pages_status" ON "{prefix}pages" ("status");
CREATE INDEX "{prefix}pages_show_in_menu" ON "{prefix}pages" ("show_in_menu");
CREATE INDEX "{prefix}pages_menu_order" ON "{prefix}pages" ("menu_order");

CREATE TABLE "{prefix}post_meta" (
	"id" INTEGER NOT NULL PRIMARY KEY,
	"post" INTEGER NOT NULL,
	"custom_field" INTEGER NOT NULL,
	"data" TEXT NOT NULL
);

CREATE INDEX "{prefix}post_meta_custom_field" ON "{prefix}post_meta" ("custom_field");
CREATE INDEX "{prefix}post_meta_post" ON "{prefix}post_meta" ("post");

CREATE TABLE "{prefix}posts" (
	"id" INTEGER NOT NULL PRIMARY KEY,
	"title" TEXT NOT NULL,
	"slug" TEXT NOT NULL,
	"content" TEXT NOT NULL,
	"html" TEXT NOT NULL,
	"created" NUMERIC NOT NULL,
	"modified" NUMERIC NOT NULL,
	"published" NUMERIC NOT NULL,
	"author" INTEGER NOT NULL,
	"category" INTEGER NOT NULL,
	"status" TEXT NOT NULL
);

CREATE INDEX "{prefix}posts_slug" ON "{prefix}posts" ("slug");
CREATE INDEX "{prefix}posts_status" ON "{prefix}posts" ("status");
CREATE INDEX "{prefix}posts_published" ON "{prefix}posts" ("published");
CREATE INDEX "{prefix}posts_author" ON "{prefix}posts" ("author");
CREATE INDEX "{prefix}posts_category" ON "{prefix}posts" ("category");

CREATE TABLE "{prefix}users" (
	"id" INTEGER NOT NULL PRIMARY KEY,
	"username" TEXT NOT NULL,
	"password" TEXT NOT NULL,
	"email" TEXT NOT NULL,
	"name" TEXT NOT NULL,
	"bio" TEXT NOT NULL,
	"status" TEXT NOT NULL,
	"role" TEXT NOT NULL
);

CREATE INDEX "{prefix}users_status" ON "{prefix}users" ("status");
CREATE INDEX "{prefix}users_username" ON "{prefix}users" ("username");
CREATE INDEX "{prefix}users_email" ON "{prefix}users" ("email");

CREATE TABLE "{prefix}user_tokens" (
	"id" INTEGER NOT NULL PRIMARY KEY,
	"user" INTEGER NOT NULL,
	"expires" NUMERIC NOT NULL,
	"token" TEXT NOT NULL
);

CREATE INDEX "{prefix}user_tokens_user" ON "{prefix}user_tokens" ("user");
CREATE INDEX "{prefix}user_tokens_token" ON "{prefix}user_tokens" ("token");
CREATE INDEX "{prefix}user_tokens_expires" ON "{prefix}user_tokens" ("expires");
