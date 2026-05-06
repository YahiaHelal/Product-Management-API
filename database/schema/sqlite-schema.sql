CREATE TABLE IF NOT EXISTS "migrations"(
  "id" integer primary key autoincrement not null,
  "migration" varchar not null,
  "batch" integer not null
);
CREATE TABLE IF NOT EXISTS "users"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "email" varchar not null,
  "email_verified_at" datetime,
  "password" varchar not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "users_email_unique" on "users"("email");
CREATE TABLE IF NOT EXISTS "password_reset_tokens"(
  "email" varchar not null,
  "token" varchar not null,
  "created_at" datetime,
  primary key("email")
);
CREATE TABLE IF NOT EXISTS "cache"(
  "key" varchar not null,
  "value" text not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE INDEX "cache_expiration_index" on "cache"("expiration");
CREATE TABLE IF NOT EXISTS "cache_locks"(
  "key" varchar not null,
  "owner" varchar not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE INDEX "cache_locks_expiration_index" on "cache_locks"("expiration");
CREATE TABLE IF NOT EXISTS "jobs"(
  "id" integer primary key autoincrement not null,
  "queue" varchar not null,
  "payload" text not null,
  "attempts" integer not null,
  "reserved_at" integer,
  "available_at" integer not null,
  "created_at" integer not null
);
CREATE INDEX "jobs_queue_index" on "jobs"("queue");
CREATE TABLE IF NOT EXISTS "job_batches"(
  "id" varchar not null,
  "name" varchar not null,
  "total_jobs" integer not null,
  "pending_jobs" integer not null,
  "failed_jobs" integer not null,
  "failed_job_ids" text not null,
  "options" text,
  "cancelled_at" integer,
  "created_at" integer not null,
  "finished_at" integer,
  primary key("id")
);
CREATE TABLE IF NOT EXISTS "failed_jobs"(
  "id" integer primary key autoincrement not null,
  "uuid" varchar not null,
  "connection" text not null,
  "queue" text not null,
  "payload" text not null,
  "exception" text not null,
  "failed_at" datetime not null default CURRENT_TIMESTAMP
);
CREATE UNIQUE INDEX "failed_jobs_uuid_unique" on "failed_jobs"("uuid");
CREATE TABLE IF NOT EXISTS "products"(
  "id" integer primary key autoincrement not null,
  "sku" varchar not null,
  "price" numeric not null,
  "sale_price" numeric,
  "stock" integer not null default '0',
  "brand" varchar not null,
  "main_image_path" varchar,
  "status" tinyint(1) not null default '1',
  "category_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  foreign key("category_id") references "categories"("id") on delete cascade
);
CREATE INDEX "products_sku_index" on "products"("sku");
CREATE INDEX "products_brand_index" on "products"("brand");
CREATE INDEX "products_status_index" on "products"("status");
CREATE UNIQUE INDEX "products_sku_unique" on "products"("sku");
CREATE TABLE IF NOT EXISTS "categories"(
  "id" integer primary key autoincrement not null,
  "parent_id" integer,
  "status" tinyint(1) not null default '1',
  "image_path" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("parent_id") references "categories"("id") on delete set null
);
CREATE TABLE IF NOT EXISTS "category_translations"(
  "id" integer primary key autoincrement not null,
  "category_id" integer not null,
  "locale" varchar not null,
  "name" varchar not null,
  foreign key("category_id") references "categories"("id") on delete cascade
);
CREATE UNIQUE INDEX "category_translations_category_id_locale_unique" on "category_translations"(
  "category_id",
  "locale"
);
CREATE INDEX "category_translations_locale_index" on "category_translations"(
  "locale"
);
CREATE INDEX "category_translations_name_index" on "category_translations"(
  "name"
);
CREATE TABLE IF NOT EXISTS "favorites"(
  "id" integer primary key autoincrement not null,
  "user_id" integer not null,
  "product_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id") on delete cascade,
  foreign key("product_id") references "products"("id") on delete cascade
);
CREATE UNIQUE INDEX "favorites_user_id_product_id_unique" on "favorites"(
  "user_id",
  "product_id"
);
CREATE TABLE IF NOT EXISTS "product_translations"(
  "id" integer primary key autoincrement not null,
  "product_id" integer not null,
  "locale" varchar not null,
  "title" varchar not null,
  "description" text,
  foreign key("product_id") references "products"("id") on delete cascade
);
CREATE UNIQUE INDEX "product_translations_product_id_locale_unique" on "product_translations"(
  "product_id",
  "locale"
);
CREATE INDEX "product_translations_locale_index" on "product_translations"(
  "locale"
);
CREATE TABLE IF NOT EXISTS "product_images"(
  "id" integer primary key autoincrement not null,
  "product_id" integer not null,
  "image_path" varchar not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("product_id") references "products"("id") on delete cascade
);
CREATE INDEX "product_images_product_id_index" on "product_images"(
  "product_id"
);
CREATE TABLE IF NOT EXISTS "product_files"(
  "id" integer primary key autoincrement not null,
  "product_id" integer not null,
  "file_path" varchar not null,
  "file_type" varchar not null,
  "file_name" varchar not null,
  "file_size" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("product_id") references "products"("id") on delete cascade
);
CREATE INDEX "product_files_product_id_index" on "product_files"("product_id");
CREATE TABLE IF NOT EXISTS "product_attributes"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "value" varchar not null,
  "product_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("product_id") references "products"("id") on delete cascade
);
CREATE INDEX "product_attributes_product_id_name_index" on "product_attributes"(
  "product_id",
  "name"
);
CREATE TABLE IF NOT EXISTS "admins"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "email" varchar not null,
  "email_verified_at" datetime,
  "password" varchar not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "admins_email_unique" on "admins"("email");
CREATE TABLE IF NOT EXISTS "personal_access_tokens"(
  "id" integer primary key autoincrement not null,
  "tokenable_type" varchar not null,
  "tokenable_id" integer not null,
  "name" text not null,
  "token" varchar not null,
  "abilities" text,
  "last_used_at" datetime,
  "expires_at" datetime,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE INDEX "personal_access_tokens_tokenable_type_tokenable_id_index" on "personal_access_tokens"(
  "tokenable_type",
  "tokenable_id"
);
CREATE UNIQUE INDEX "personal_access_tokens_token_unique" on "personal_access_tokens"(
  "token"
);
CREATE INDEX "personal_access_tokens_expires_at_index" on "personal_access_tokens"(
  "expires_at"
);

INSERT INTO migrations VALUES(1,'0001_01_01_000000_create_users_table',1);
INSERT INTO migrations VALUES(2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO migrations VALUES(3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO migrations VALUES(4,'2026_04_29_113720_create_products_table',1);
INSERT INTO migrations VALUES(5,'2026_04_29_114439_create_categories_table',1);
INSERT INTO migrations VALUES(6,'2026_04_29_114450_create_category_translations_table',1);
INSERT INTO migrations VALUES(7,'2026_04_29_114506_create_favorites_table',1);
INSERT INTO migrations VALUES(8,'2026_04_29_114521_create_product_translations_table',1);
INSERT INTO migrations VALUES(9,'2026_04_29_114600_create_product_images_table',1);
INSERT INTO migrations VALUES(10,'2026_04_29_114609_create_product_files_table',1);
INSERT INTO migrations VALUES(11,'2026_04_29_114623_create_product_attributes_table',1);
INSERT INTO migrations VALUES(12,'2026_04_29_155047_create_admins_table',1);
INSERT INTO migrations VALUES(13,'2026_04_30_214902_create_personal_access_tokens_table',1);
