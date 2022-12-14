<?php

use Illuminate\Database\Seeder;
use App\Colony;

class colonyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $colonias = ["12 De Julio",
            "15 De Enero",
            "15 De Mayo",
            "16 De Septiembre",
            "1Era Priv O Rev",
            "9 De Septiembre",
            "Acacias",
            "Acequias",
            "Adolfo Lopez Mateos",
            "Aguilas",
            "Aguilas De Zaragoza Etapa 1",
            "Aguilas De Zaragoza Etapa 2",
            "Agustin Melgar",
            "Alameda",
            "Alameda Galgodromo",
            "Alamos De San Lorenzo",
            "Alamos De Senecu",
            "Alamos Pronaf",
            "Alborada",
            "Alcazar",
            "Almendros",
            "Almendros Ii",
            "Alondra",
            "Altamira",
            "Altavista",
            "Alvaro Obregon",
            "Americas",
            "Ampl Aeropuerto",
            "Ampl Felipe Angeles",
            "Ampl Francisco Sarabia",
            "Ampl Fronteriza",
            "Ampl Fronteriza Baja",
            "Ampl Inf Aeropuerto",
            "Ampl Lazaro Cardenas",
            "Ampl Loma Blanca",
            "Ampl Papalote",
            "Ampl Plutarco E Calles",
            "Ampl Plutarco Elias Calles",
            "Ampl San Isidro",
            "Ampl Silgo Xxi",
            "Ampl Vista De Las Cumbres",
            "Ampliacion Aeropuerto",
            "Ampliacion Felipe Angeles",
            "Ampliacion Francisco Sarabia",
            "Ampliacion Fronteriza",
            "Anahuac",
            "And Del Guerrillero",
            "Andalucia",
            "Andres Figueroa",
            "Anexas",
            "Antares",
            "Anzures",
            "Arboleda",
            "Arboleda De San Fernando",
            "Arenales",
            "Arenas",
            "Arroyo Colorado",
            "Arroyo Del Paraiso",
            "Atenas",
            "Atenas Ii",
            "Aurora",
            "Av Alberto Talavera",
            "Av Azteca",
            "Av Aztecas",
            "Av Benjamin Franklin",
            "Av Carlos Amaya",
            "Av De Aztecas",
            "Av De La Paz",
            "Av De Las Torres",
            "Av De Los Aztecas",
            "Av Del Granjero",
            "Av Eje Vial Juan Gabriel",
            "Av El Granjero",
            "Av General Ponciano Arriaga",
            "Av Georgina",
            "Av Gral Ponciano Arriaga",
            "Av Granjero",
            "Av Guadalupe",
            "Av Henequen",
            "Av Jilotepec",
            "Av Jose De Iturrigaray",
            "Av Josefina",
            "Av Los Aztecas",
            "Av Manuel J Clouthier",
            "Av Manuel Jesus Clouthier",
            "Av Oasis Revolucion",
            "Av Panamericana",
            "Av Pavorreal",
            "Av Plutarco Elias Calles",
            "Av Ponciano Arriaga",
            "Av Quinta Granada Poniente",
            "Av Quinta Granada Pte",
            "Av Rafael Murguia",
            "Av Regina",
            "Av San Antonio",
            "Av Tecnologico",
            "Av Tercera",
            "Av Universidad Tecnologica",
            "Ave Josefina",
            "Ave Regina",
            "Avicola Emiliano Zapata ( Km 22 )",
            "Azteca",
            "Banus 360",
            "Barrio Alto",
            "Barrio Azul",
            "Barrio Azul/Barrio Nuevo",
            "Bca Oasis De Cracovia",
            "Bellavista",
            "Bello Horizonte",
            "Benito Juarez",
            "Blvd Antonio Maria De  Bucareli",
            "Blvd Cieneguillas",
            "Blvd Francisco Indalesio Madero",
            "Blvd Gaviota",
            "Blvd Gral Calixto Contreras",
            "Blvd Gral Severiano Ceniceros",
            "Blvd Lic Oscar Flores Sanchez",
            "Blvd Oscar Flores",
            "Blvd Oscar Flores Sanchez",
            "Blvd Pedro Baranda",
            "Blvd Zaragoza",
            "Boca Del Rio",
            "Bonanza",
            "Bosque Bonito/Colinas Del Sol",
            "Bosques De Salvarcar",
            "Bosques De San Jose",
            "Bosques De Santa Fe",
            "Bosques De Senecu",
            "Bosques De Sicomoros",
            "Bosques De Waterfill",
            "Bosques Del Sol",
            "Bosques Del Valle",
            "Buenos Aires",
            "Bugambilias",
            "Burocrata",
            "C  Presa De Las Tortolas",
            "C  V",
            "C 0",
            "C 0Delicias",
            "C 0Rtiga",
            "C 0Topinambo Sur",
            "C 10",
            "C 11",
            "C 12",
            "C 12 De Octubre",
            "C 14",
            "C 14 -",
            "C 15",
            "C 15 De Mayo",
            "C 15 De Septiembre",
            "C 16",
            "C 16 De Septiembre",
            "C 17",
            "C 18",
            "C 1Ra Privada Oasis Revolucion",
            "C 2",
            "C 2 De Abril",
            "C 2 De Octubre",
            "C 20",
            "C 20 Regimiento De Caballeria",
            "C 23 De Septiembre",
            "C 2A De Queretaro",
            "C 2A Priv  Acolhuas",
            "C 2A Priv De  Pe??uelillas",
            "C 2A Priv Penuelillas",
            "C 2Da De Pedro Meza",
            "C 2Da De Pe??uelillas",
            "C 2Da Pedro Meza",
            "C 2Da Priv Oasis Revolucion",
            "C 2Da Priv Pe??uelillas",
            "C 2Da Privada Acolhuas",
            "C 2Da Privada Pe??uelillas",
            "C 3",
            "C 3 Castillos",
            "C 30 De Abril",
            "C 36",
            "C 37",
            "C 4",
            "C 5 De Abril",
            "C 5 De Febrero",
            "C 6",
            "C 7",
            "C 8",
            "C 8 De Octubre",
            "C 9",
            "C 9A",
            "C A Alvarez",
            "C A Borjon Parga",
            "C A Bucareli",
            "C A Carrera Torres",
            "C A De La Vega",
            "C A Lemus",
            "C A Norzagaray",
            "C A Pantoja",
            "C A Ramirez",
            "C Acambay",
            "C Acapulco",
            "C Acatenango",
            "C Aceituna Negra",
            "C Acequia Parcioneros",
            "C Acolhuas",
            "C Acuario",
            "C Acultzingo",
            "C Adela Velarde",
            "C Adela Velarde De  Perez",
            "C Adela Velarde Perez",
            "C Adelaida Diaz",
            "C Adolfo Terrones",
            "C Afganistan",
            "C Agapito Teran",
            "C Agate",
            "C Agave",
            "C Ageo Meneses",
            "C Agricultores",
            "C Aguamarina",
            "C Aguililla",
            "C Aguirre Laredo",
            "C Agustin De La Vega",
            "C Aida",
            "C Ajillo",
            "C Ajonjoli",
            "C Ajusco",
            "C Alabastro",
            "C Alabastros",
            "C Alamo",
            "C Alaska",
            "C Alazan",
            "C Albania",
            "C Albaricoque",
            "C Albatros",
            "C Alberto Alvarez",
            "C Alberto Alvarez A",
            "C Alberto Alvarez Acosta",
            "C Alberto Alvarez Y Alvarez",
            "C Alberto Batiz",
            "C Alberto Carrera",
            "C Alberto Carrera Torres",
            "C Alberto Talavera",
            "C Alberto Vargas",
            "C Aldama",
            "C Alejandrina Ramirez",
            "C Alejandro  Gonzalez",
            "C Alejandro Gandarilla",
            "C Alejandro Garza",
            "C Alejandro Garza Glez",
            "C Alejandro Garza Gonzalez",
            "C Alejandro Parra",
            "C Alejandro Ramirez",
            "C Alejandro Ramirez Sur",
            "C Alemania",
            "C Alferez Andres De Peralta",
            "C Alfonso Casta??eda",
            "C Alforfon",
            "C Alfredo Lewis",
            "C Alfredo Martinez",
            "C Alicia",
            "C Alicia Oviedo",
            "C Alicia Oviedo Mota",
            "C Alma Delia Rojas",
            "C Alma Delia Rojas Rodriguez",
            "C Almadre",
            "C Almagre",
            "C Almendra Espa??ola",
            "C Almendra Espa??ola",
            "C Alpes Suizos",
            "C Alpiste",
            "C Alto Volta",
            "C Alumbre",
            "C Amador Fonceca",
            "C Amador Fonseca",
            "C Amatista",
            "C Amaya",
            "C Amelia",
            "C America Latina",
            "C Amozoc",
            "C Anastacio Pantoja",
            "C Anastasio Pantoja",
            "C Andador Del Guerrillero",
            "C Andalucia",
            "C Andora",
            "C Andorra",
            "C Andres Figueroa",
            "C Andres Ortiz",
            "C Andres Portillo",
            "C Anemona",
            "C Angola",
            "C Anis",
            "C Aniz",
            "C Antonio  Lemus",
            "C Antonio  Tamayo",
            "C Antonio De Mendoza",
            "C Antonio Lemus",
            "C Antonio Ma Bucareli",
            "C Antonio Ma De  Bucareli",
            "C Antonio Ma De Bucareli",
            "C Antonio Ma De Bucarelli",
            "C Antonio Maria De  Bucareli",
            "C Antonio Maria De Bucareli",
            "C Antonio Medrano",
            "C Antonio Norzagaray",
            "C Antonio Perez",
            "C Antonio Rabajo",
            "C Antonio Tamayo",
            "C Apozol",
            "C Arabia",
            "C Arabia Saudita",
            "C Arabia Sur",
            "C Arbeja",
            "C Arcilla",
            "C Arco De Tito",
            "C Arenales",
            "C Argelia",
            "C Aries",
            "C Armando Borjon Parga",
            "C Arnoldo Casso Lopez",
            "C Arrayan",
            "C Arrollo De Sonora",
            "C Arroyo",
            "C Arroyo De Sonora",
            "C Arroyo Del Indio",
            "C Arroyo Del Jarudo",
            "C Arroyo Jarudo",
            "C Arroyo Sonora",
            "C Arroz",
            "C Arteaga",
            "C Artillero Felipe Ortega",
            "C Arturo Games",
            "C Arturo Gamez",
            "C Arturo Gamiz",
            "C Arveja",
            "C Asbesto",
            "C Asfalto",
            "C Asfodelo",
            "C Aspalato",
            "C Astral",
            "C Atenas",
            "C Atzayacatl",
            "C Atzimba",
            "C Augusto Sandino",
            "C Aurora Boreal",
            "C Aurora Borial",
            "C Australia",
            "C Austria",
            "C Ave. Granjero",
            "C Avelina Gallegos",
            "C Avena",
            "C Avestruz",
            "C Avestruz Poniente",
            "C Aveztruz",
            "C Ayuntamiento",
            "C Ayutla",
            "C Azcapotzalco",
            "C Azogue",
            "C Aztecas",
            "C B De Celaya",
            "C B De Torreon",
            "C B De Zacatecas",
            "C Bagdad",
            "C Bahia De  Banderas",
            "C Bahia De Banderas",
            "C Balcon Del Sol",
            "C Baranquilla",
            "C Barcelona",
            "C Barranco Azul",
            "C Barranquilla",
            "C Basalto",
            "C Batalla  Del Chamizal",
            "C Batalla De Celaya",
            "C Batalla De Chamizal",
            "C Batalla De Juarez",
            "C Batalla De Paredon",
            "C Batalla De Puebla",
            "C Batalla De Santa Rosa",
            "C Batalla De Tomochi",
            "C Batalla De Tomochic",
            "C Batalla De Torreon",
            "C Batalla De Zacatecas",
            "C Batalla Del Carrizal",
            "C Batalla Del Carrrizal",
            "C Batalla Del Chamizal",
            "C Batalla Del Paredon",
            "C Batalla Desanta Rosa",
            "C Baudelio Uribe",
            "C Belgica",
            "C Belice",
            "C Bellota",
            "C Benito Juarez",
            "C Benjamin  Franklin",
            "C Benjamin Franklin",
            "C Berlin",
            "C Bernal Hdez",
            "C Berrendo",
            "C Bilbao",
            "C Birmania",
            "C Bisnaga",
            "C Biznaga",
            "C Blvd Zaragoza",
            "C Boca Del Rio",
            "C Bolivia",
            "C Bolson De Mapimi",
            "C Boquilla",
            "C Borjon Parga",
            "C Bosque De Alamo",
            "C Bosque De Los Arrayanes",
            "C Bracamontes",
            "C Bravo",
            "C Bucareli",
            "C Bucarelli",
            "C Buenos Aires",
            "C Bulgaria",
            "C Burma",
            "C Butan",
            "C C Bernal  Hernandez",
            "C C Bernal De Hdez",
            "C C Bernal De Hernandez",
            "C C Canales",
            "C C Cervantes",
            "C C Contreras",
            "C C Creston",
            "C C De La  Plata",
            "C C Del Caucaso",
            "C C0Ral",
            "C Cacamatzin",
            "C Cachalquies",
            "C Ca??averal",
            "C Cadiz",
            "C Cafetal",
            "C Calandria",
            "C Calchalquies",
            "C Calchaquies",
            "C Calcita",
            "C Calcital",
            "C Calcopirita",
            "C Calera",
            "C Calhidra",
            "C Caliche",
            "C Calidra",
            "C Calixto Contreras",
            "C Calixto Conttreras",
            "C Caliza",
            "C Calle 14",
            "C Calsita",
            "C Camargo",
            "C Camboya",
            "C Camboya Sur",
            "C Camello",
            "C Camerun",
            "C Camilo Cien Fuegos",
            "C Camilo Cienfuegos",
            "C Camilo Torres",
            "C Camorras",
            "C Campeche",
            "C Canada",
            "C Cananea",
            "C Ca??averal",
            "C Cancer",
            "C Candelaria Diaz De Bustamante",
            "C Candelario Cervantes",
            "C Candelilla",
            "C Canela",
            "C Ca??on De Urique",
            "C Cantera",
            "C Caolin",
            "C Capandaro",
            "C Capitan Ageo Meneses",
            "C Capitan Alejandro Parra",
            "C Capitan Antonio Perez",
            "C Capitan Emilio Molina",
            "C Capitan Jose  Granados",
            "C Capitan Jose Granados",
            "C Capitan Jose Rivera",
            "C Capitan Margarito Luna",
            "C Capitan Pedro Meza",
            "C Capitan Salome  Mora",
            "C Capitan Salome Mora",
            "C Capricornio",
            "C Capulin",
            "C Carbonera",
            "C Carboneras",
            "C Carburador",
            "C Caridad",
            "C Caridad B Adams",
            "C Caridad Bravo Adams",
            "C Carlos Almeida",
            "C Carlos Amador Fonseca",
            "C Carlos Amaya",
            "C Carlos Fonseca",
            "C Carlos Marin",
            "C Carlos Marx",
            "C Carlos Ramirez",
            "C Carlota Santini",
            "C Carmen Arenal",
            "C Carmen I De Rios",
            "C Carmen I Rios",
            "C Carmen Ibarra",
            "C Carmen Irigoyen",
            "C Carmen Irigoyen De Rios",
            "C Carmen Irigoyen Rios",
            "C Carolina",
            "C Carretas",
            "C Cartagena",
            "C Cartagena Poniente",
            "C Cartamo",
            "C Cartegena",
            "C Casa De Janos",
            "C Casa Janos",
            "C Casas Grandes",
            "C Catalina",
            "C Catalu??a",
            "C Catalu??a",
            "C Catar",
            "C Catorce",
            "C Caulin",
            "C Cdp",
            "C Cebada",
            "C Ceilan",
            "C Cemento",
            "C Cenegal",
            "C Ceniceros",
            "C Centeno",
            "C Centeno Sur",
            "C Cereza Negra",
            "C Cerrada Gironella",
            "C Cerrada Quinta Dumbria",
            "C Cerrada Quinta Escairon",
            "C Cerro Bola",
            "C Cerro Bufa",
            "C Cerro De Colima",
            "C Cerro De Creston",
            "C Cerro De Cubilete",
            "C Cerro De La  Plata",
            "C Cerro De La Bufa",
            "C Cerro De La Plata",
            "C Cerro De La Silla",
            "C Cerro De Las Colinas",
            "C Cerro De Las Cruces",
            "C Cerro Del Aguila",
            "C Cerro Del Coyote",
            "C Cerro Del Creston",
            "C Cerro Del Indio",
            "C Cerro El Creston",
            "C Cerro Mexico",
            "C Cerro Prieto",
            "C Cervantes",
            "C Cesar Agusto Sandino",
            "C Cesar Augusto Sandino",
            "C Cesar Canales",
            "C Ceylan",
            "C Chalcas",
            "C Chamizal",
            "C Chamizo",
            "C Chamulas",
            "C Chamulas Poniente",
            "C Chauzingo",
            "C Che Guevara",
            "C Checoslovaquia",
            "C Chichen Itza",
            "C Chichimecas",
            "C Chihuahua",
            "C Chimalpopoca",
            "C China",
            "C Chinacantecos",
            "C Chiricahua",
            "C Chirimoya",
            "C Chocholtecas",
            "C Chochultecas",
            "C Cholultecas",
            "C Chontales",
            "C Chontalez",
            "C Cien Fuegos",
            "C Cieneguillas",
            "C Cienfuegos",
            "C Cinabrio",
            "C Ciniabrio",
            "C Cintalapa",
            "C Cirilo Gonzalez",
            "C Ciruelo",
            "C Cisne",
            "C Citlaltepetl",
            "C Citlatepec",
            "C Citlatepetl",
            "C Coahuila",
            "C Codorniz",
            "C Cohuanacotazin",
            "C Colibri",
            "C Colima",
            "C Colina",
            "C Colina  Oriente",
            "C Colina  Poniente",
            "C Colina  Sur",
            "C Colina Amarilla",
            "C Colina Blanca",
            "C Colina Del Oasis",
            "C Colina Del Sur",
            "C Colina Norte",
            "C Colina Oriente",
            "C Colina Poniente",
            "C Colina Pte",
            "C Colina Sur",
            "C Colina Verde",
            "C Colinas De Juarez",
            "C Colinas Del Desierto",
            "C Colinas Del Lago",
            "C Colinas Del Monte",
            "C Colinas Del Oasis",
            "C Colinas Del Prado",
            "C Colinas Del Puerto",
            "C Colinas Del Rio",
            "C Colinas Del Sur",
            "C Colinas Del Valle",
            "C Colnias Del Lago",
            "C Comaltitlan",
            "C Comandante  Felix Merino",
            "C Comandante Felix Merino",
            "C Comatitlan",
            "C Cometa",
            "C Comite De La Defensa Popular",
            "C Comitecos",
            "C Comoras",
            "C Conde De Revillagigedo",
            "C Conde Revillagigedo",
            "C Congo",
            "C Consuelo  Bernal Hdez",
            "C Consuelo Bernal",
            "C Consuelo Bernal  Hernandez",
            "C Consuelo Bernal De Hdez",
            "C Consuelo Bernal De Hernandez",
            "C Consuelo Bernal Hernandez",
            "C Coordillera De Los Andes",
            "C Copaiba",
            "C Coquena",
            "C Coral",
            "C Cord De Los Andes",
            "C Cordillera Caucaso",
            "C Cordillera De Los Andes",
            "C Cordillera Del Caucaso",
            "C Corindon",
            "C Coronel Cirilo Gonzalez",
            "C Francisca Graba",
            "C Coronel Daniel Delgado",
            "C Coronel Fco Aguirre",
            "C Coronel Francisco Aguirre",
            "C Coronel Primitivo Uro",
            "C Coronel Santiago  Mendoza",
            "C Coronel Santiago Mendez",
            "C Cosmopolitan",
            "C Costa De Mallorca",
            "C Costa De Marfil",
            "C Cotufa",
            "C Cptan Jose  Granados",
            "C Cptan Jose Rivera",
            "C Cptan Pedro Meza",
            "C Cptan Salome  Mora",
            "C Crancisco I Madero",
            "C Crisolito",
            "C Croacia",
            "C Croasia",
            "C Cuacia",
            "C Cuarta",
            "C Cuarta Sur",
            "C Cuarzo",
            "C Cuasia",
            "C Cuauhtemoc",
            "C Cuauhtlatoa",
            "C Cuchillo Parado",
            "C Cuetzalan",
            "C Cuicuilco",
            "C Cuiculco",
            "C Daniel Delgado",
            "C Daniel Garcia",
            "C Daniel Gonzalez",
            "C De Cartagena",
            "C De Constituyentes",
            "C De Golfo De Tehuantepec",
            "C De Jose Revilla",
            "C De La Paz",
            "C De La Toronja",
            "C De Las Cruces",
            "C De Las Flores",
            "C De Las Parcelas",
            "C De Los Aztecas",
            "C De Mojave",
            "C De Nogales",
            "C De Obsidiana",
            "C De Osa Mayor",
            "C De Vistas De Humedades",
            "C De Vistas Del Manantial",
            "C De Vistas Las Tazas",
            "C De Xochimilcas",
            "C Decima",
            "C Defensa Nacional",
            "C Defensa Popular",
            "C Del Barreal",
            "C Del Bosque",
            "C Del Bote",
            "C Del Carburador",
            "C Del Carton",
            "C Del Desierto",
            "C Del Granero",
            "C Del Granjero",
            "C Del Herradero",
            "C Del Hueso",
            "C Del Maestro",
            "C Del Nivel",
            "C Del Parque",
            "C Del Picacho",
            "C Del Rayo",
            "C Del Roble",
            "C Del Sol",
            "C Del Valle",
            "C Del Vidrio",
            "C Del Volante",
            "C Delfino Montes",
            "C Delicias",
            "C Des De Los Leones",
            "C Desiero De Los Leones",
            "C Desierto De Gila",
            "C Desierto De Los Leones",
            "C Desierto De Mojave",
            "C Desierto De Namibia",
            "C Desierto De Neguev",
            "C Desierto De Sonora",
            "C Desierto De Viscaino",
            "C Desierto De Vizcaino",
            "C Diagonal 4A",
            "C Diagonal Cuarta",
            "C Diamante",
            "C Diaz Bustamante",
            "C Diaz De Bistamante",
            "C Diaz De Bustamante",
            "C Diego Lucero",
            "C Dina Rico",
            "C Dinamarca",
            "C Dionea",
            "C Dionicio",
            "C Dionicio Gomez",
            "C Dionisio Gomez",
            "C Diosnisio Gomez",
            "C Division Del Norte",
            "C Doctor Arroyo",
            "C Doctor Pablo Gomez",
            "C Dolores De Revilla",
            "C Dolores Revilla",
            "C Dolores Romero",
            "C Dolores Romero De Revilla",
            "C Dorados De Chihuahua",
            "C Dracona",
            "C Drosofila",
            "C Durango",
            "C E Griensen",
            "C E Ledezma",
            "C E Pasten",
            "C E Perez",
            "C E R De Quezada",
            "C E Rey De Quezada",
            "C E Rosales",
            "C E Zapata",
            "C Eclipse",
            "C Eclipse Nte",
            "C Eclipse Oriente",
            "C Eclipse Ote",
            "C Eclipse Poniente",
            "C Eclipse Sur",
            "C Eduardo Carranza",
            "C Eduardo Ocaranza",
            "C Eduwiges Ery De Quezada",
            "C Eduwiges Rey De Quezada",
            "C Eduwiges Rey De Quezada Sur",
            "C Edwiges Rey",
            "C Edwiges Rey De Quezada",
            "C Egipto",
            "C Eje Vial Juan Gabriel",
            "C Ejeercito Rebelde",
            "C Ejer Los Rebeldes",
            "C Ejercito De Los Rebeldes",
            "C Ejercito Los Rebelde",
            "C Ejercito Los Rebeldes",
            "C Ejercito Rebelde",
            "C Ejido Del Vergel",
            "C Ejido Rancheria",
            "C Ejido Terrazas",
            "C El Barreal",
            "C El Berrendo",
            "C El Cid",
            "C El Granjero",
            "C El Jarudo",
            "C El Saucito",
            "C Electricistas",
            "C Elefante",
            "C Elias Torres",
            "C Eligio Pasten",
            "C Elisa Griensen",
            "C Emilia Galindo Tellez",
            "C Emilia Perez Payan",
            "C Emiliano Zapata",
            "C Emilio Molina",
            "C Enrique  Flores Magon",
            "C Enrique  Ledezma",
            "C Enrique Benitez",
            "C Enrique C Ledezma",
            "C Enrique Diaz Gonzales",
            "C Enrique Diaz Gonzalez",
            "C Enrique Dominguez Gonzalez",
            "C Enrique Flores Magon",
            "C Enrique Hdez  Campos",
            "C Enrique Hdez Campos",
            "C Enrique Hernandez Campos",
            "C Enrique Ledezma",
            "C Enrrique Terrazas",
            "C Eritrea",
            "C Ernesto  Che Guevara",
            "C Ernesto  Guevara",
            "C Ernesto Che Guevara",
            "C Ernesto Rios",
            "C Escarola",
            "C Escocia",
            "C Escorpion",
            "C Eslovaquia",
            "C Eslovenia",
            "C Espa??a",
            "C Espa??a",
            "C Espa??ola",
            "C Esparto",
            "C Esperanza",
            "C Esperanza  Rosales Padron",
            "C Esperanza Reyes",
            "C Esperanza Rosales",
            "C Esperanza Rosales Padron",
            "C Esperanza Sandoval",
            "C Esperanza Sandoval De Quintana",
            "C Esteban Perez",
            "C Esther Gomez",
            "C Estonia",
            "C Estragon",
            "C Estuco",
            "C Ethel Cuervo",
            "C Ethel Cuervo Huerta",
            "C Etiopia",
            "C Eucalipto",
            "C Eugenio  Benavides",
            "C Eugenio A Benavides",
            "C Eugenio Aguilar",
            "C Eugenio Aguirre",
            "C Eugenio Aguirre Benavides",
            "C Eugenio Benavides",
            "C F Angeles",
            "C F Berriozabal",
            "C F Castro",
            "C F Medina",
            "C Faisan",
            "C Farabundo Marti",
            "C Faraday",
            "C Fca Crabas",
            "C Fca Grabas",
            "C Fca Gravas",
            "C Fco  Villa",
            "C Fco I Madero",
            "C Fco Madero",
            "C Fco Moure",
            "C Fco Portillo",
            "C Fco Sarabia",
            "C Fco Villa",
            "C Federico Cordova",
            "C Feldespalto",
            "C Feldespato",
            "C Feliciano Dominguez",
            "C Felipe Angeles",
            "C Felipe Angles",
            "C Felipe Berriozabal",
            "C Felipe Medina",
            "C Felipe Orteaga",
            "C Felipe Ortega",
            "C Felipe Rico",
            "C Felix Merino",
            "C Feliz Merino",
            "C Fernando Pacheco Parra",
            "C Ferrocariles",
            "C Ferrocarril",
            "C Fidel Castro",
            "C Fiji",
            "C Filipinas",
            "C Filipinas Y Libano",
            "C Filodendro",
            "C Filomeno Casta??eda",
            "C Filomeno Casta??eda",
            "C Flamingo",
            "C 'Flamingo",
            "C Flores Magon",
            "C Francia",
            "C Franciia",
            "C Francisc0 P0Rtillo",
            "C Francisca Grabas",
            "C Francisca Gravas",
            "C Francisco  I  Madero",
            "C Francisco Aguirre",
            "C Francisco Baca Gallardo",
            "C Francisco I  Madero",
            "C Francisco I Madero",
            "C Francisco Imadero",
            "C Francisco Indalesio Madero",
            "C Francisco Moure",
            "C Francisco Murguia",
            "C Francisco Pimentel",
            "C Francisco Portillo",
            "C Francisco Sandoval",
            "C Francisco Sarabia",
            "C Francisco Villa",
            "C Fray Antonio De Benavides",
            "C Fresa",
            "C Fresnillo",
            "C Frijol",
            "C Fuerza Aerea",
            "C Gabino Barreda",
            "C Gabino Barrera",
            "C Gabino Zarate",
            "C Gabiotas",
            "C Galenita",
            "C Gama",
            "C Gambia",
            "C Garambullo",
            "C Garcicrespo",
            "C Gascu??a",
            "C Gascu??a",
            "C Gaspar De Sandoval",
            "C Gaspar De Sandoval Sur",
            "C Gaspar Sandoval",
            "C Gaspar Trujillo",
            "C Gaviota",
            "C Gaviotas",
            "C Geminis",
            "C Genaro Vazquez",
            "C General  Juan Jimenez  Mendez",
            "C General Adolfo Terrones Benitez",
            "C General Ageo Meneses",
            "C General Agustin De La Vega",
            "C General Alberto Carreera Torres",
            "C General Alberto Carrera Torres",
            "C General Anastacio Pantoja",
            "C General Antonio Medrano",
            "C General Baudelio Uribe",
            "C General Calixto Contreras",
            "C General Candelario Cervantes",
            "C General Carlos Almeida",
            "C General Daniel Delgado",
            "C General Delfino Montes",
            "C General Eligio Pasten",
            "C General Enrique Diaz Gonzales",
            "C General Enrique Diaz Gonzalez",
            "C General Ernesto Rios",
            "C General Eugenio Aguilar",
            "C General Eugenio Aguirre Benavides",
            "C General Federico Cordova",
            "C General Felipe Berriozabal",
            "C General Francisco Villa",
            "C General Gilberto Cortez",
            "C General Guillermo Chavez",
            "C General Jeronimo  Padilla",
            "C General Jeronimo Padilla",
            "C General Jesus Nava",
            "C General Joaquin Soto Mendoza",
            "C General Joaquin Soto Mensoza",
            "C General Jose  Carrillo",
            "C General Jose Carrillo",
            "C General Jose Ines Salazar",
            "C General Jose Maria Heredia",
            "C General Jose Rodriguez Carrillo",
            "C General Juan J Mendez",
            "C General Julian Perez",
            "C General Julio Hernandez Serrano",
            "C General Julio Miramontes",
            "C General Luis Gutierrez",
            "C General Luis Herrera Cano",
            "C General Luis Ibarra",
            "C General Luis Tercero",
            "C General Manuel Castro",
            "C General Manuel Marin Aveitia",
            "C General Mariano Salas",
            "C General Maximo Castillo",
            "C General Ponciano Arriaga",
            "C General Primitivo Uro",
            "C General Ricardo Pe??a",
            "C General Rosalio  Hernandez",
            "C General Rosalio Hernandez",
            "C General Salvador  Mercado",
            "C General Salvador Mercado",
            "C General Santos Ortiz",
            "C General Severiano Ceniceros",
            "C General Tomas Urbina",
            "C General Victor Manuel Corral",
            "C Genneral Vict0R Manuuel  Corall",
            "C Genoveva De La O",
            "C Genovevo De La O",
            "C Georgina",
            "C Gereral Luis Tercero",
            "C Geronimo Padilla",
            "C Ghana",
            "C Gilberto Cortez",
            "C Gilberto Limon",
            "C Giner Duran",
            "C Gmo Chavez"];

        foreach ($colonias as $name){
            $colony = new Colony();
            $colony->name = $name;
            $colony->save();
        }
    }
}
