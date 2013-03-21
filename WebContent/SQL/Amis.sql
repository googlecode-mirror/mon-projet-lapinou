-- drop table lapin_Ami cascade constraints;


-- creation de la table 
create table lapin_Ami (idProprio varchar(30),
                        idAmi varchar(30),
                        constraint FKAmi1 foreign key (idProprio) references lapin_proprietaire (identifiant) on delete cascade,
                        constraint FKAmi2 foreign key (idAmi) references lapin_proprietaire (identifiant) on delete cascade);
