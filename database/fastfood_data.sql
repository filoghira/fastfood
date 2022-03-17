insert into t_ingredient(name, price, can_single_buy) value
    ('pane', 0.10, 0),					/*1*/
    ('hamburger_pig', 1.50, 1),		/*2*/
    ('hamburger_chicken', 1.00, 1),	/*3*/
    ('hamburger_cow', 2.00, 1),		/*4*/
    ('bacon', 0.70, 1),				/*5*/
    ('chips', 2.00, 0),				/*6*/
    ('ketchup', 0.50, 1),			/*7*/
    ('mayonnaise', 0.50, 1),		/*8*/
    ('coca_cola', 1.00, 0),			/*9*/
    ('sottiletta', 0.10, 1);			/*10*/

insert into t_type(name) value
    ('panino'),		/*1*/
    ('bibita'),		/*2*/
    ('contorno'),	/*3*/
    ('salsa');		/*4*/

insert into t_product(name, price, my_size, typeID) value
    ('Panino al maiale', 4.50, 1, 1),	/*1*/
    ('Panino al maiale', 5.50, 2, 1),	/*2*/
    ('Panino al maiale', 6.50, 3, 1),	/*3*/
    ('Patatine', 1.50, 1, 3),			/*4*/
    ('Patatine', 2.50, 2, 3),			/*5*/
    ('Patatine', 3.50, 3, 3),			/*6*/
    ('CocaCola', 2.00, 1, 2),			/*7*/
    ('CocaCola', 2.50, 2, 2),			/*8*/
    ('CocaCola', 3.50, 3, 2);			/*9*/

/*Panino al maiale piccolo*/
insert into r_product_composition(product_id, ingredient_id, quantity, strict) value
    (1, 1, 1, true),	/*Pane*/
    (1, 2, 1, true),	/*Hamburger*/
    (1, 5, 2, false),	/*Bacon*/
    (1, 10, 1, false);	/*Sottiletta*/

/*Panino al maiale medio*/
insert into r_product_composition(product_id, ingredient_id, quantity, strict) value
    (2, 1, 1, true),	/*Pane*/
    (2, 2, 2, true),	/*Hamburger*/
    (2, 5, 4, false),	/*Bacon*/
    (2, 10, 2, false);	/*Sottiletta*/

/*Panino al maiale grande*/
insert into r_product_composition(product_id, ingredient_id, quantity, strict) value
    (3, 1, 1, true),	/*Pane*/
    (3, 2, 3, true),	/*Hamburger*/
    (3, 5, 8, false),	/*Bacon*/
    (3, 10, 3, false);	/*Sottiletta*/

/*Patatine*/
insert into r_product_composition(product_id, ingredient_id, quantity) value
    (4, 6, 1),	/*Piccole*/
    (5, 6, 2),	/*Medie*/
    (6, 6, 3);	/*Grande*/

/*CocaCola*/
insert into r_product_composition(product_id, ingredient_id, quantity) value
    (7, 9, 0.25),	/*Piccola*/
    (8, 9, 0.5),	/*Media*/
    (9, 9, 0.75);	/*Grande*/

/*Menus*/
insert into t_menu(name, my_size, price) value
    ('Il Gran Maiale', 1, 5.50),	/*1*/
    ('Il Gran Maiale', 2, 7.50),	/*2*/
    ('Il Gran Maiale', 3, 8.50);	/*3*/

/*Menu maialino*/
insert into r_menu_contains(menu_id, product_id) value
    (1, 1),		/*Panino*/
    (1, 4),		/*Patatine*/
    (1, 7);		/*CocaCola*/

/*Menu maiale*/
insert into r_menu_contains(menu_id, product_id) value
    (2, 2),		/*Panino*/
    (2, 5),		/*Patatine*/
    (2, 8);		/*CocaCola*/

/*Menu maialone*/
insert into r_menu_contains(menu_id, product_id) value
    (3, 3),		/*Panino*/
    (3, 6),		/*Patatine*/
    (3, 9);		/*CocaCola*/