<?php

	class note {

		private string $setType;
		private database $db;
		private Parsedown $parser;

		private string $dbNoteId; // Used when updating the table incase the noteId has been changed after instantiation
		
		public bool $existed; // Can be used to see whether the given entity existed already at the time of instantiation

		// Main database attributes
		public $noteId;
		public $businessId;
		public $title;
		public $bodyMarkdown;
		public $bodyHtml;
		public $viewPrivacy;
		public $viewPass;
		public $editPrivacy;
		public $editPass;
		public $lastUpdate;
		public $dateTimeAdded;

		// Arrays to store linked data
		public $tags = array();

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set to defaults function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function setToDefaults() {
			// Default businessId to the currently selected business
			if (isset($_SESSION['ultiscape_businessId'])) {
				$this->businessId = $_SESSION['ultiscape_businessId'];
			} else {
				$this->businessId = '';
			}
			$this->title = '';
			$this->bodyMarkdown = '';
			$this->bodyHtml = '';
			$this->viewPrivacy = 'private';
			$this->viewPass = NULL;
			$this->editPrivacy = 'private';
			$this->editPass = '';
			// Default dateTimeAdded to now since it is likely going to be inserted at this time
			$currentDateTime = new DateTime();
			$this->lastUpdate = $currentDateTime->format('Y-m-d H:i:s');
			$this->dateTimeAdded = $currentDateTime->format('Y-m-d H:i:s');

			// Clear arrays
			$this->tags = array();
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Init variables
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		function __construct(string $noteId = '') {

			// Connect to the database
			require_once dirname(__FILE__)."/../database.php";
			$this->db = new database;

			//Initialize the parser for markdown
			require_once dirname(__FILE__)."/../parsedown/parsedown.php";
			$this->parser = new Parsedown;
			$this->parser->setSafeMode(true);

			// Fetch from database
			$fetch = $this->db->select('note', '*', "WHERE noteId ='".$this->db->sanitize($noteId)."'");

			// If noteId already exists then set the set method type to UPDATE and fetch the values for the note
			if ($fetch) {
				$this->noteId = $noteId;
				$this->businessId = $fetch[0]['businessId'];
				$this->title = $fetch[0]['title'];
				$this->bodyMarkdown = $fetch[0]['bodyMarkdown'];
				$this->bodyHtml = $fetch[0]['bodyHtml'];
				$this->viewPrivacy = $fetch[0]['viewPrivacy'];
				$this->viewPass = $fetch[0]['viewPass'];
				$this->editPrivacy = $fetch[0]['editPrivacy'];
				$this->editPass = $fetch[0]['editPass'];
				$this->lastUpdate = $fetch[0]['lastUpdate'];
				$this->dateTimeAdded = $fetch[0]['dateTimeAdded'];

				$this->setType = 'UPDATE';
				$this->existed = true;

			// If noteId does not exist then set the set method type to INSERT and inititialize default values
			} else {
				// Make a new noteId
				require_once dirname(__FILE__)."/tableUuid.php";
				$uuid = new tableUuid('note', 'noteId');
				$this->noteId = $uuid->generatedId;

				$this->setToDefaults();

				$this->setType = 'INSERT';
				$this->existed = false;
			}

			$this->dbNoteId = $this->noteId;
			
		}
		
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Set function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function set() {

			$attributes = array(
				'noteId' => $this->db->sanitize($this->dbNoteId),
				'businessId' => $this->db->sanitize($this->businessId),
				'title' => $this->db->sanitize($this->title),
				'bodyMarkdown' => $this->db->sanitize($this->bodyMarkdown),
				'bodyHtml' => $this->db->sanitize($this->bodyHtml),
				'viewPrivacy' => $this->db->sanitize($this->viewPrivacy),
				'viewPass' => $this->db->sanitize($this->viewPass),
				'editPrivacy' => $this->db->sanitize($this->editPrivacy),
				'editPass' => $this->db->sanitize($this->editPass),
				'lastUpdate' => $this->db->sanitize($this->lastUpdate),
				'dateTimeAdded' => $this->db->sanitize($this->dateTimeAdded)
			);

			if ($this->setType == 'UPDATE') {

				// Update the values in the database after sanitizing them
				if ($this->db->update('note', $attributes, "WHERE noteId = '".$this->db->sanitize($this->dbNoteId)."'", 1)) {
					return true;
				} elseif ($this->db->getLastError() === '') {
					return true;
				} else {
					return $this->db->getLastError();
				}

			} else {

				// Insert the values to the database after sanitizing them
				if ($this->db->insert('note', $attributes)) {
					// Set the setType to UPDATE since it is now in the database
					$this->setType = 'UPDATE';
					return true;
				} else {
					return $this->db->getLastError();
				}

			}

		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Linked data pull functions
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		// Converts the current bodyMarkdown field into html code
		// that displays the note for viewer-only mode
		// Use Parsedown library from gitHub: https://github.com/erusev/parsedown
		public function convertMarkdownToHtml($noHtmlChars = false) {
			if ($noHtmlChars) {
				return $this->parser->text(htmlspecialchars($this->bodyMarkdown));
			} else {
				return $this->parser->text($this->bodyMarkdown);
			}
			
		}

		// tags
		public function pullTags($params = '') {
			$this->tags = array();
			// Add space before params
			if ($params != '') {
				$params = " ".$params;
			}
			// If there are entries, push them to the array
			$fetch = $this->db->select('noteNoteTagBridge', 'noteTagId', "WHERE noteId = '$this->dbNoteId'".$params);
			if ($fetch) {
				foreach ($fetch as $row) {
					array_push($this->tags, $row['noteTagId']);
				}
				return true;
			} elseif ($this->db->getLastError() === '') {
					return true;
			} else {
				return $this->db->getLastError();
			}
		}

		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// Delete function
		// -------------------------------------------------------------------------------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------------------------------------------------------------------

		public function delete() {

			// Remove row from database
			if (!$this->db->delete('note', "WHERE noteId = '".$this->db->sanitize($this->dbNoteId)."'", 1)) {
				return $this->db->getLastError();
			}

			$this->setToDefaults();

			// Set setType to INSERT since there is no longer a row to update
			$this->setType = 'INSERT';

			// Set existed to false since it no longer exists
			$this->existed = false;

			return true;
		}
	}

?>
