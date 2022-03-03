create database if not exists fastfood;
use fastfood;

create table if not exists t_user (
                                      id int not null auto_increment primary key,
                                      username varchar(20) not null unique,
                                      password_hash varchar(255) not null,
                                      auth_level int not null default 0 check ( auth_level >= 0 and auth_level <= 1 )
);

create table if not exists t_menu (
                                      id int not null primary key auto_increment,
                                      name varchar(260) not null ,
                                      my_size int not null check ( my_size > 0 AND my_size < 4),
                                      price DEC(65, 2) not null check ( price > 0 )
);

create table if not exists t_type (
                                      id int not null primary key auto_increment,
                                      name varchar(260) not null
);

create table if not exists t_product (
                                         id int not null primary key auto_increment,
                                         name varchar(260) not null ,
                                         price DEC(65, 2) not null check ( price > 0 ),
                                         my_size int not null default 2 check ( my_size > 0 AND my_size < 4) ,
                                         typeID int references t_type(id)
);

create table if not exists t_receipt (
                                         id int not null primary key auto_increment,
                                         emit_date DATETIME not null ,
                                         account_id int not null references t_user(id),
                                         total DEC(65, 2) not null check ( total > 0 )
);

create table if not exists t_ingredient (
                                            id int not null primary key auto_increment,
                                            name varchar(260) not null ,
                                            price DEC(65, 2) not null check ( price > 0 ),
                                            can_single_buy bool not null default false
);

create table if not exists r_menu_contains (
                                               menu_id int references t_menu(id),
                                               product_id int references t_product(id)
);

create table if not exists r_product_composition (
                                                     product_id int references t_product(id) ,
                                                     ingredient_id int references t_ingredient(id),
                                                     quantity float not null default 1 check ( quantity > 0 )
);

create table if not exists r_orderMenu (
                                           receipt_id int references t_receipt(id) ,
                                           menu_id int references t_menu(id)
);

create table if not exists r_orderProduct (
                                              receipt_id int references t_receipt(id) ,
                                              product_id int references t_product(id)
);

create table if not exists r_orderIngredient (
                                                 receipt_id int references t_receipt(id) ,
                                                 ingredient_id int references t_ingredient(id)
);