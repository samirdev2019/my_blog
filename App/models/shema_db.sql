
CREATE TABLE categorys (
                id INT AUTO_INCREMENT NOT NULL,
                name VARCHAR(255) NOT NULL,
                PRIMARY KEY (id)
);


CREATE TABLE users (
                user_id INT AUTO_INCREMENT NOT NULL,
                username VARCHAR(50) NOT NULL,
                email VARCHAR(255) NOT NULL,
                password VARCHAR(255) NOT NULL,
                first_name VARCHAR(50) NOT NULL,
                last_name VARCHAR(50) NOT NULL,
                role ENUM('admin', 'contribitor', 'visitor') NULL,
                validated BOOLEAN NOT NULL,
                PRIMARY KEY (user_id)
);


CREATE TABLE posts (
                post_id INT NOT NULL,
                title VARCHAR(255) NOT NULL,
                author VARCHAR(255) NOT NULL,
                chapo TEXT NOT NULL,
                created DATETIME NOT NULL,
                updated DATETIME NOT NULL,
                content TEXT NOT NULL,
                online BOOLEAN NOT NULL,
                slug VARCHAR(255) NOT NULL,
                cotegory_id INT NOT NULL,
                user_id INT NOT NULL,
                PRIMARY KEY (post_id)
);


CREATE TABLE comments (
                comment_id INT AUTO_INCREMENT NOT NULL,
                commented DATETIME NOT NULL,
                content TEXT NOT NULL,
                validated BOOLEAN NOT NULL,
                post_id INT NOT NULL,
                user_id INT NOT NULL,
                PRIMARY KEY (comment_id)
);


ALTER TABLE posts ADD CONSTRAINT categorys_posts_fk
FOREIGN KEY (cotegory_id)
REFERENCES categorys (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION;

ALTER TABLE posts ADD CONSTRAINT users_posts_fk
FOREIGN KEY (user_id)
REFERENCES users (user_id)
ON DELETE NO ACTION
ON UPDATE NO ACTION;

ALTER TABLE comments ADD CONSTRAINT users_comments_fk
FOREIGN KEY (user_id)
REFERENCES users (user_id)
ON DELETE NO ACTION
ON UPDATE NO ACTION;

ALTER TABLE comments ADD CONSTRAINT posts_comments_fk
FOREIGN KEY (post_id)
REFERENCES posts (post_id)
ON DELETE NO ACTION
ON UPDATE NO ACTION;