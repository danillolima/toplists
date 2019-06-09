DROP DATABASE toplistas;
CREATE DATABASE toplistas;
USE toplistas;

ALTER DATABASE toplistas CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;


ALTER TABLE tl_user CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

--ALTER TABLE table_name CHANGE column_name column_name VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

--ALTER DATABASE toplistas COLLATE = 'utf-8';

/*
Usuários autorizados a entrar no site
*/
CREATE OR REPLACE TABLE `tl_user` (
 	`id` int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	`username` varchar(15) NOT NULL,
	`name` varchar(100) DEFAULT NULL,
	`surname` varchar(30) DEFAULT NULL,
	`about` text(500) DEFAULT NULL,
	`pass` varchar(64) DEFAULT NULL,
	`email` varchar(200) DEFAULT NULL,
	`qtd_comments` int DEFAULT 0,
	`qtd_lists` int DEFAULT 0,
	`qtd_itens` int DEFAULT 0,
	`registered` TIMESTAMP DEFAULT NOW(),
    UNIQUE KEY `username` (`username`)
);

/*
Categorias podem ter categorias pais
*/
CREATE OR REPLACE TABLE `tl_category` (
 	`id` int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	`name` varchar(50) DEFAULT NULL,
	`url` varchar(100) DEFAULT NULL,
    `id_parent` int DEFAULT NULL,
    UNIQUE KEY `url` (`url`)
);

/*
Comentários podem ser feitos em items, perfis, listas e item de listas eles podem ser
buscados pelo atributo URL.
*/
CREATE OR REPLACE TABLE `tl_comment` (
 	`id` int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	`content` varchar(500) DEFAULT NULL,
	`author_comment` int UNSIGNED NOT NULL,
	`url` varchar(100),
	`url_commented` varchar(100),
	CONSTRAINT `fk_author_comment` FOREIGN KEY (author_comment) REFERENCES tl_user(id)
);

/*
Armazena os itens:
	id: Sequencial
	name: Nome do item ou assunto
	url: URL para o item
	id_category: Chave estrangeira 
*/
CREATE OR REPLACE TABLE `tl_item` (
 	`id` int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	`name` varchar(100) DEFAULT NULL,
	`description` varchar(300) DEFAULT NULL,
	`url` varchar(200) DEFAULT NULL,
	`id_category` int UNSIGNED NOT NULL,
	`author_item` int UNSIGNED NOT NULL,
	UNIQUE KEY `name` (`name`),
	UNIQUE KEY `url` (`url`),
	CONSTRAINT `fk_author_item` FOREIGN KEY (author_item) REFERENCES tl_user(id),
	CONSTRAINT `fk_item_category` FOREIGN KEY (id_category) REFERENCES tl_category(id)
);

/*
	Mapeamento da media
*/
CREATE OR REPLACE TABLE `tl_media` (
 	`id` int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	`type` int DEFAULT NULL,
	`url` varchar(200) DEFAULT NULL,
	`author_media` int UNSIGNED,
	KEY `fk_author_media` (`author_media`),
	CONSTRAINT `fk_author_media` FOREIGN KEY (author_media) REFERENCES tl_user(id),
	UNIQUE KEY `url` (`url`)
);

/*
	Uma lista tem um nome ou titulo, um link, descrição, um assunto(categoria), um tipo de lista
*/
CREATE OR REPLACE TABLE `tl_list` (
 	`id` int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	`name` varchar(150) DEFAULT NULL,
	`url` varchar(100) DEFAULT NULL,
	`description` varchar(200) DEFAULT NULL,
	`id_subject` int UNSIGNED NOT NULL,
	`list_type` int DEFAULT NULL,
	`source` varchar(200) DEFAULT NULL,
	`allow_votes` int DEFAULT NULL,
	`id_media` int UNSIGNED DEFAULT NULL,
	`id_user` int UNSIGNED NOT NULL,
	`id_category` int UNSIGNED NOT NULL,
	UNIQUE KEY `url` (`url`),
	CONSTRAINT `fk_list_category` FOREIGN KEY (id_category) REFERENCES tl_category(id),
	CONSTRAINT `fk_list_item` FOREIGN KEY (id_subject) REFERENCES tl_item(id),
	CONSTRAINT `fk_list_media` FOREIGN KEY (id_media) REFERENCES tl_media(id),
	CONSTRAINT `fk_list_user` FOREIGN KEY (id_user) REFERENCES tl_user(id)
);

