DELETE FROM `Products`;
INSERT INTO `Products` (`id`, `category_id`, `name`, `description`, `price`, `availability`) VALUES 

(1,1,"Xanax - confezione da 20 compresse","Lo Xanax è un potente farmaco ansiolitico utilizzato per la cura di patologie quali il disturbo da ansia, tensione e attacchi di panico. Il suo principio attivo, l'Alprazolam, appartiene alla classe di molecole denominata 'benzodiazepine'. Lo Xanax è indicato anche per i trattamenti di disturbo del sonno per la sua azione antistress. Gli effetti dello Xanax hanno una durata che va dalle 11 alle 16 ore, in base alla composizione corporea. Lo Xanax, come per le altre benzodiazepine, è controindicato per utilizzatori con miastenia grave, insufficienza respiratoria grave, sindrome da apnea notturna, insufficienza epatica grave e per le donne in gravidanza.",8.99,30),
(2,2,"Marijuana - confezione da 1 grammo","La marijuana è una sostanza psicoattiva che si ottiene dalle infiorescenze essiccate delle piante femminili di canapa. L'assunzione avviene tramite aspirazione di fumi prodotti dalla combustione del prodotto, in genere bruciato sotto forma di sigaretta rollata o all'interno di pipe apposite o bong. La marijuana è una droga leggera utilizzata anche in ambito medico come palliativo per i dolori. Il suo principio attivo, il THC, contraddistingue la categoria dei cannabinoidi. La marijuana non ha controindicazioni per specifici pazienti anche se un uso prolungato può portare a conseguenze indesiderate come la perdita parziale della memoria. La marijuana causa dipendenza quindi vedete di comprarne tanta e fate attenzione a non dimenticarvi di inviarci le credenziali della vosrta carta di credito così se ve le dimenticate ci pensiamo noi a fare il pagamento.",10,300),

(3,5,"Crack - 1 bottiglietta", "Il crack è una droga sintetica prodotta tramite la cristallizzazione della cocaina. È stato originariamente concepito e sintetizzato per uno scopo ben preciso: era destinato ai cocainomani cronici come sostituto della cocaina, in quanto l'assunzione nasale della cocaina provocava la distruzione dei tessuti nasali, per cui l'unica modalità di assunzione alternativa era rappresentata dall'inalazione. È uno stupefacente altamente pericoloso in grado di indurre elevata dipendenza e rapida assuefazione psicologica e fisica, inoltre è in grado di aumentare gli istinti violenti e alterare i principali centri di controllo del sistema nervoso centrale. Spesso porta all'alienazione sociale o a forme di psicosi.", 23.00, 450),

(4,4,"LSD - confezione da 10 cartoncini", "LSD è una tra le più potenti sostanze psichedeliche conosciute. LSD è prodotto sotto forma di cristalli e mischiato con eccipienti o diluito. E' venduto in piccole tavolette, su cubetti di zucchero, in cubetti di gelatina o, in pezzi di cartoncino sui quali è stato versato un quantitativo minimo della sostanza in forma liquida. Consistono principalmente in alterazione della coscienza, euforia, perdita di consapevolezza e lucidità, riduzione dei riflessi psicofisici, alterazioni nella memoria a breve e lungo termine, sensazione di intensa beatitudine, emozioni amplificate (tuttavia non alterate), aumento dell'apprezzamento musicale; a dose media provoca allucinazioni geometriche e frattali, amplificazioni sensoriali, distorsione della consapevolezza del tempo.", 150.00, 100),

(5,5,"Coca - confezione da 1 grammo","La cocaina è una sostanza stupefacente che agisce come potente stimolante del sistema nervoso centrale, vasocostrittore e anestetico. La cocaina crea dipendenza, è la seconda droga illegale più utilizzata a livello globale, dopo la cannabis. I sintomi principali sono perdita di contatto con la realtà e sensazioni di felicità o agitazione. I sintomi fisici possono includere battito cardiaco accelerato, sudorazione aumentata e dilatazione delle pupille.",98.00,20000),

(6,3,"Eroina gialla - confezione da 1 grammo","L'eroina è un derivato della morfina, alcaloide principe dell'oppio. L'eroiana gialla non è altro che eroina giallina perché contiene meno solveni. Gli effetti dell'eroina sono divisi in gradi e se la droga è assunta la prima volta per via endovenosa l'effetto dura circa due ore: si raggiunge un'estasi pari quasi a un orgasmo che si diffonde a tutta la muscolatura del corpo; (dopo circa 20 min): i legami associativi sono più lenti, il pensiero rallenta e perde un senso logico; (dopo circa un'ora): compare il picco massimo dell'effetto: la mente raggiunge una sensazione di pace, il corpo, anestetizzato da un incondizionato senso di piacere.", 49.00, 200),

