<?php
/**
 * api base object
 */
 use RATWEB\DB;
 include 'db.php';
 include '../config.php';

 /**
  * base database API
  * requed:  'id' autoincrement unique field in record
  */ 
 class API {
		public $tableName = 'users';
		public $insertLog = false;
		public $updateLog = false;
		public $deleteLog = false;
		public $getLog = false;
		public $userInlog = getSession('logedUser',0); // 1 = algorithm
		public $db;
		
		function __construct() {
			$this->db = new Query($this->tableName);
		}
		
		/**
		 * get value from session
		 * @param string $name
		 * @param mixed $default - optional
		 * @return mixed
		 */ 
		public function getSession(string $name, $default = '') {
			$result = $default;
			if (isset($_SESSION[$name]) {
				$result = $_SESSION[$name];
			}
			return $result;
		}

		/**
		 * get value from GET or POST
		 * @param string $name
		 * @param mixed $default - optional
		 * @return mixed
		 */ 
		public function getRequest(string $name, $default = '') {
			$result = $default;
			if (isset($_GET[$name]) {
				$result = $_GET[$name];
			}
			if (isset($_POST[$name]) {
				$result = $_POST[$name];
			}
			return $result;
		}
				
		/**
		 * process before insert
		 * @param Record $record
		 * @return '' or errorMsg
		 */ 		
		protected function beforeInsert(Record &$record): string {
			return '';
		}
		
		/**
		 * process after insert
		 * @param Record $record
		 * @return '' or errorMsg
		 */ 		
		protected function afterInsert(Record &$record): string {
			return '';
		}
		
		/**
		 * process before update
		 * @param Record $oldRecord
		 * @param Record $record
		 * @return '' or errorMsg
		 */ 		
		protected function beforeUpdate(Record $oldRecord, Record &$record): string {
			return '';
		}
		
		/**
		 * process after update
		 * @param Record $oldRecord
		 * @param Record $record
		 * @return '' or errorMsg
		 */ 		
		protected function afterUpdate(Record $oldRecord, Record &$record): string {
			return '';
		}
		
		/**
		 * process before delete
		 * @param Record $oldRecord
		 * @param Record $record
		 * @return '' or errorMsg
		 */ 		
		protected function beforeDelete(Record &$record): string {
			return '';
		}
	 
	 
	    /**
	     * validator
	     * @param string $action 'insert'|'update'|'delete'|'get'
	     * @param Record $oldRecord 
	     * @param Record $record
	     */
		public function validator(string $action, 
		    Record $oldRecord, Record $record, integer $logedUser): string {
			$result = '';
			return $result;
		}
		
		/**
		 * insert new record into database, set $record->id
		 * @param Record $record
		 * @return '' or errorMsg
		 */ 
		public function insert(Record &$record): string {
			$result = '';
			$oldRecord new Record();
			$result = $this->validator('insert',$oldRecord, $record, getSession('logedUser',0);
			if ($result == '') {
				$this->beforeInsert($record);
				// insert into database, if error set $result
				$id = $this->db->insert($record);
				$result = $this->db->error;
				if ($result == '') {
				   $record->id = $id;
				   $result = $this->afterInsert($record);
				   if ($result == '') {
				      if ($this->insertLog) {
					     // insert into log, if error set $result
						 $dbLog = new Query($this->tableName.'_log');
						 $logRecord = new Record();
						 $logRecord->id = 0;
						 $logRecord->time = now();
						 $logRecord->user_id = $this->userInLog;
						 $logRecord->type = 'insert';
						 $logRecord->info = JSON_encode($record);
						 $dbLog->insert($logRecord);
						 $result = $dbLog->error;
				      }
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
		public function update(Record $record): string {
			$result = '';
			return $result;
		}
		
		/**
		 * delete record
		 * @param Record $record
		 * @return '' or errorMsg
		 */ 
		public function delete(Record $record): string {
			$result = '';
			return $result;
		}
		
		/**
		 * get one record by id
		 * @param integer $id
		 * @return Record  if not found: {}
		 */ 
		public function getById(integer $id): Record {
			$result = new Record();
			return $record;
		}
	 
 } 
?>
