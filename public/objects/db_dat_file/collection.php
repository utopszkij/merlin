<?php
/**
 * tárolás data/{collectionName}.dat fájlokban
 * one line = one record in JSON format
 */
include_once 'where.php';

class Document{

}

 /**
  * array to Document
  * @param array $recArray
  * @return Document
  */
 function Doc(array $recArray): Document {
    $result = new Document();
    foreach ($recArray as $fn => $fv) {
        $result->$fn = $fv;
    }
    return $result;
 }

 class Collection extends Where {
    public string $collectionName;  // kollekció név
    public string $error;
    protected int $offset;
    protected int $limit;
    // protected array $select;
    protected string $alias;
    protected $lines = [];
    public $havings = [];
    // inherited
    // protected array $extensions
    // addWhere($fieldName, $rel, $value)
    // addOrWhere($fieldName, $rel, $value)
    // $recs = doWhere($recs)
    
    protected $fileName = '';
    
    /**
     * @param string $source név
     * @param string $alias
     */
    function __construct(string $collectionName) {
        parent::__construct();
        $this->collectionName = $collectionName;
        $this->fileName = __DIR__.'/data/'.$this->collectionName.'.dat';
        $this->offset = 0;
        $this->limit = -1;
        $this->select = [];
        $this->alias = '';
        if (!file_exists($this->fileName)) {
            $this->lines = [];
        } else {
            $this->lines = file($this->fileName);
            for ($i = 0; $i<count($this->lines); $i++) {
                $this->lines[$i] = JSON_decode($this->lines[$i]);
            }
        }
    }

    /**
     * string kodolás magyar ABC szerinti indexeléshez
     * egyenlőre idő és gas kimélésből belenyugszunk, hogy az index szerinti keresés
     * nem nagyar ABC sorrendet hoz ki, a Query sortBy oldja ezt meg
     */
    function hun(string $a): string {
        /*
        $Hchr = array('á'=>'az', 'é'=>'ez', 'í'=>'iz', 'ó'=>'oz', 'ö'=>'ozz', 'ő'=>'ozz', 'ú'=>'uz', 'ü'=>'uzz', 'ű'=>'uzz', 'cs'=>'cz', 'zs'=>'zz', 
        'ccs'=>'czcz', 'ggy'=>'gzgz', 'lly'=>'lzlz', 'nny'=>'nznz', 'ssz'=>'szsz', 'tty'=>'tztz', 'zzs'=>'zzzz', 'Á'=>'az', 'É'=>'ez', 'Í'=>'iz', 
        'Ó'=>'oz', 'Ö'=>'ozz', 'Ő'=>'ozz', 'Ú'=>'uz', 'Ü'=>'uzz', 'Ű'=>'uzz', 'CS'=>'cz', 'ZZ'=>'zz', 'CCS'=>'czcz', 'GGY'=>'gzgz', 'LLY'=>'lzlz', 
        'NNY'=>'nznz', 'SSZ'=>'szsz', 'TTY'=>'tztz', 'ZZS'=>'zzzz');  
        $s = strtolower(strtr($a,$Hchr));
        */
        return $a;
    }  

    /**
     * Új document kiirása json file-ba
     * @param array $document
     * @return string Új ID
     */
    public function insert($document): int {
        $this->error = '';
        $document->id = count($this->lines)+1;
        $this->lines[] = $document;
        $result = count($this->lines);

        $fp = fopen($this->fileName,'w+');
        foreach ($this->lines as $line) {
            fwrite($fp,JSON_encode($line)."\n");
        }   
        fclose($fp);
        return $result;
    }

    /**
     * Document modosítása
     * @param int $id
     * @param array $document
     * @return bool
     */
    public function updateById(int $id, $document): bool {
        $id = $id - 1;
        $this->error = '';
        if ($id < count($this->lines)) {
            if (isset($this->lines[$id]->deleted)) {
                $result = false;
                $this->error = 'NOT FOUND';
            } else {
                $this->lines[$id] = $document;
                $fp = fopen($this->fileName,'w+');
                foreach ($this->lines as $line) {
                    fwrite($fp,JSON_encode($line)."\n");
                }   
                fclose($fp);
                $result = true;
            }
        } else {
            $result = false;
            $this->error = 'NOT FOUND';
        }
        return $result;
    }

    /**
     * Document (logikai) törlése
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool {
        $id = $id - 1;
        $this->error = '';
        $result = true;
        if ($id < count($this->lines)) {
                $lines[$id] = JSON_encode('{"deleted":true}');
                $fp = fopen($this->fileName,'w+');
                foreach ($this->lines as $line) {
                    fwrite($fp,JSON_encode($line)."\n");
                }   
                fclose($fp);
                $result = true;
        }
        return $result;
    }   
    
    public function drop() {
        $this->lines = [];
        if (file_exists($this->fileName)) {
            unlink($this->fileName);
        }    
    }

    /**
     * Document elérés id alapján
     * @param int $id
     * @return object $document , ha nincs ilyen akkor {}
     */
    public function getById(int $id) {
        $id = $id - 1;
        $this->error = '';
        $result = false;
        if ($id < count($this->lines)) {
            $rec = $this->lines[$id];
            if (isset($rec->deleted)) {
                $result = false;
                $this->error = 'NOT FOUND';
            } else {
                $result = $rec;
            }
        } else {
            $result = false;
            $this->error = 'NOT FOUND';
        }
    }

    /**
     * dokumentumok olvasása
     * @param string $indexFieldName 
     * @param string $minValue        min.value az indexen
     * @param string $maxValue        max.value az indexen lehet 'none' is
     * @param function(array $rec): bool filterFun
     * @return array [{fieldname:value,...},...]
     */
    public function getByFilter(string $indexFieldName,
        string $minValue,
        string $maxValue) {
        $minValue =  $this->hun($minValue);   
        $maxValue =  $this->hun($maxValue);   
        $result = [];
        foreach ($this->lines as $line) {
            if (isset($line->$indexFieldName)) {
                if (($line->$indexFieldName <= $maxValue) &
                    ($line->$indexFieldName >= $minValue)) {
                        if (!isset($line->deleted)) {
                            $result[] = $line;
                        }  
                }
            }
        }
        return $result;
    } 
    
    /**
     * document array -ból a select alapján document objectet alakít ki.
     * @param object $rec
     * @param string $prefix  a resultban az oszlopnevek elé kerül
     * @return Document
     */
    public function buildResultRow($rec, string $prefix='') {
        $result = new \stdClass();
        foreach ($rec as $fn => $fv) {
            $colName = $prefix.$fn;
            if (count($this->select) == 0) {
                if (($fn != '_orderBy') & ($fn != 'deleted')) {
                    $result->$colName = $fv;
                }    
            } else {
                if (isset($this->select[$fn])) {
                    $result->$colName = $fv;
                }
            }
        }
        return $result;
    }
  
    /**
     * Offset deiniálás
     * @param int $offset
     * @return Collection
     */
    public function offset(int $offset): Collection {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Limit deiniálás
     * @param int $limit
     * @return Table
     */
    public function limit(int $limit): Collection {
        $this->limit = $limit;
        return $this;
    }

    /**
     * és -el összekapcsolt where feltétel hozzáadása az aktuális csoporthoz
     * @param string $fieldName
     * @param string $rel '<'|'<=','=','<>','>','>='
     * @param $value  szám, string, bool konstans vagy '`fieldName' lehet
     */
    public function where($fieldName, $rel, $value): Collection {
        $this->addWhere($fieldName, $rel, $value);
        return $this;
    }

    /**
     * or-al kapcsolt where csoprt kezdése hozzáadása
     * @param string $fieldName
     * @param string $rel '<'|'<=','=','<>','>','>='
     * @param $value  szám, string, bool konstans vagy '`fieldName' lehet
     */
    public function orWhere($fieldName, $rel, $value): Collection {
        $this->addOrWhere($fieldName, $rel, $value);
        return $this;
    }

    /**
     * select definiálása
     * a [funName, fieldName, alias] forma csak Query -ben, gropBy -al használhatóa 
     * funName: COUNT | SUM | MIN | MAX | AVG 
     * @param array $fieldnames ['fieldName'...., [funname,'fieldname','alias'],....]
     */
    public function select(array $fieldNames): Collection {
        $this->select = [];
        foreach ($fieldNames as $fieldName) {
            if (is_array($fieldName)) {
                $this->select[fieldname[1]] = $fieldName[2];
            } else {
                $this->select[$fieldName] = '';
            }
        }
        return $this;
    }

    /**
     * dokumentumok olvasása a bállított were, select, orderBy, offset, limit paraméterek alapján
     * @param string $prefix az outputban a mező nevek elé kerül
     * @return array [Document, Document,...]
     */
    public function all(string $prefix = '') {
        if (($prefix == '') & ($this->alias != '')) {
            $prefix = $this->alias.'.';
        }
        // használandó index és min, max megállapítása a where alapján
        $keyName = 'id';
        $minValue = '';
        $maxValue = 'none';
        if (count($this->expression) == 1) {
            if (is_array($this->expression[0])) {
                if (isset($this->expression[0][0])) {
                    $condition = $this->expression[0][0];
                    $keyName = $condition[0];
                    if (($condition[1] == '<') | ($condition[1] == '<=') | ($condition[1] == '=')) {
                        $maxValue = $condition[2];
                    }
                    if (($condition[1] == '>') | ($condition[1] == '>=') | ($condition[1] == '=')) {
                        $minValue = $condition[2];
                    }
                }    
                if (isset($this->expression[0][1])) {
                    $condition = $this->expression[0][1];
                    if ($condition[0] == $keyName) {
                        if (($condition[1] == '<') | ($condition[1] == '<=') | ($condition[1] == '=')) {
                            $maxValue = $condition[2];
                        }
                        if (($condition[1] == '>') | ($condition[1] == '>=') | ($condition[1] == '=')) {
                            $minValue = $condition[2];
                        }
                    }
                }
            } 
        }
        // dokumentum lekérés a where -el filterezve
        $recs = $this->getByFilter($keyName, $minValue, $maxValue);
        // result kialakítása
        $result = [];
        $count = 0;
        for ($i=0; $i < count($recs); $i++) {
            if (($i >= $this->offset) & 
                (($count <= $this->limit) | ($this->limit < 0))) {
                $result[] = $this->buildResultRow($recs[$i],$prefix);    
                $count++;
            }
        }
        $this->expression = [];
        return $result;
    }
 }

?>