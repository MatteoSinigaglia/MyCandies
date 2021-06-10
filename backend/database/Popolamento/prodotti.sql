DELETE FROM `Products`;
INSERT INTO `Products` (`id`, `category_id`, `name`, `description`, `price`, `availability`) VALUES 

(1, 1, "Balsamiche nootropiche", "Questa classica caramella ha da sempre una forma rettangolare. Il suo gusto è familiare, gradevole e aromatico, e viene in aiuto in caso di tosse ma in questa versione permette lo sviluppo di telecinesi. La ricetta dell’originale cristallo di zucchero alle erbe tanto apprezzato in tutto il mondo è rimasta praticamente invariata dal 1940 ed è custodita con la massima segretezza. Una cosa però la sveliamo: la forza di questa caramella è data dai nootropi. I nootropi conosciuti anche come (farmaci intelligenti), sono sostanze che aumentano le capacità cognitive dell'essere umano (abilità e funzionalità del cervello). Generalmente, i nootropi lavorano aumentando il rilascio di agenti neurochimici (neurotrasmettitori, enzimi e ormoni), migliorando l'apporto di ossigeno al cervello o stimolando la crescita nervosa.", 8.99, 30),

(2, 2, "Taurina mou", "Le caramelle mou si ottengono sciogliendo nel latte freddo lo zucchero per poi portarlo ad ebollizione mescolando frequentemente fino a raggiungere un composto tipo marmellata. La nostra versione prevede l'aggiunta di grosse quantità di taurina, la quale permette, in seguito all'assunzione, di muoversi a super velocità. <span xml:lang='en'>Grant Gustin</span>, attore che interpreta <span xml:lang='en'>Barry Allen</span>, nella famosa serie <span xml:lang='en'>The Flash</span>, assume regolarmente 'Taurina mou' prima delle scena d'azione, rendendo così inutile il ruolo di controfigure ed effetti speciali.", 10, 300),

(3, 5, "Ciucci cola ionizzate", "Le nostre ciucci cola sono un perfetto snack in situazioni di stress o stanchezza. Il contenuto di gas ionizzato permette infatti di aumentare notevolmente la concentrazione del consumatore. Il prezzo economico è giustificato dalla durata dell'effetto conferito dopo il consumo che è limitato a 23 minuti. Non ci sono contro indicazioni però, è possibile assumere una quantità illimitata di queste caramelle.", 23.00, 450),

(4, 4, "Anguria leggera", "Queste caramelle a forma di anguria hanno innaspettatamente il gusto di anguria, o meglio un leggero gusto di anguria. Il loro elevento contenuto di elio renderà difficile assumerle una volta aperto il pacchetto. Anche l'assunzione potrebbe non risultare così semplice! Una volta assunta la giusta quantità di caramelle sarete pieni di elio e pronti a prendere il volo per qualsiasi direzione! Le dosi possono variare in relazione al peso del soggetto.", 150.00, 100),

(5, 5, "Coccodrionizzati", "Il coccodrillo più dolce di tutti con la pancia di marshmallow delizia tutte le generazioni di buongustai dal 2010. Con la sua tenera consistenza e la sua forma divertente il coccodrillo gommoso viene assunto per aiutarci in momenti un pò meno divertenti. Il contenuto di gas ionizzato permette di aumentare la concentrazione di chi lo assume, rendendolo un prodotto utile per studenti, lavoratori e chi ha bisogno di una spinta extra dopo una sbornia.", 98.00, 2000),

(6, 3, "Kryptocola", "Le nostre bottigliette Kryptocola aumentano la sete di avventura da più di 50 anni, in viaggio e durante le migliori feste. Ogni bottiglietta gommosa alla cola nasconde una piccola avventura tutta sua! Questo prodotto rappresenta la punta di diamante della nostra catena, si potrebbe definire 'All-in'. Questa caramella infatti può conferire la maggior parte della abilità. Gli attori che interpretano Superman nei diversi film assumono Kryptocola per registrare le diverse scene di azione. Il prodotto più completo che possa esserci: velocità, super forza, capacità di volare e raggi laser.", 549.00, 200),

(7, 3, "Orsetti filosofali", "Il nome di questi simpatici orsetti deriva dalla famosa Pietra filosofale. La pietra filosofale o pietra dei filosofi è, per eccellenza, la sostanza catalizzatrice simbolo dell'alchimia, capace di risanare la corruzione della materia. Siamo stati in grado di estrarre da quest'ultima interessanti proprietà che permettono di invertire la direzione del tempo del consumatore, permettendo un ringiovanimento fino a 5 anni per ogni assunzione. Che dire, il costo è elevato, ma alla fine il costo della vita stessa.", 649.00, 300),

