CREATE TABLE person
(
    id   int(11) unsigned NOT NULL AUTO_INCREMENT,
    name varchar(255) DEFAULT '' NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE vacation
(
    id   int(11) unsigned NOT NULL AUTO_INCREMENT,
    date int(11) unsigned DEFAULT '0' NOT NULL,
    persons varchar(255) DEFAULT '' NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE shift
(
    id   int(11) unsigned NOT NULL AUTO_INCREMENT,
    from_time int(11) unsigned DEFAULT '0' NOT NULL,
    to_time int(11) unsigned DEFAULT '0' NOT NULL,
    person_id int(11) unsigned DEFAULT '0' NOT NULL,
    PRIMARY KEY (id)
);