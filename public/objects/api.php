<?php
/**
 * api base object
 */
use \RATWEB\DB;
include_once __DIR__.'/db_'.DBTYPE.'.php';

class LogRecord extends \RATWEB\DB\Record {
	public int $id;
	public string $time;
	public int $users_id;
	public int $parent_id;
	public string $parent_table;
	public string $event;
}

/**
  * base database API
  * requed:  'id' autoincrement unique field in record
  */ 
 class Api {
		public $tableName = 'users';
		public $insertLog = false;
		public $updateLog = false;
		public $deleteLog = false;
		public $getLog = false;
		public $userInlog = 1;  // allgorithm
		public $db;
		
		function __construct() {
			$this->db = new \RATWEB\DB\Query($this->tableName);
			// check session - exlude local test and activator,
			// set error dispay
			if (($_SERVER['REMOTE_ADDR'] != '127.0.0.1') && (!isset($_GET['code']))){
				if (($this->getSession('REMOTE_ADDR') != $_SERVER['REMOTE_ADDR']) |
					($this->getSession('HTTP_USER_AGENT') != $_SERVER['HTTP_USER_AGENT'])) {
					// session id is invalid!
					$result = new \stdClass();
					$result->errorMsg = 'SESSION_INVALID '.$_SERVER['REMOTE_ADDR'];
					$result->ok = false;
					echo JSON_encode($result);
					exit();
				}
				ini_set('display_errors', 0);
				ini_set('display_startup_errors', 0);
			} else {
				ini_set('display_errors', 1);
				ini_set('display_startup_errors', 1);
				error_reporting(E_ALL);
			}
		}

		/**
		 * build initialized result
		 */
		public static function initResult(): object {
			$result = new \stdClass();
			$result->ok = false;
			$result->errorMsg = '';
			return $result;
		}
		
		/**
		 * get value from session
		 * @param string $name
		 * @param mixed $default - optional
		 * @return mixed
		 */ 
		public static function getSession(string $name, $default = '') {
			$result = $default;
			if (isset($_SESSION[$name])) {
				$result = $_SESSION[$name];
			}
			return $result;
		}

		/**
		 * set sinto session
		 * @param string $name
		 * @param mixed value
		 */
		public function setSession(string $name, $value) {
			$_SESSION[$name] = $value;
		}

		/**
		 * get value from GET or POST
		 * @param string $name
		 * @param mixed $default - optional
		 * @return mixed
		 */ 
		public static function getRequest(string $name, $default = '') {
			$result = $default;
			if (isset($_GET[$name])) {
				$result = $_GET[$name];
			}
			if (isset($_POST[$name])) {
				$result = $_POST[$name];
			}
			return $result;
		}
				
		/**
		 * process before insert
		 * @param Record $record
		 * @return '' or errorMsg
		 */ 		
		protected function beforeInsert(\RATWEB\DB\Record &$record): string {
			return '';
		}
		
		/**
		 * process after insert
		 * @param Record $record
		 * @return '' or errorMsg
		 */ 		
		protected function afterInsert(\RATWEB\DB\Record &$record): string {
			return '';
		}
		
		/**
		 * process before update
		 * @param Record $oldRecord
		 * @param Record $record
		 * @return '' or errorMsg
		 */ 		
		protected function beforeUpdate(\RATWEB\DB\Record $oldRecord, \RATWEB\DB\Record &$record): string {
			return '';
		}
		
		/**
		 * process after update
		 * @param Record $oldRecord
		 * @param Record $record
		 * @return '' or errorMsg
		 */ 		
		protected function afterUpdate(\RATWEB\DB\Record $oldRecord, \RATWEB\DB\Record &$record): string {
			return '';
		}
		
		/**
		 * process before delete
		 * @param Record $oldRecord
		 * @param Record $record
		 * @return '' or errorMsg
		 */ 		
		protected function beforeDelete(\RATWEB\DB\Record &$record): string {
			return '';
		}
	 
	 
	    /**
	     * validator
	     * @param string $action 'insert'|'update'|'delete'|'get'
	     * @param Record $oldRecord 
	     * @param Record $record
		 * @return string ''|errormsg
	     */
		public function validator(string $action, 
			\RATWEB\DB\Record $oldRecord, \RATWEB\DB\Record $record, int $logedUser): string {
			$result = '';
			return $result;
		}

		/**
	     * loged user accessRight?
	     * @param string $action 'insert'|'update'|'delete'|'get'
	     * @param Record $oldRecord 
	     * @param Record $record
		 * @return ''|errormsg
	     */
		public function accessRight(string $action, 
			\RATWEB\DB\Record $oldRecord, \RATWEB\DB\Record $record, int $logedUser): string {
			$result = '';
			return $result;
		}

		
		/**
		 * insert new record into database, set $record->id
		 * @param Record $record
		 * @return '' or errorMsg
		 */ 
		public function insert(\RATWEB\DB\Record &$record): string {
			$result = '';
			$oldRecord = new \RATWEB\DB\Record();
			$result = $this->validator('insert',$oldRecord, $record, $this->getSession('logedUser',0));
			if ($result == '') {
				$result = $this->accessRight('insert',$oldRecord, $record, $this->getSession('logedUser',0));
			}	
			if ($result == '') {
				$this->beforeInsert($record);
				// insert into database, if error set $result
				$id = $this->db->insert($record);
				$result = $this->db->error;
				if ($result == '') {
				   $record->id = $id;
				   $result = $this->afterInsert($record);
				   if ($this->insertLog) {
					 // insert into log, if error set $result
					 $dbLog = new \RATWEB\DB\Query($this->tableName.'_log');
					 $logRecord = new LogRecord();
					 $logRecord->id = 0;
					 $logRecord->time = date('Y-m-d H:i:s');
					 $logRecord->users_id = 1;
					 $logRecord->parent_id = $record->id;
					 $logRecord->parent_table = $this->tableName;
					 $logRecord->event = 'insert '.$this->tableName.' '.JSON_encode($record);
					 $dbLog->insert($logRecord);
					 $result = $dbLog->error;
				   }
				}      
			}
			return $result;
		}
		
		/**
		 * update record
		 * @param Record $record
		 * @return '' or errorMsg
		 */ 
		public function update(\RATWEB\DB\Record $record): string {
			$result = '';
			$oldRecord = $this->db->getById($record->id);
			$result = $this->validator('update',$oldRecord, $record, getSession('logedUser',0));
			if ($result == '') {
				$result = $this->accessRight('update',$oldRecord, $record, getSession('logedUser',0));
			}	
			if ($result == '') {
				$this->beforeUpdate($record);
				// update into database, if error set $result
				$this->db->where('id','=',$record->id)
				           .update($record);
				$result = $this->db->error;
				if ($result == '') {
					if ($this->updateLog) {
						// insert into log, if error set $result
						$dbLog = new \RATWEB\DB\Query($this->tableName.'_log');
						$logRecord = new LogRecord();
						$logRecord->id = 0;
						$logRecord->time = date('Y-m-d H:i:s');
						$logRecord->users_id = $this->getSession('logedUser',1);
						$logRecord->parent_id = $record->id;
						$logRecord->parent_table = $this->tableName;
						$logRecord->info = 'update '.$this->tableName.' old:'.JSON_encode($oldrecord).
						   ' new:'.JSON_encode($record);
						$dbLog->insert($logRecord);
						$result = $dbLog->error;
					}
				  	$result = $this->afterUpdate($record);
				}      
			}
			return $result;
		}
		
		/**
		 * delete record
		 * @param int $id
		 *  @return '' or errorMsg
		 */ 
		public function delete(int $id) {
			$result = '';
			$oldRecord = $this->db->getById($id);
			$record = new \stdClass();
			$result = $this->validator('delete',$oldRecord, $record, getSession('logedUser',0));
			if ($result == '') {
				$result = $this->accessRight('delete',$oldRecord, $record, getSession('logedUser',0));
			}	
			if ($result == '') {
				$this->beforDelete($oldRecord);
				// delete from database, if error set $result
				$this->db->where('id','=',$record->id)
				           .delete();
				$result = $this->db->error;
				if ($result == '') {
				   if ($result == '') {
				      if ($this->deleteLog) {
					     // insert into log, if error set $result
						 $dbLog = new \RATWEB\DB\Query($this->tableName.'_log');
						 $logRecord = new LogRecord();
						 $logRecord->id = 0;
						 $logRecord->time = date('Y-m-d H:i:s');
						 $logRecord->users_id = $this->getSession('logedUser',0);
						 $logRecord->parent_id = $record->id;
						 $logRecord->parent_table = $this->tableName;
						 $logRecord->info = 'delete '.$this->tableName.' '.JSON_encode($oldRecord);
						 $dbLog->insert($logRecord);
						 $result = $dbLog->error;
				      }
				   }   
				}      
			}
			return $result;
		}
		
		/**
		 * get one record by id
		 * @param int $id
		 * @return Record  if not found: {}
		 */ 
		public function getById(int $id): \RATWEB\DB\Record {
			$result = new \RATWEB\DB\Record();
			return $record;
		}
	 
		protected function remove_accent($str) {
			// ékezetes betük helyettesítése az ékezet nélkülivel
			$a=array('À','Á','Â','Ã','Ä','Å','Æ','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ð','Ñ','Ò','Ó','Ô','Õ','Ö','Ø','Ù','Ú','Û','Ü','Ý','ß','à','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','ÿ','Ā','ā','Ă','ă','Ą','ą','Ć','ć','Ĉ','ĉ','Ċ','ċ','Č','č','Ď','ď','Đ','đ','Ē','ē','Ĕ','ĕ','Ė','ė','Ę','ę','Ě','ě','Ĝ','ĝ','Ğ','ğ','Ġ','ġ','Ģ','ģ','Ĥ','ĥ','Ħ','ħ','Ĩ','ĩ','Ī','ī','Ĭ','ĭ','Į','į','İ','ı','Ĳ','ĳ','Ĵ','ĵ','Ķ','ķ','Ĺ','ĺ','Ļ','ļ','Ľ','ľ','Ŀ','ŀ','Ł','ł','Ń','ń','Ņ','ņ','Ň','ň','ŉ','Ō','ō','Ŏ','ŏ','Ő','ő','Œ','œ','Ŕ','ŕ','Ŗ','ŗ','Ř','ř','Ś','ś','Ŝ','ŝ','Ş','ş','Š','š','Ţ','ţ','Ť','ť','Ŧ','ŧ','Ũ','ũ','Ū','ū','Ŭ','ŭ','Ů','ů','Ű','ű','Ų','ų','Ŵ','ŵ','Ŷ','ŷ','Ÿ','Ź','ź','Ż','ż','Ž','ž','ſ','ƒ','Ơ','ơ','Ư','ư','Ǎ','ǎ','Ǐ','ǐ','Ǒ','ǒ','Ǔ','ǔ','Ǖ','ǖ','Ǘ','ǘ','Ǚ','ǚ','Ǜ','ǜ','Ǻ','ǻ','Ǽ','ǽ','Ǿ','ǿ');
			$b=array('A','A','A','A','A','A','AE','C','E','E','E','E','I','I','I','I','D','N','O','O','O','O','O','O','U','U','U','U','Y','s','a','a','a','a','a','a','ae','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','o','u','u','u','u','y','y','A','a','A','a','A','a','C','c','C','c','C','c','C','c','D','d','D','d','E','e','E','e','E','e','E','e','E','e','G','g','G','g','G','g','G','g','H','h','H','h','I','i','I','i','I','i','I','i','I','i','IJ','ij','J','j','K','k','L','l','L','l','L','l','L','l','l','l','N','n','N','n','N','n','n','O','o','O','o','O','o','OE','oe','R','r','R','r','R','r','S','s','S','s','S','s','S','s','T','t','T','t','T','t','U','u','U','u','U','u','U','u','U','u','U','u','W','w','Y','y','Y','Z','z','Z','z','Z','z','s','f','O','o','U','u','A','a','I','i','O','o','U','u','U','u','U','u','U','u','U','u','A','a','AE','ae','O','o');
			return str_replace($a,$b,$str);
		}

		protected function clearFileName($s) {
			// string érvényes filenévvé alakítása
			return preg_replace("/[^a-z0-9._-]/", '', strtolower($this->remove_accent($s)));
		}
		

		/**
		 * upload all uploaded file if exists overwrite it.
		 * @param string $fileName_prefix
		 * @param string $uploadDir  realtive from document_root, not begin '/' and not end '/'
		 * @param arryay $extensions  example:['pmg','jpg','jpeg']
		 * @return {fileNames:[], errorMsg:''}
		 */
		public function uploadFiles(string $fileNamePrefix,
			string $uploadDir,
			array $extensions,
			): object {
			$result = new \stdClass();
			$result->fileNames = [];
			$result->errorMsg = '';	
			$_uploadDir = __DIR__.'/../'.$uploadDir;
			if (!is_dir($_uploadDir)) {
				mkdir($_uploadDir,0755);
			}
			$_uploadDir .= '/'.$fileNamePrefix;
			foreach ($_FILES as $fn => $fv) {
				$fileName = $this->clearFileName(basename($_FILES[$fn]['name']));
				$uploadFile = $_uploadDir . $fileName;

				$uploadFileExt = pathinfo($uploadFile,PATHINFO_EXTENSION);
				if (!in_array($uploadFileExt, $extensions)) {
					$result->errorMsg = 'upload_not_enabled';
					return $result;	
				}
				if (file_exists($uploadFile)) {
					unlink($uploadFile);
				}
				if (move_uploaded_file($_FILES[$fn]['tmp_name'], $uploadFile)) {
					$url = SITEURL.'/'.$uploadDir.'/'.$fileNamePrefix.$fileName;
					$result->fileNames[] = $url;
				} else {
					$result->errorMsg = 'error_in_upload';
					return $result;
				}
			}
			return $result;
 		} 
 }	
?>