(8, 5, "Caffebat", "Il tubetto più goloso che c'è: avvolto da uno strato gommoso di liquirizia, il morbido ripieno dal sapore di caffè amaro colorato dà il meglio di sé. Ogni momento è quello giusto per gustare le caramelle alla liquirizia Caffebat, che tu sia letto, a lavoro o in qualsiasi altra situazione in cui volessi raggiungere un oggetto senza sforzi, Caffebat sono le caramelle che fanno per te. L'alto contenuto di caffeina permette di stimolare ogni area del cervello permettendo di sviluppare utilissime abilità telecinetiche.", 70.00, 5),

(9, 2, "Eucalkrypto", "Queste caramelle dal colore verde lasciano intendere quale sostanza sia in loro contenuta. Sono perfette per un momento di relax assoluto: come una pesantissima seduta di pesi. Gli atleti d'elité mondiale degli sport di forza portano sempre in gara con loro una caramella Eucalkrypto per aumentare a dismisura la loro forza. Per limitare il costo di queste caramelle la dose di Kryptonite contenuta è ridotta! No, non è una sostanza dopante, essendo un prodotto totalmente naturale prelevato dal pianeta Krypton.", 64.50, 40)
;

DELETE FROM `Categories`;
INSERT INTO `Categories` (`id`, `name`) VALUES (1,'Balsamiche'),(2,'Senza zucchero'),(3,'Gelatine'),(4,'Frutta'),(5,'Gommose');

DELETE FROM `ActivePrinciples`;
INSERT INTO `ActivePrinciples` (`id`, `name`) VALUES (1,'Taurina'),(2,'Gas ionizzato'),(3,'Kryptonite'),(4,'Materia bianca'),(5,'Nootropi'),(6,'Elio'),(7,'Pietra filosofale'),(8,'Caffeina');

DELETE FROM `Effects`;
INSERT INTO `Effects` (`id`, `name`) VALUES (1,'Superforza'),(2,'Super velocità'),(3,'Capacità di diventare invisibili'),(4,'Ringiovanimento'),(5,'Super concentrazione'),(6,'Capacità di volare'),(7,'Controllo della mente'),(8,'Telecinesi');

DELETE FROM `SideEffects`;
INSERT INTO `SideEffects` (`id`,`name`) VALUES(1,'Stanchezza'),(2,'Irritabilità'),(3,'Disturbo del sonno'),(4,'Disturbo della personalità'),(5,'Tachicardia'),(6,'Vomito'),(7,'Mancanza di concentrazione'),(8,'Spasmi');

DELETE FROM `ProductsActivePrinciples`;
INSERT INTO `ProductsActivePrinciples` (`product_id`, `active_principle_id`, `percentage`) VALUES (1,5,20),(2,1,80),(3,2,90),(4,6,15),(5,2,65),(6,3,40),(7,7,99),(8,8,35),(9,8,70);

DELETE FROM `ActivePrinciplesEffects`;
INSERT INTO `ActivePrinciplesEffects` (`active_principle_id`, `effect_id`) VALUES (1,2),(2,5),(3,1),(4,7),(5,8),(6,6),(7,4),(8,8);

DELETE FROM `ActivePrinciplesSideEffects`;
INSERT INTO `ActivePrinciplesSideEffects` (`active_principle_id`, `side_effect_id`) VALUES (1,1),(2,5),(3,3),(4,4),(5,2),(6,8),(7,6),(8,7);

DELETE FROM `Images`;
INSERT INTO `Images` (`id`, `img_path`) VALUES (1,'../img/products/balsamiche.png'),(2,'../img/products/mou.jpg'),(3,'../img/products/cola_gommose.jpg'),(4,'../img/products/anguria.jpg'),(5,'../img/products/coccodri.jpg'),(6,'../img/products/cola.jpg'),(7,'../img/products/orsetti.jpg'),(8,'../img/products/liquirizia_ripiene.jpg'),(9,'../img/products/eucalipto.jpg');

DELETE FROM `ProductsImages`;
INSERT INTO `ProductsImages` (`img_id`, `product_id`) VALUES (1,1),(2,2),(3,3),(4,4),(5,5),(6,6),(7,7),(8,8),(9,9);
