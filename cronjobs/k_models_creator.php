<?php

use util\config\Config;
use database\Connections;
use util\uploadfile\FileUploaderHelper;

include '../cronjobs/__init.php';

/** VARS */
 $DATE_ECHO = date("Y-m-d H:i:s") . ': ';
/** END VARS */

/** SECTION METHODS */
function getFieldsTable ( $nameTable ){
    $query = 'SHOW COLUMNS FROM ' . $nameTable;
    $stm = Connections::getConnection()->prepare($query);
    $stm->setFetchMode(\PDO::FETCH_ASSOC);
    $stm->execute();
    return $stm->fetchAll();
}
//MOSTTAR TODAS LAS TABLES DE LA BASE DE DATOS
function getNameTables(){
    $query = 'SHOW TABLES';
    $stm = Connections::getConnection()->prepare($query);
    $stm->setFetchMode(\PDO::FETCH_ASSOC);
    $stm->execute();
    return $stm->fetchAll();
}

function makeClassName( $string ){
    //array words
    $words = mb_split('[ ]', $string);

    foreach ($words as $iWord => $word){

        //array word
        $arrayWord = str_split($word);
        $partWord = '';
        foreach ($arrayWord as $iArrayWord => $aWord){
            if ( $iArrayWord == 0 ){
                $partWord .= mb_strtoupper($aWord);
            } else {
                $partWord .= $aWord;
            }
        }

        $words[$iWord] = $partWord;

    }

    return str_replace(' ', '', implode(' ', $words));
}

function showTablesUser(){
    $configuration = Config::get('default', 'database_config');
    $nameTable ='Tables_in_'.$configuration['dataBaseName'];
    $dataTables = getNameTables();
             echo '|_____TABLAS DE DATABASE___|'.PHP_EOL;
        foreach ($dataTables as $key => $item) {
            echo '......'.$item[$nameTable].PHP_EOL;
        };
}

function makeClass ( $nameTableClassModel, $nameSpace, $fields ){

    $classModel = str_ireplace('_', ' ', $nameTableClassModel);
    $classModel = makeClassName($classModel);

    $contentClass = '<?php ' . "\n\n".
        '/**' . "\n" .
        '*Powered by K-Models-Creator V1.1' . "\n" .
        '*Author:  Jose Luis'.  "\n" .
        '*Cooperation:  Freddy Chable'.  "\n" .
        '*Date: ' . date('d/m/Y') . "\n".
        '*Time: ' . date('H:i:s') . "\n" .
        '*/' . "\n\n" .
        'namespace ' . str_replace('/', '\\', $nameSpace) . ';' . "\n\n" .
        'class ' . $classModel . ' {' . "\n\n";

    //make attr
    foreach ($fields as $field){
        $contentClass .=
            "\n\t" . '/** db_column */' . "\n\t" .
            'private $' . $field['Field'] . ';';
    }

    //make getters and setters
    foreach ($fields as $field){
        $contentClass .=
            "\n\n\t" .
            '/**' . "\n\t" .
            '* @return mixed' . "\n\t" .
            '*/' . "\n\t" .
            'public function get' . makeClassName(str_replace('_', ' ', $field['Field'])) .
            '(){' . "\n\t\t" . 'return $this->' . $field['Field'] . ';' . "\n\t" .
            '}' . "\n\n\t" .
            '/**' . "\n\t" .
            '* @param mixed $' . $field['Field'] . "\n\t" .
            '* @return ' . $classModel . "\n\t" .
            '*/' . "\n\t" .
            'public function set' . makeClassName(str_replace('_', ' ', $field['Field'])) . '($' . $field['Field'] . '){' . "\n\t\t" .
            '$this->' . $field['Field'] . ' = $' . $field['Field'] . ';' . "\n\t" .
            '   return $this;'. "\n\t".
            '}' . "\n";
    }

    $contentClass .= '}';

    return array(
        'class_name' => $classModel,
        'content_class' => $contentClass
    );

}

function makeClassDAO( $nameClass, $nameSpace ){
    $nameClassDAO = $nameClass . 'DAO';
    //write head
    $contentClassDAO =
        '<?php' . "\n\n" .
        '/**' . "\n" .
        '*Powered by K-Models-Creator' . "\n" .
        '*Author: ' . $_SERVER['USERNAME'] . "\n" .
        '*Date: ' . date('d/m/Y') . "\n".
        '*Time: ' . date('H:i:s') . "\n" .
        '*/' . "\n\n" .
        'namespace ' . str_replace('/', '\\', $nameSpace) . ';' . "\n\n" .
        'use database\Connections;' . "\n" .
        'use database\SimpleDAO;' . "\n\n" .
        'class ' . $nameClassDAO . ' extends SimpleDAO {' . "\n\n";

    //write construct
    $contentClassDAO .=
        "\t" . '/**' . "\n\t"  .
        '*' . $nameClassDAO . ' construct' . "\n\t" .
        '*/' . "\n\t" .
        'public function __construct(){' . "\n\t\t" .
        'parent::__construct(new ' . $nameClass . '());' . "\n\t" .
        '}' . "\n\n";

    $contentClassDAO .= '}';

    return array(
        'class_name_dao' => $nameClassDAO,
        'content_class_dao' => $contentClassDAO
    );
}

