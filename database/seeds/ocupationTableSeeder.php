<?php

use Illuminate\Database\Seeder;
use App\Ocupation;

class ocupationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ocupations = ['Ama De Casa',
            'Jubilado',
            'Trabajadora Domestica',
            'Trabajador Rama Restaurantera / Bares',
            'Trabajador Maquiladora',
            'Tecnico',
            'Obrero',
            'Agricultor/Campesino',
            'Albañileria',
            'Jardineria',
            'Ventas',
            'Taxista',
            'Tranportista',
            'Empleado De Servicios',
            'Secretaria',
            'Recepcionista',
            'Auxiliar Oficina',
            'Mensajeria',
            'Mantenimiento',
            'Personal De Limpieza / Afanador',
            'Arte/Cultura',
            'Trabajo Social',
            'Profesor Universitaria (Superior)',
            'Profesor Preparatoria/Bachillerato (Media Superior)',
            'Profesor Secundaria (Media)',
            'Profesor Primaria (Basica)',
            'Profesor  Jardin De Niños/Kinder',
            'Empresario Ramo Comercial',
            'Empresario Ramo Industrial',
            'Empresario Ramo Restaurantero',
            'Empresario Ramo Turistico',
            'Empresario De Servicios',
            'Empresario Ramo Agricola',
            'Area Legal (Abogacia)',
            'Economista',
            'Administracion De Empresas',
            'Contaduria',
            'Administracion Publica',
            'Comunicaciones / Medios',
            'Medicna General',
            'Medicina Especializa',
            'Oftalmologia',
            'Optometria',
            'Odontologia',
            'Medicina Veterinaria',
            'Enfermeria',
            'Rescatista',
            'Bombero',
            'Policia',
            'Agente De Transito',
            'Arquitectura',
            'Ingenieria Civil',
            'Informatica',
            'Desarrollo De Software',
            'Telecomunicaciones',
            'Electricidad',
            'Electronica',
            'Ingenieria Industrial',
            'Ingenieria Biomedica',
            'Mecatronica / Robotica',
            'Quimica',
            'Ecologia, Fuentes Renovables, Medio Ambiente',
            'Diseño Grafico',
            'Otro',
            'Biologia'];

        foreach ($ocupations as $o){
            $ocup = new Ocupation();
            $ocup->occupation_name = $o;
            $ocup->save();
        }
    }
}