(7,3,"Makatussin - flacone da 80ml","Il Makatussin è uno sciroppo per la tosse il quale, miscelato con la bevanda '<span xml:lang='en'>Sprite</span>' reagisce e amplifica l'effetto della codeina in esso contenuta. La codeina è un analgesico che ha anche effetti antiussigeni, che agisce intervenendo sul modo in cui il cervello e il sistema nervoso percepiscono il dolore e riducendo l’attività delle parti del cervello che controllano la tosse. La durata degli effetti varia tra le due e le tre ore. L'utilizzo della codeina è sconsigliato agli utenti che soffrono di asma o patologie respiratorie.",6,300),

(8,5,"Ecstasy - confezione da 4 pastiglie", "In termini farmacologici l’MDMA si situa a metà fra i composti stimolanti e quelli allucinogeni; ha una primaria influenza a livello comunicativo ed emozionale, 'svelando' la psiche dell’individuo e toccandolo in posti nascosti. Per tali motivi l’MDMA venne inizialmente inserita in medicina nella terapia psicanalitica. Gli effetti appaiono con un senso di malessere, di respiro affannoso, di paura per poi rivelarsi in quelli desiderati: incrementato interesse nei rapporti interpersonali, vigilanza e resistenza fisica. La dipendenza da tali sostanze è esclusivamente psicologica. Uno dei pericoli più gravi è costituito dall’elevata neurotossicità. Studi approfonditi hanno dimostrato la degenerazione irreversibile dei neuroni.", 70.00, 5),

(9,5,"Crystal Meth - confezione da 5 grammi","Con il nome '<span xml:lang='en'>Crystal meth</span>' ci si riferisce alla forma più pura della metanfetamina, ovvero cristalli solitamente limpidi di D-metanfetamina cloridrato. Spesso viene fumata o iniettata con effetti di gran lunga superiori alle altre vie di assunzione. L'effetto molto lungo (6-12 ore) è una delle caratteristiche principali di questo tipo di sostanze. Il <span xml:lang='en'>Crystal Meth</span> è stato particolarmente reso celebre dalla serie TV <span xml:lang='en'>Breaking Bad</span>.",600,40)
;

DELETE FROM `Categories`;
INSERT INTO `Categories` (`id`, `name`) VALUES (1,'Benzodiazepine'),(2,'Cannabinoidi'),(3,'Oppiacei'),(4,'Allucinogeni'),(5,'Stimolanti');

DELETE FROM `ActivePrinciples`;
INSERT INTO `ActivePrinciples` (`id`, `name`) VALUES (1,'THC'),(2,'Cocaina'),(3,'Morfina'),(4,'Metanfetamina'),(5,'Alprazolam'),(6,'Dietilamide dell''acido lisergico'),(7,'Codeina'),(8,'3,4-metilenediossimetanfetamina');

DELETE FROM `Effects`;
INSERT INTO `Effects` (`id`, `name`) VALUES (1,'Analgesia'),(2,'Euforia'),(3,'Rilassamento'),(4,'Amplificazione delle percezioni sensoriali'),(5,'Maggior energia'),(6,'Loquacità'),(7,'Riduzione del senso di fame'),(8,'Riduzione della sensazione di ansia');

DELETE FROM `SideEffects`;
INSERT INTO `SideEffects` (`id`,`name`) VALUES(1,'Apatia'),(2,'Depressione'),(3,'Disturbo del sonno'),(4,'Disturbo della personalità'),(5,'Tachicardia'),(6,'Vomito'),(7,'Schizofrenia'),(8,'Allucinazioni');

DELETE FROM `ProductsActivePrinciples`;
INSERT INTO `ProductsActivePrinciples` (`product_id`, `active_principle_id`, `percentage`) VALUES (1,5,20),(2,1,80),(3,2,90),(4,6,15),(5,2,65),(6,3,40),(7,7,99),(8,8,35),(9,8,70);

DELETE FROM `ActivePrinciplesEffects`;
INSERT INTO `ActivePrinciplesEffects` (`active_principle_id`, `effect_id`) VALUES (1,2),(2,5),(3,1),(4,7),(5,8),(6,6),(7,4),(8,8);

DELETE FROM `ActivePrinciplesSideEffects`;
INSERT INTO `ActivePrinciplesSideEffects` (`active_principle_id`, `side_effect_id`) VALUES (1,1),(2,5),(3,3),(4,4),(5,2),(6,8),(7,6),(8,7);

DELETE FROM `Images`;
INSERT INTO `Images` (`id`, `img_path`) VALUES (1,'../img/products/xanax.jpg'),(2,'../img/products/marijuana.jpg'),(3,'../img/products/crack.jpg'),(4,'../img/products/lsd.jpeg'),(5,'../img/products/cocaina.jpg'),(6,'../img/products/eroina.jpg'),(7,'../img/products/makatussin.jpg'),(8,'../img/products/ecstasy.jpg'),(9,'../img/products/crystal_meth.jpg');

DELETE FROM `ProductsImages`;
INSERT INTO `ProductsImages` (`img_id`, `product_id`) VALUES (1,1),(2,2),(3,3),(4,4),(5,5),(6,6),(7,7),(8,8),(9,9);