function createModelAndDao($nameTableToModel, $directorySave, $directoryModulos){

    $fields = getFieldsTable( $nameTableToModel );
    $class = makeClass($nameTableToModel, $directorySave, $fields);
    $classDAO = makeClassDAO($class['class_name'], $directorySave);

    file_put_contents($directoryModulos . $class['class_name'] . '.php', $class['content_class']);
    file_put_contents($directoryModulos . $classDAO['class_name_dao'] . '.php', $classDAO['content_class_dao'] );
}

function createAllModelsAndDao( $directorySave, $directoryModulos){

    $configuration = Config::get('default', 'database_config');
    $nameTable ='Tables_in_'.$configuration['dataBaseName'];
    $dataTables = getNameTables();
    foreach ($dataTables as $item){
         createModelAndDao($item[$nameTable], $directorySave, $directoryModulos);
         echo 'Model y Dao ..'.$item[$nameTable].'...'.PHP_EOL;
    }
}
/** END SECTION METHODS */

function getConsole(){
    $handler = fopen("php://stdin", "r");
    $directorySave = fgets($handler);
    fclose($handler);

    return trim(preg_replace('/\s+/', ' ', $directorySave));
}

function createModelConfirm( $DATE_ECHO,$directorySave, $directoryModulos)
{

    echo 'Desea crear todos los MODELOS y DAOS de la base de Datos si( y ) / no( n ): ';

    switch (getConsole()) {
        case 'y':
            createAllModelsAndDao($directorySave, $directoryModulos);
            break;
        case 'n':
            echo 'Ingrese el nombre de la tabla: ';
            $nameTable = getConsole();
            createModelAndDao($nameTable, $directorySave, $directoryModulos);
            break;
        default:
            createModelConfirm( $DATE_ECHO,$directorySave, $directoryModulos);
            break;
    }
    echo $DATE_ECHO . 'Model y DAO creados con exito (/._.)/' . PHP_EOL;
    exit(0);
}

echo PHP_EOL . "
_+88__________________________... 
_+880_________________________... 
_++88_________________________... 
_++88_________________________... 
__+880________________________... 
__+888________________________... 
__++880______________________+8.. 
__++888_____+++88__________++88..
__++8888__+++8880++88____+++88... 
__+++8888+++8880++8888__++888_... 
___++888++8888+++888888++888__... 
___++88++8888++8888888++888___... 
___++++++888888888888888888___... 
____++++++88888888888888888___... 
____++++++++000888888888888___... 
_____+++++++000088888888888___... 
______+++++++00088888888888___... 
_______+++++++088888888888____... 
_______+++++++088888888888____... 
________+++++++8888888888_____... 
________++++++0088888888______... 
________+++++0008888888_______... 
________.....888888888________...
 _______________________________
|                               |
|..MODELS-CREATOR BY JOSE LUIS..| 
|_______________________________|" . PHP_EOL . PHP_EOL;

try {
    echo 'Ingrese direccion para salvar los modelos y DAOS de la base de datos (/raiz del proyecto/<carpeta destino>): ' ;
    $directorySave = getConsole();
    $directoryModulos = FileUploaderHelper::pathOfDirectoryOf($directorySave );

    if(!file_exists($directoryModulos)){
        throw  new Exception('la ruta de archivo no exite',0);
    }

    echo 'Desea ver todas las tablas de la base de datos si( y ) / no ( presione Enter ): ';

    if(strtolower( getConsole() ) == 'y') {
        showTablesUser();
    }

    echo 'RUTA DE ARCHIVOS -> '. $directoryModulos . PHP_EOL ;

    createModelConfirm ($DATE_ECHO, $directorySave, $directoryModulos);

} catch (\Exception $exception){
    echo 'Codigo de error: '.$exception->getCode(). PHP_EOL;
    echo 'Mensaje de error: '.$exception->getMessage() . PHP_EOL;
    echo 'Error en la linea ['.$exception->getLine().']' . PHP_EOL;
    echo 'Error en el archivo ['.$exception->getFile().']' . PHP_EOL;
    exit;
}