CREATE OR REPLACE TABLE `tl_item_media` (
 	`id` int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	`id_item` int UNSIGNED NOT NULL,
	`id_media` int UNSIGNED NOT NULL,
	CONSTRAINT `fk_item_media_item` FOREIGN KEY (`id_item`) REFERENCES tl_item(id),
	CONSTRAINT `fk_item_media_media` FOREIGN KEY (`id_media`) REFERENCES tl_media(id)
);

CREATE OR REPLACE TABLE `tl_list_item` (
 	`id` int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	`id_list` int UNSIGNED NOT NULL,
	`id_item` int UNSIGNED NOT NULL,
	`votes` int DEFAULT 0,
	`url` varchar(300),
	CONSTRAINT `fk_list_item_item` FOREIGN KEY (`id_item`) REFERENCES `tl_item` (`id`),
	CONSTRAINT `fk_list_item_list` FOREIGN KEY (`id_list`) REFERENCES `tl_list` (`id`)
);

CREATE OR REPLACE TABLE `tl_vote` (
 	`id` int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	`author_vote` int unsigned,
	`vote` int,
	`item_voted` int unsigned,
	CONSTRAINT `fk_item_vote` FOREIGN KEY (item_voted) REFERENCES tl_list_item (id), 
	CONSTRAINT `fk_author_vote` FOREIGN KEY (author_vote) REFERENCES tl_user (id) 
);

CREATE OR REPLACE TABLE `tl_report` (
	`id` int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	`url` varchar(100),
	`motive` varchar(40),
	`content` text(500),
	`author` int UNSIGNED,
	CONSTRAINT `fk_author_report` FOREIGN KEY (author) REFERENCES tl_user(id) 
);

/*
	mensagens entre os usuários
*/
CREATE OR REPLACE TABLE `tl_message`(
 	`id` int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	`content` text(200),
	`to_destinatary` int UNSIGNED not null,
	`from_author` int UNSIGNED not null,
	`is_read` boolean,
	CONSTRAINT `fk_destiny` FOREIGN KEY (to_destinatary) REFERENCES tl_user(id),
	CONSTRAINT `fk_author` FOREIGN KEY (from_author) REFERENCES tl_user(id)
);

CREATE OR REPLACE TABLE `tl_notif_type`(
	`id` smallint UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	`text` varchar(100)
);

CREATE OR REPLACE TABLE `tl_notification`(
 	`id` int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 	`user_notify` int UNSIGNED,
	`author_notify` int UNSIGNED,
	`time` TIMESTAMP DEFAULT NOW(),
	`url` VARCHAR(200),
	`type_not` smallint UNSIGNED,
	CONSTRAINT `fk_user_author` FOREIGN KEY(author_notify) REFERENCES tl_user(id),
	CONSTRAINT `fk_notification` FOREIGN KEY(type_not) REFERENCES tl_notif_type(id),
	CONSTRAINT `fk_user_notify` FOREIGN KEY(user_notify) REFERENCES tl_user(id)
);

CREATE OR REPLACE TABLE `tl_follows`(
	`id` int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	`followed` int UNSIGNED NOT NULL,
	`following` int UNSIGNED NOT NULL,
	CONSTRAINT `fk_followed` FOREIGN KEY(followed) REFERENCES tl_user(id),
	CONSTRAINT `fk_following` FOREIGN KEY(following) REFERENCES tl_user(id)
);

CREATE OR REPLACE TABLE `tl_contact`(
	`id` int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	`subject` VARCHAR(200) not null,
	`content` text not null,
	`email` varchar(200) DEFAULT NULL
);

insert into tl_category values(1, "Jogos", "/category/jogos", NULL);
insert into tl_category values(2, "Educação", "/category/educacao", NULL);
insert into tl_category values(3, "Política", "/category/politica", NULL);
insert into tl_category values(4, "Ambiente", "/category/ambiente", NULL);
insert into tl_category values(5, "Artes e Entetenimento", "/category/artes-e-entretenimento", NULL);
insert into tl_category values(6, "Animes", "/category/animes", NULL);
insert into tl_category values(7, "Mangás", "/category/mangas", NULL);
insert into tl_category values(8, "Videos", "/category/videos", NULL);
insert into tl_category values(9, "Esportes", "/category/esportes", NULL);
insert into tl_category values(10, "Beleza e bem estar", "/category/beleza-bem-estar", NULL);
insert into tl_category values(11, "Negócios", "/category/negocios", NULL);
insert into tl_category values(12, "Computadores", "/category/computadores", NULL);
insert into tl_category values(13, "Tecnologia", "/category/tecnologia", NULL);
insert into tl_category values(14, "Viagem", "/category/viagem", NULL);
insert into tl_category values(15, "Livros", "/category/livros", NULL);





