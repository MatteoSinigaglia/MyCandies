DELETE FROM `Products`;
INSERT INTO `Products` (`id`, `category_id`, `name`, `description`, `price`, `availability`) VALUES 

(1, 1, "Balsamiche nootropiche", "Questa classica caramella ha da sempre una forma rettangolare. Il suo gusto è familiare, gradevole e aromatico, e viene in aiuto in caso di tosse ma in questa versione permette lo sviluppo di telecinesi. La ricetta dell’originale cristallo di zucchero alle erbe tanto apprezzato in tutto il mondo è rimasta praticamente invariata dal 1940 ed è custodita con la massima segretezza. Una cosa però la sveliamo: la forza di questa caramella è data dai nootropi. I nootropi conosciuti anche come (farmaci intelligenti), sono sostanze che aumentano le capacità cognitive dell'essere umano (abilità e funzionalità del cervello). Generalmente, i nootropi lavorano aumentando il rilascio di agenti neurochimici (neurotrasmettitori, enzimi e ormoni), migliorando l'apporto di ossigeno al cervello o stimolando la crescita nervosa.", 8.99, 30),

(2, 2, "Taurina mou", "Le caramelle mou si ottengono sciogliendo nel latte freddo lo zucchero per poi portarlo ad ebollizione mescolando frequentemente fino a raggiungere un composto tipo marmellata. La nostra versione prevede l'aggiunta di grosse quantità di taurina, la quale permette, in seguito all'assunzione, di muoversi a super velocità. <span xml:lang='en'>Grant Gustin</span>, attore che interpreta <span xml:lang='en'>Barry Allen</span>, nella famosa serie <span xml:lang='en'>The Flash</span>, assume regolarmente 'Taurina mou' prima delle scena d'azione, rendendo così inutile il ruolo di controfigure ed effetti speciali.", 10, 300),

(3, 5, "Cola ionizzate", "gas ionizzato", 23.00, 450),

(4, 4, "Anguria leggera", "elio", 150.00, 100),

(5, 5, "Squali ionizzati", "gas ionizzato", 98.00, 2000),

(6, 3, "Testocola", "testosterone", 549.00, 200),

(7, 3, "Orsetti filosofali", "pietra filosofale", 6, 300),

(8, 5, "Liquirizie ripiene di caffeina", "caffeina", 70.00, 5),

(9, 2, "Caramelle al caffè gusto caffè", "caffeina", 64.50, 40)
;

DELETE FROM `Categories`;
INSERT INTO `Categories` (`id`, `name`) VALUES (1,'Balsamiche'),(2,'Senza zucchero'),(3,'Gelatine'),(4,'Frutta'),(5,'Gommose');

DELETE FROM `ActivePrinciples`;
INSERT INTO `ActivePrinciples` (`id`, `name`) VALUES (1,'Taurina'),(2,'Gas ionizzato'),(3,'Testosterone'),(4,'Materia bianca'),(5,'Nootropi'),(6,'Elio'),(7,'Pietra filosofale'),(8,'Caffeina');

DELETE FROM `Effects`;
INSERT INTO `Effects` (`id`, `name`) VALUES (1,'Superforza'),(2,'Super velocità'),(3,'Capacità di diventare invisibili'),(4,'Capacità di indurire il corpo'),(5,'Possibilità di controllare l''elettricità'),(6,'Capacità di volare'),(7,'Controllo della mente'),(8,'Telecinesi');

DELETE FROM `SideEffects`;
INSERT INTO `SideEffects` (`id`,`name`) VALUES(1,'Stanchezza'),(2,'Irritabilità'),(3,'Disturbo del sonno'),(4,'Disturbo della personalità'),(5,'Tachicardia'),(6,'Vomito'),(7,'Mancanza di concentrazione'),(8,'Spasmi');

DELETE FROM `ProductsActivePrinciples`;
INSERT INTO `ProductsActivePrinciples` (`product_id`, `active_principle_id`, `percentage`) VALUES (1,5,20),(2,1,80),(3,2,90),(4,6,15),(5,2,65),(6,3,40),(7,7,99),(8,8,35),(9,8,70);

DELETE FROM `ActivePrinciplesEffects`;
INSERT INTO `ActivePrinciplesEffects` (`active_principle_id`, `effect_id`) VALUES (1,2),(2,5),(3,1),(4,7),(5,8),(6,6),(7,4),(8,8);

DELETE FROM `ActivePrinciplesSideEffects`;
INSERT INTO `ActivePrinciplesSideEffects` (`active_principle_id`, `side_effect_id`) VALUES (1,1),(2,5),(3,3),(4,4),(5,2),(6,8),(7,6),(8,7);

DELETE FROM `Images`;
INSERT INTO `Images` (`id`, `img_path`) VALUES (1,'../img/products/balsamiche.png'),(2,'../img/products/mou.jpg'),(3,'../img/products/cola_gommose.jpg'),(4,'../img/products/anguria.jpg'),(5,'../img/products/squali_gommosi.jpg'),(6,'../img/products/cola.jpg'),(7,'../img/products/orsetti.jpg'),(8,'../img/products/liquirizia_ripiene.jpg'),(9,'../img/products/caffe.png');

DELETE FROM `ProductsImages`;
INSERT INTO `ProductsImages` (`img_id`, `product_id`) VALUES (1,1),(2,2),(3,3),(4,4),(5,5),(6,6),(7,7),(8,8),(9,9);
