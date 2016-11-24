<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "bienesinfo.php" ?>
<?php include_once "personalinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$bienes_list = NULL; // Initialize page object first

class cbienes_list extends cbienes {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{33D340AA-42AB-4291-828D-AC1DA9E53193}";

	// Table name
	var $TableName = 'bienes';

	// Page object name
	var $PageObjName = 'bienes_list';

	// Grid form hidden field names
	var $FormName = 'fbieneslist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (bienes)
		if (!isset($GLOBALS["bienes"]) || get_class($GLOBALS["bienes"]) == "cbienes") {
			$GLOBALS["bienes"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["bienes"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "bienesadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "bienesdelete.php";
		$this->MultiUpdateUrl = "bienesupdate.php";

		// Table object (personal)
		if (!isset($GLOBALS['personal'])) $GLOBALS['personal'] = new cpersonal();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'bienes', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (personal)
		if (!isset($UserTable)) {
			$UserTable = new cpersonal();
			$UserTableConn = Conn($UserTable->DBID);
		}

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fbieneslistsrch";

		// List actions
		$this->ListActions = new cListActions();
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
		$this->idbienes->SetVisibility();
		$this->idbienes->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->direccion->SetVisibility();
		$this->precio->SetVisibility();
		$this->superficie->SetVisibility();
		$this->fechainicio->SetVisibility();
		$this->fechaexpira->SetVisibility();
		$this->banios->SetVisibility();
		$this->dormitorios->SetVisibility();
		$this->idtipo->SetVisibility();
		$this->idtipoin->SetVisibility();
		$this->idper->SetVisibility();
		$this->imagen->SetVisibility();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $bienes;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($bienes);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Process filter list
			$this->ProcessFilterList();

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->idbienes->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->idbienes->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fbieneslistsrch");
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->idbienes->AdvancedSearch->ToJSON(), ","); // Field idbienes
		$sFilterList = ew_Concat($sFilterList, $this->direccion->AdvancedSearch->ToJSON(), ","); // Field direccion
		$sFilterList = ew_Concat($sFilterList, $this->precio->AdvancedSearch->ToJSON(), ","); // Field precio
		$sFilterList = ew_Concat($sFilterList, $this->superficie->AdvancedSearch->ToJSON(), ","); // Field superficie
		$sFilterList = ew_Concat($sFilterList, $this->fechainicio->AdvancedSearch->ToJSON(), ","); // Field fechainicio
		$sFilterList = ew_Concat($sFilterList, $this->fechaexpira->AdvancedSearch->ToJSON(), ","); // Field fechaexpira
		$sFilterList = ew_Concat($sFilterList, $this->banios->AdvancedSearch->ToJSON(), ","); // Field banios
		$sFilterList = ew_Concat($sFilterList, $this->dormitorios->AdvancedSearch->ToJSON(), ","); // Field dormitorios
		$sFilterList = ew_Concat($sFilterList, $this->informacion->AdvancedSearch->ToJSON(), ","); // Field informacion
		$sFilterList = ew_Concat($sFilterList, $this->idtipo->AdvancedSearch->ToJSON(), ","); // Field idtipo
		$sFilterList = ew_Concat($sFilterList, $this->idtipoin->AdvancedSearch->ToJSON(), ","); // Field idtipoin
		$sFilterList = ew_Concat($sFilterList, $this->idper->AdvancedSearch->ToJSON(), ","); // Field idper
		$sFilterList = ew_Concat($sFilterList, $this->imagen->AdvancedSearch->ToJSON(), ","); // Field imagen
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}
		$sFilterList = preg_replace('/,$/', "", $sFilterList);

		// Return filter list in json
		if ($sFilterList <> "")
			$sFilterList = "\"data\":{" . $sFilterList . "}";
		if ($sSavedFilterList <> "") {
			if ($sFilterList <> "")
				$sFilterList .= ",";
			$sFilterList .= "\"filters\":" . $sSavedFilterList;
		}
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Process filter list
	function ProcessFilterList() {
		global $UserProfile;
		if (@$_POST["cmd"] == "savefilters") {
			$filters = ew_StripSlashes(@$_POST["filters"]);
			$UserProfile->SetSearchFilters(CurrentUserName(), "fbieneslistsrch", $filters);
		} elseif (@$_POST["cmd"] == "resetfilter") {
			$this->RestoreFilterList();
		}
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(ew_StripSlashes(@$_POST["filter"]), TRUE);
		$this->Command = "search";

		// Field idbienes
		$this->idbienes->AdvancedSearch->SearchValue = @$filter["x_idbienes"];
		$this->idbienes->AdvancedSearch->SearchOperator = @$filter["z_idbienes"];
		$this->idbienes->AdvancedSearch->SearchCondition = @$filter["v_idbienes"];
		$this->idbienes->AdvancedSearch->SearchValue2 = @$filter["y_idbienes"];
		$this->idbienes->AdvancedSearch->SearchOperator2 = @$filter["w_idbienes"];
		$this->idbienes->AdvancedSearch->Save();

		// Field direccion
		$this->direccion->AdvancedSearch->SearchValue = @$filter["x_direccion"];
		$this->direccion->AdvancedSearch->SearchOperator = @$filter["z_direccion"];
		$this->direccion->AdvancedSearch->SearchCondition = @$filter["v_direccion"];
		$this->direccion->AdvancedSearch->SearchValue2 = @$filter["y_direccion"];
		$this->direccion->AdvancedSearch->SearchOperator2 = @$filter["w_direccion"];
		$this->direccion->AdvancedSearch->Save();

		// Field precio
		$this->precio->AdvancedSearch->SearchValue = @$filter["x_precio"];
		$this->precio->AdvancedSearch->SearchOperator = @$filter["z_precio"];
		$this->precio->AdvancedSearch->SearchCondition = @$filter["v_precio"];
		$this->precio->AdvancedSearch->SearchValue2 = @$filter["y_precio"];
		$this->precio->AdvancedSearch->SearchOperator2 = @$filter["w_precio"];
		$this->precio->AdvancedSearch->Save();

		// Field superficie
		$this->superficie->AdvancedSearch->SearchValue = @$filter["x_superficie"];
		$this->superficie->AdvancedSearch->SearchOperator = @$filter["z_superficie"];
		$this->superficie->AdvancedSearch->SearchCondition = @$filter["v_superficie"];
		$this->superficie->AdvancedSearch->SearchValue2 = @$filter["y_superficie"];
		$this->superficie->AdvancedSearch->SearchOperator2 = @$filter["w_superficie"];
		$this->superficie->AdvancedSearch->Save();

		// Field fechainicio
		$this->fechainicio->AdvancedSearch->SearchValue = @$filter["x_fechainicio"];
		$this->fechainicio->AdvancedSearch->SearchOperator = @$filter["z_fechainicio"];
		$this->fechainicio->AdvancedSearch->SearchCondition = @$filter["v_fechainicio"];
		$this->fechainicio->AdvancedSearch->SearchValue2 = @$filter["y_fechainicio"];
		$this->fechainicio->AdvancedSearch->SearchOperator2 = @$filter["w_fechainicio"];
		$this->fechainicio->AdvancedSearch->Save();

		// Field fechaexpira
		$this->fechaexpira->AdvancedSearch->SearchValue = @$filter["x_fechaexpira"];
		$this->fechaexpira->AdvancedSearch->SearchOperator = @$filter["z_fechaexpira"];
		$this->fechaexpira->AdvancedSearch->SearchCondition = @$filter["v_fechaexpira"];
		$this->fechaexpira->AdvancedSearch->SearchValue2 = @$filter["y_fechaexpira"];
		$this->fechaexpira->AdvancedSearch->SearchOperator2 = @$filter["w_fechaexpira"];
		$this->fechaexpira->AdvancedSearch->Save();

		// Field banios
		$this->banios->AdvancedSearch->SearchValue = @$filter["x_banios"];
		$this->banios->AdvancedSearch->SearchOperator = @$filter["z_banios"];
		$this->banios->AdvancedSearch->SearchCondition = @$filter["v_banios"];
		$this->banios->AdvancedSearch->SearchValue2 = @$filter["y_banios"];
		$this->banios->AdvancedSearch->SearchOperator2 = @$filter["w_banios"];
		$this->banios->AdvancedSearch->Save();

		// Field dormitorios
		$this->dormitorios->AdvancedSearch->SearchValue = @$filter["x_dormitorios"];
		$this->dormitorios->AdvancedSearch->SearchOperator = @$filter["z_dormitorios"];
		$this->dormitorios->AdvancedSearch->SearchCondition = @$filter["v_dormitorios"];
		$this->dormitorios->AdvancedSearch->SearchValue2 = @$filter["y_dormitorios"];
		$this->dormitorios->AdvancedSearch->SearchOperator2 = @$filter["w_dormitorios"];
		$this->dormitorios->AdvancedSearch->Save();

		// Field informacion
		$this->informacion->AdvancedSearch->SearchValue = @$filter["x_informacion"];
		$this->informacion->AdvancedSearch->SearchOperator = @$filter["z_informacion"];
		$this->informacion->AdvancedSearch->SearchCondition = @$filter["v_informacion"];
		$this->informacion->AdvancedSearch->SearchValue2 = @$filter["y_informacion"];
		$this->informacion->AdvancedSearch->SearchOperator2 = @$filter["w_informacion"];
		$this->informacion->AdvancedSearch->Save();

		// Field idtipo
		$this->idtipo->AdvancedSearch->SearchValue = @$filter["x_idtipo"];
		$this->idtipo->AdvancedSearch->SearchOperator = @$filter["z_idtipo"];
		$this->idtipo->AdvancedSearch->SearchCondition = @$filter["v_idtipo"];
		$this->idtipo->AdvancedSearch->SearchValue2 = @$filter["y_idtipo"];
		$this->idtipo->AdvancedSearch->SearchOperator2 = @$filter["w_idtipo"];
		$this->idtipo->AdvancedSearch->Save();

		// Field idtipoin
		$this->idtipoin->AdvancedSearch->SearchValue = @$filter["x_idtipoin"];
		$this->idtipoin->AdvancedSearch->SearchOperator = @$filter["z_idtipoin"];
		$this->idtipoin->AdvancedSearch->SearchCondition = @$filter["v_idtipoin"];
		$this->idtipoin->AdvancedSearch->SearchValue2 = @$filter["y_idtipoin"];
		$this->idtipoin->AdvancedSearch->SearchOperator2 = @$filter["w_idtipoin"];
		$this->idtipoin->AdvancedSearch->Save();

		// Field idper
		$this->idper->AdvancedSearch->SearchValue = @$filter["x_idper"];
		$this->idper->AdvancedSearch->SearchOperator = @$filter["z_idper"];
		$this->idper->AdvancedSearch->SearchCondition = @$filter["v_idper"];
		$this->idper->AdvancedSearch->SearchValue2 = @$filter["y_idper"];
		$this->idper->AdvancedSearch->SearchOperator2 = @$filter["w_idper"];
		$this->idper->AdvancedSearch->Save();

		// Field imagen
		$this->imagen->AdvancedSearch->SearchValue = @$filter["x_imagen"];
		$this->imagen->AdvancedSearch->SearchOperator = @$filter["z_imagen"];
		$this->imagen->AdvancedSearch->SearchCondition = @$filter["v_imagen"];
		$this->imagen->AdvancedSearch->SearchValue2 = @$filter["y_imagen"];
		$this->imagen->AdvancedSearch->SearchOperator2 = @$filter["w_imagen"];
		$this->imagen->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->direccion, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->precio, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->informacion, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->imagen, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $arKeywords, $type) {
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if (EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace(EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual && $Fld->FldVirtualSearch) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .=  "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				$ar = array();

				// Match quoted keywords (i.e.: "...")
				if (preg_match_all('/"([^"]*)"/i', $sSearch, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$p = strpos($sSearch, $match[0]);
						$str = substr($sSearch, 0, $p);
						$sSearch = substr($sSearch, $p + strlen($match[0]));
						if (strlen(trim($str)) > 0)
							$ar = array_merge($ar, explode(" ", trim($str)));
						$ar[] = $match[1]; // Save quoted keyword
					}
				}

				// Match individual keywords
				if (strlen(trim($sSearch)) > 0)
					$ar = array_merge($ar, explode(" ", trim($sSearch)));

				// Search keyword in any fields
				if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
					foreach ($ar as $sKeyword) {
						if ($sKeyword <> "") {
							if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
							$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
						}
					}
				} else {
					$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL(array($sSearch), $sSearchType);
			}
			if (!$Default) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->idbienes); // idbienes
			$this->UpdateSort($this->direccion); // direccion
			$this->UpdateSort($this->precio); // precio
			$this->UpdateSort($this->superficie); // superficie
			$this->UpdateSort($this->fechainicio); // fechainicio
			$this->UpdateSort($this->fechaexpira); // fechaexpira
			$this->UpdateSort($this->banios); // banios
			$this->UpdateSort($this->dormitorios); // dormitorios
			$this->UpdateSort($this->idtipo); // idtipo
			$this->UpdateSort($this->idtipoin); // idtipoin
			$this->UpdateSort($this->idper); // idper
			$this->UpdateSort($this->imagen); // imagen
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->idbienes->setSort("");
				$this->direccion->setSort("");
				$this->precio->setSort("");
				$this->superficie->setSort("");
				$this->fechainicio->setSort("");
				$this->fechaexpira->setSort("");
				$this->banios->setSort("");
				$this->dormitorios->setSort("");
				$this->idtipo->setSort("");
				$this->idtipoin->setSort("");
				$this->idper->setSort("");
				$this->imagen->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanView();
		$item->OnLeft = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = FALSE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanAdd();
		$item->OnLeft = FALSE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = FALSE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssStyle = "white-space: nowrap;";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = FALSE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		$viewcaption = ew_HtmlTitle($Language->Phrase("ViewLink"));
		if ($Security->CanView()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		$editcaption = ew_HtmlTitle($Language->Phrase("EditLink"));
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		$copycaption = ew_HtmlTitle($Language->Phrase("CopyLink"));
		if ($Security->CanAdd()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->CanDelete())
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt && $this->Export == "" && $this->CurrentAction == "") {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->idbienes->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("AddLink"));
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fbieneslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fbieneslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fbieneslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fbieneslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch()) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->idbienes->setDbValue($rs->fields('idbienes'));
		$this->direccion->setDbValue($rs->fields('direccion'));
		$this->precio->setDbValue($rs->fields('precio'));
		$this->superficie->setDbValue($rs->fields('superficie'));
		$this->fechainicio->setDbValue($rs->fields('fechainicio'));
		$this->fechaexpira->setDbValue($rs->fields('fechaexpira'));
		$this->banios->setDbValue($rs->fields('banios'));
		$this->dormitorios->setDbValue($rs->fields('dormitorios'));
		$this->informacion->setDbValue($rs->fields('informacion'));
		$this->idtipo->setDbValue($rs->fields('idtipo'));
		$this->idtipoin->setDbValue($rs->fields('idtipoin'));
		$this->idper->setDbValue($rs->fields('idper'));
		$this->imagen->Upload->DbValue = $rs->fields('imagen');
		$this->imagen->CurrentValue = $this->imagen->Upload->DbValue;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->idbienes->DbValue = $row['idbienes'];
		$this->direccion->DbValue = $row['direccion'];
		$this->precio->DbValue = $row['precio'];
		$this->superficie->DbValue = $row['superficie'];
		$this->fechainicio->DbValue = $row['fechainicio'];
		$this->fechaexpira->DbValue = $row['fechaexpira'];
		$this->banios->DbValue = $row['banios'];
		$this->dormitorios->DbValue = $row['dormitorios'];
		$this->informacion->DbValue = $row['informacion'];
		$this->idtipo->DbValue = $row['idtipo'];
		$this->idtipoin->DbValue = $row['idtipoin'];
		$this->idper->DbValue = $row['idper'];
		$this->imagen->Upload->DbValue = $row['imagen'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("idbienes")) <> "")
			$this->idbienes->CurrentValue = $this->getKey("idbienes"); // idbienes
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// idbienes
		// direccion
		// precio
		// superficie
		// fechainicio
		// fechaexpira
		// banios
		// dormitorios
		// informacion
		// idtipo
		// idtipoin
		// idper
		// imagen

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// idbienes
		$this->idbienes->ViewValue = $this->idbienes->CurrentValue;
		$this->idbienes->ViewCustomAttributes = "";

		// direccion
		$this->direccion->ViewValue = $this->direccion->CurrentValue;
		$this->direccion->ViewCustomAttributes = "";

		// precio
		$this->precio->ViewValue = $this->precio->CurrentValue;
		$this->precio->ViewCustomAttributes = "";

		// superficie
		$this->superficie->ViewValue = $this->superficie->CurrentValue;
		$this->superficie->ViewCustomAttributes = "";

		// fechainicio
		$this->fechainicio->ViewValue = $this->fechainicio->CurrentValue;
		$this->fechainicio->ViewValue = ew_FormatDateTime($this->fechainicio->ViewValue, 2);
		$this->fechainicio->ViewCustomAttributes = "";

		// fechaexpira
		$this->fechaexpira->ViewValue = $this->fechaexpira->CurrentValue;
		$this->fechaexpira->ViewValue = ew_FormatDateTime($this->fechaexpira->ViewValue, 0);
		$this->fechaexpira->ViewCustomAttributes = "";

		// banios
		$this->banios->ViewValue = $this->banios->CurrentValue;
		$this->banios->ViewCustomAttributes = "";

		// dormitorios
		$this->dormitorios->ViewValue = $this->dormitorios->CurrentValue;
		$this->dormitorios->ViewCustomAttributes = "";

		// idtipo
		$this->idtipo->ViewValue = $this->idtipo->CurrentValue;
		$this->idtipo->ViewCustomAttributes = "";

		// idtipoin
		$this->idtipoin->ViewValue = $this->idtipoin->CurrentValue;
		$this->idtipoin->ViewCustomAttributes = "";

		// idper
		$this->idper->ViewValue = $this->idper->CurrentValue;
		$this->idper->ViewCustomAttributes = "";

		// imagen
		if (!ew_Empty($this->imagen->Upload->DbValue)) {
			$this->imagen->ImageWidth = 150;
			$this->imagen->ImageHeight = 150;
			$this->imagen->ImageAlt = $this->imagen->FldAlt();
			$this->imagen->ViewValue = $this->imagen->Upload->DbValue;
		} else {
			$this->imagen->ViewValue = "";
		}
		$this->imagen->ViewCustomAttributes = "";

			// idbienes
			$this->idbienes->LinkCustomAttributes = "";
			$this->idbienes->HrefValue = "";
			$this->idbienes->TooltipValue = "";

			// direccion
			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";
			$this->direccion->TooltipValue = "";

			// precio
			$this->precio->LinkCustomAttributes = "";
			$this->precio->HrefValue = "";
			$this->precio->TooltipValue = "";

			// superficie
			$this->superficie->LinkCustomAttributes = "";
			$this->superficie->HrefValue = "";
			$this->superficie->TooltipValue = "";

			// fechainicio
			$this->fechainicio->LinkCustomAttributes = "";
			$this->fechainicio->HrefValue = "";
			$this->fechainicio->TooltipValue = "";

			// fechaexpira
			$this->fechaexpira->LinkCustomAttributes = "";
			$this->fechaexpira->HrefValue = "";
			$this->fechaexpira->TooltipValue = "";

			// banios
			$this->banios->LinkCustomAttributes = "";
			$this->banios->HrefValue = "";
			$this->banios->TooltipValue = "";

			// dormitorios
			$this->dormitorios->LinkCustomAttributes = "";
			$this->dormitorios->HrefValue = "";
			$this->dormitorios->TooltipValue = "";

			// idtipo
			$this->idtipo->LinkCustomAttributes = "";
			$this->idtipo->HrefValue = "";
			$this->idtipo->TooltipValue = "";

			// idtipoin
			$this->idtipoin->LinkCustomAttributes = "";
			$this->idtipoin->HrefValue = "";
			$this->idtipoin->TooltipValue = "";

			// idper
			$this->idper->LinkCustomAttributes = "";
			$this->idper->HrefValue = "";
			$this->idper->TooltipValue = "";

			// imagen
			$this->imagen->LinkCustomAttributes = "";
			if (!ew_Empty($this->imagen->Upload->DbValue)) {
				$this->imagen->HrefValue = ew_GetFileUploadUrl($this->imagen, $this->imagen->Upload->DbValue); // Add prefix/suffix
				$this->imagen->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->imagen->HrefValue = ew_ConvertFullUrl($this->imagen->HrefValue);
			} else {
				$this->imagen->HrefValue = "";
			}
			$this->imagen->HrefValue2 = $this->imagen->UploadPath . $this->imagen->Upload->DbValue;
			$this->imagen->TooltipValue = "";
			if ($this->imagen->UseColorbox) {
				if (ew_Empty($this->imagen->TooltipValue))
					$this->imagen->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->imagen->LinkAttrs["data-rel"] = "bienes_x" . $this->RowCnt . "_imagen";
				ew_AppendClass($this->imagen->LinkAttrs["class"], "ewLightbox");
			}
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

		//$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($bienes_list)) $bienes_list = new cbienes_list();

// Page init
$bienes_list->Page_Init();

// Page main
$bienes_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$bienes_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fbieneslist = new ew_Form("fbieneslist", "list");
fbieneslist.FormKeyCountName = '<?php echo $bienes_list->FormKeyCountName ?>';

// Form_CustomValidate event
fbieneslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fbieneslist.ValidateRequired = true;
<?php } else { ?>
fbieneslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var CurrentSearchForm = fbieneslistsrch = new ew_Form("fbieneslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($bienes_list->TotalRecs > 0 && $bienes_list->ExportOptions->Visible()) { ?>
<?php $bienes_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($bienes_list->SearchOptions->Visible()) { ?>
<?php $bienes_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($bienes_list->FilterOptions->Visible()) { ?>
<?php $bienes_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $bienes_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($bienes_list->TotalRecs <= 0)
			$bienes_list->TotalRecs = $bienes->SelectRecordCount();
	} else {
		if (!$bienes_list->Recordset && ($bienes_list->Recordset = $bienes_list->LoadRecordset()))
			$bienes_list->TotalRecs = $bienes_list->Recordset->RecordCount();
	}
	$bienes_list->StartRec = 1;
	if ($bienes_list->DisplayRecs <= 0 || ($bienes->Export <> "" && $bienes->ExportAll)) // Display all records
		$bienes_list->DisplayRecs = $bienes_list->TotalRecs;
	if (!($bienes->Export <> "" && $bienes->ExportAll))
		$bienes_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$bienes_list->Recordset = $bienes_list->LoadRecordset($bienes_list->StartRec-1, $bienes_list->DisplayRecs);

	// Set no record found message
	if ($bienes->CurrentAction == "" && $bienes_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$bienes_list->setWarningMessage(ew_DeniedMsg());
		if ($bienes_list->SearchWhere == "0=101")
			$bienes_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$bienes_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$bienes_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($bienes->Export == "" && $bienes->CurrentAction == "") { ?>
<form name="fbieneslistsrch" id="fbieneslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($bienes_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fbieneslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="bienes">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($bienes_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($bienes_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $bienes_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($bienes_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($bienes_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($bienes_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($bienes_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $bienes_list->ShowPageHeader(); ?>
<?php
$bienes_list->ShowMessage();
?>
<?php if ($bienes_list->TotalRecs > 0 || $bienes->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid bienes">
<form name="fbieneslist" id="fbieneslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($bienes_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $bienes_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="bienes">
<div id="gmp_bienes" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($bienes_list->TotalRecs > 0) { ?>
<table id="tbl_bieneslist" class="table ewTable">
<?php echo $bienes->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$bienes_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$bienes_list->RenderListOptions();

// Render list options (header, left)
$bienes_list->ListOptions->Render("header", "left");
?>
<?php if ($bienes->idbienes->Visible) { // idbienes ?>
	<?php if ($bienes->SortUrl($bienes->idbienes) == "") { ?>
		<th data-name="idbienes"><div id="elh_bienes_idbienes" class="bienes_idbienes"><div class="ewTableHeaderCaption"><?php echo $bienes->idbienes->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idbienes"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bienes->SortUrl($bienes->idbienes) ?>',1);"><div id="elh_bienes_idbienes" class="bienes_idbienes">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bienes->idbienes->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($bienes->idbienes->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bienes->idbienes->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($bienes->direccion->Visible) { // direccion ?>
	<?php if ($bienes->SortUrl($bienes->direccion) == "") { ?>
		<th data-name="direccion"><div id="elh_bienes_direccion" class="bienes_direccion"><div class="ewTableHeaderCaption"><?php echo $bienes->direccion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="direccion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bienes->SortUrl($bienes->direccion) ?>',1);"><div id="elh_bienes_direccion" class="bienes_direccion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bienes->direccion->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($bienes->direccion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bienes->direccion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($bienes->precio->Visible) { // precio ?>
	<?php if ($bienes->SortUrl($bienes->precio) == "") { ?>
		<th data-name="precio"><div id="elh_bienes_precio" class="bienes_precio"><div class="ewTableHeaderCaption"><?php echo $bienes->precio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="precio"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bienes->SortUrl($bienes->precio) ?>',1);"><div id="elh_bienes_precio" class="bienes_precio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bienes->precio->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($bienes->precio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bienes->precio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($bienes->superficie->Visible) { // superficie ?>
	<?php if ($bienes->SortUrl($bienes->superficie) == "") { ?>
		<th data-name="superficie"><div id="elh_bienes_superficie" class="bienes_superficie"><div class="ewTableHeaderCaption"><?php echo $bienes->superficie->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="superficie"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bienes->SortUrl($bienes->superficie) ?>',1);"><div id="elh_bienes_superficie" class="bienes_superficie">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bienes->superficie->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($bienes->superficie->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bienes->superficie->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($bienes->fechainicio->Visible) { // fechainicio ?>
	<?php if ($bienes->SortUrl($bienes->fechainicio) == "") { ?>
		<th data-name="fechainicio"><div id="elh_bienes_fechainicio" class="bienes_fechainicio"><div class="ewTableHeaderCaption"><?php echo $bienes->fechainicio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fechainicio"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bienes->SortUrl($bienes->fechainicio) ?>',1);"><div id="elh_bienes_fechainicio" class="bienes_fechainicio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bienes->fechainicio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($bienes->fechainicio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bienes->fechainicio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($bienes->fechaexpira->Visible) { // fechaexpira ?>
	<?php if ($bienes->SortUrl($bienes->fechaexpira) == "") { ?>
		<th data-name="fechaexpira"><div id="elh_bienes_fechaexpira" class="bienes_fechaexpira"><div class="ewTableHeaderCaption"><?php echo $bienes->fechaexpira->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fechaexpira"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bienes->SortUrl($bienes->fechaexpira) ?>',1);"><div id="elh_bienes_fechaexpira" class="bienes_fechaexpira">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bienes->fechaexpira->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($bienes->fechaexpira->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bienes->fechaexpira->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($bienes->banios->Visible) { // banios ?>
	<?php if ($bienes->SortUrl($bienes->banios) == "") { ?>
		<th data-name="banios"><div id="elh_bienes_banios" class="bienes_banios"><div class="ewTableHeaderCaption"><?php echo $bienes->banios->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="banios"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bienes->SortUrl($bienes->banios) ?>',1);"><div id="elh_bienes_banios" class="bienes_banios">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bienes->banios->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($bienes->banios->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bienes->banios->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($bienes->dormitorios->Visible) { // dormitorios ?>
	<?php if ($bienes->SortUrl($bienes->dormitorios) == "") { ?>
		<th data-name="dormitorios"><div id="elh_bienes_dormitorios" class="bienes_dormitorios"><div class="ewTableHeaderCaption"><?php echo $bienes->dormitorios->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="dormitorios"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bienes->SortUrl($bienes->dormitorios) ?>',1);"><div id="elh_bienes_dormitorios" class="bienes_dormitorios">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bienes->dormitorios->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($bienes->dormitorios->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bienes->dormitorios->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($bienes->idtipo->Visible) { // idtipo ?>
	<?php if ($bienes->SortUrl($bienes->idtipo) == "") { ?>
		<th data-name="idtipo"><div id="elh_bienes_idtipo" class="bienes_idtipo"><div class="ewTableHeaderCaption"><?php echo $bienes->idtipo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idtipo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bienes->SortUrl($bienes->idtipo) ?>',1);"><div id="elh_bienes_idtipo" class="bienes_idtipo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bienes->idtipo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($bienes->idtipo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bienes->idtipo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($bienes->idtipoin->Visible) { // idtipoin ?>
	<?php if ($bienes->SortUrl($bienes->idtipoin) == "") { ?>
		<th data-name="idtipoin"><div id="elh_bienes_idtipoin" class="bienes_idtipoin"><div class="ewTableHeaderCaption"><?php echo $bienes->idtipoin->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idtipoin"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bienes->SortUrl($bienes->idtipoin) ?>',1);"><div id="elh_bienes_idtipoin" class="bienes_idtipoin">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bienes->idtipoin->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($bienes->idtipoin->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bienes->idtipoin->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($bienes->idper->Visible) { // idper ?>
	<?php if ($bienes->SortUrl($bienes->idper) == "") { ?>
		<th data-name="idper"><div id="elh_bienes_idper" class="bienes_idper"><div class="ewTableHeaderCaption"><?php echo $bienes->idper->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idper"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bienes->SortUrl($bienes->idper) ?>',1);"><div id="elh_bienes_idper" class="bienes_idper">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bienes->idper->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($bienes->idper->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bienes->idper->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($bienes->imagen->Visible) { // imagen ?>
	<?php if ($bienes->SortUrl($bienes->imagen) == "") { ?>
		<th data-name="imagen"><div id="elh_bienes_imagen" class="bienes_imagen"><div class="ewTableHeaderCaption"><?php echo $bienes->imagen->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="imagen"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bienes->SortUrl($bienes->imagen) ?>',1);"><div id="elh_bienes_imagen" class="bienes_imagen">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bienes->imagen->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($bienes->imagen->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bienes->imagen->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$bienes_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($bienes->ExportAll && $bienes->Export <> "") {
	$bienes_list->StopRec = $bienes_list->TotalRecs;
} else {

	// Set the last record to display
	if ($bienes_list->TotalRecs > $bienes_list->StartRec + $bienes_list->DisplayRecs - 1)
		$bienes_list->StopRec = $bienes_list->StartRec + $bienes_list->DisplayRecs - 1;
	else
		$bienes_list->StopRec = $bienes_list->TotalRecs;
}
$bienes_list->RecCnt = $bienes_list->StartRec - 1;
if ($bienes_list->Recordset && !$bienes_list->Recordset->EOF) {
	$bienes_list->Recordset->MoveFirst();
	$bSelectLimit = $bienes_list->UseSelectLimit;
	if (!$bSelectLimit && $bienes_list->StartRec > 1)
		$bienes_list->Recordset->Move($bienes_list->StartRec - 1);
} elseif (!$bienes->AllowAddDeleteRow && $bienes_list->StopRec == 0) {
	$bienes_list->StopRec = $bienes->GridAddRowCount;
}

// Initialize aggregate
$bienes->RowType = EW_ROWTYPE_AGGREGATEINIT;
$bienes->ResetAttrs();
$bienes_list->RenderRow();
while ($bienes_list->RecCnt < $bienes_list->StopRec) {
	$bienes_list->RecCnt++;
	if (intval($bienes_list->RecCnt) >= intval($bienes_list->StartRec)) {
		$bienes_list->RowCnt++;

		// Set up key count
		$bienes_list->KeyCount = $bienes_list->RowIndex;

		// Init row class and style
		$bienes->ResetAttrs();
		$bienes->CssClass = "";
		if ($bienes->CurrentAction == "gridadd") {
		} else {
			$bienes_list->LoadRowValues($bienes_list->Recordset); // Load row values
		}
		$bienes->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$bienes->RowAttrs = array_merge($bienes->RowAttrs, array('data-rowindex'=>$bienes_list->RowCnt, 'id'=>'r' . $bienes_list->RowCnt . '_bienes', 'data-rowtype'=>$bienes->RowType));

		// Render row
		$bienes_list->RenderRow();

		// Render list options
		$bienes_list->RenderListOptions();
?>
	<tr<?php echo $bienes->RowAttributes() ?>>
<?php

// Render list options (body, left)
$bienes_list->ListOptions->Render("body", "left", $bienes_list->RowCnt);
?>
	<?php if ($bienes->idbienes->Visible) { // idbienes ?>
		<td data-name="idbienes"<?php echo $bienes->idbienes->CellAttributes() ?>>
<span id="el<?php echo $bienes_list->RowCnt ?>_bienes_idbienes" class="bienes_idbienes">
<span<?php echo $bienes->idbienes->ViewAttributes() ?>>
<?php echo $bienes->idbienes->ListViewValue() ?></span>
</span>
<a id="<?php echo $bienes_list->PageObjName . "_row_" . $bienes_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bienes->direccion->Visible) { // direccion ?>
		<td data-name="direccion"<?php echo $bienes->direccion->CellAttributes() ?>>
<span id="el<?php echo $bienes_list->RowCnt ?>_bienes_direccion" class="bienes_direccion">
<span<?php echo $bienes->direccion->ViewAttributes() ?>>
<?php echo $bienes->direccion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($bienes->precio->Visible) { // precio ?>
		<td data-name="precio"<?php echo $bienes->precio->CellAttributes() ?>>
<span id="el<?php echo $bienes_list->RowCnt ?>_bienes_precio" class="bienes_precio">
<span<?php echo $bienes->precio->ViewAttributes() ?>>
<?php echo $bienes->precio->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($bienes->superficie->Visible) { // superficie ?>
		<td data-name="superficie"<?php echo $bienes->superficie->CellAttributes() ?>>
<span id="el<?php echo $bienes_list->RowCnt ?>_bienes_superficie" class="bienes_superficie">
<span<?php echo $bienes->superficie->ViewAttributes() ?>>
<?php echo $bienes->superficie->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($bienes->fechainicio->Visible) { // fechainicio ?>
		<td data-name="fechainicio"<?php echo $bienes->fechainicio->CellAttributes() ?>>
<span id="el<?php echo $bienes_list->RowCnt ?>_bienes_fechainicio" class="bienes_fechainicio">
<span<?php echo $bienes->fechainicio->ViewAttributes() ?>>
<?php echo $bienes->fechainicio->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($bienes->fechaexpira->Visible) { // fechaexpira ?>
		<td data-name="fechaexpira"<?php echo $bienes->fechaexpira->CellAttributes() ?>>
<span id="el<?php echo $bienes_list->RowCnt ?>_bienes_fechaexpira" class="bienes_fechaexpira">
<span<?php echo $bienes->fechaexpira->ViewAttributes() ?>>
<?php echo $bienes->fechaexpira->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($bienes->banios->Visible) { // banios ?>
		<td data-name="banios"<?php echo $bienes->banios->CellAttributes() ?>>
<span id="el<?php echo $bienes_list->RowCnt ?>_bienes_banios" class="bienes_banios">
<span<?php echo $bienes->banios->ViewAttributes() ?>>
<?php echo $bienes->banios->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($bienes->dormitorios->Visible) { // dormitorios ?>
		<td data-name="dormitorios"<?php echo $bienes->dormitorios->CellAttributes() ?>>
<span id="el<?php echo $bienes_list->RowCnt ?>_bienes_dormitorios" class="bienes_dormitorios">
<span<?php echo $bienes->dormitorios->ViewAttributes() ?>>
<?php echo $bienes->dormitorios->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($bienes->idtipo->Visible) { // idtipo ?>
		<td data-name="idtipo"<?php echo $bienes->idtipo->CellAttributes() ?>>
<span id="el<?php echo $bienes_list->RowCnt ?>_bienes_idtipo" class="bienes_idtipo">
<span<?php echo $bienes->idtipo->ViewAttributes() ?>>
<?php echo $bienes->idtipo->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($bienes->idtipoin->Visible) { // idtipoin ?>
		<td data-name="idtipoin"<?php echo $bienes->idtipoin->CellAttributes() ?>>
<span id="el<?php echo $bienes_list->RowCnt ?>_bienes_idtipoin" class="bienes_idtipoin">
<span<?php echo $bienes->idtipoin->ViewAttributes() ?>>
<?php echo $bienes->idtipoin->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($bienes->idper->Visible) { // idper ?>
		<td data-name="idper"<?php echo $bienes->idper->CellAttributes() ?>>
<span id="el<?php echo $bienes_list->RowCnt ?>_bienes_idper" class="bienes_idper">
<span<?php echo $bienes->idper->ViewAttributes() ?>>
<?php echo $bienes->idper->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($bienes->imagen->Visible) { // imagen ?>
		<td data-name="imagen"<?php echo $bienes->imagen->CellAttributes() ?>>
<span id="el<?php echo $bienes_list->RowCnt ?>_bienes_imagen" class="bienes_imagen">
<span>
<?php echo ew_GetFileViewTag($bienes->imagen, $bienes->imagen->ListViewValue()) ?>
</span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$bienes_list->ListOptions->Render("body", "right", $bienes_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($bienes->CurrentAction <> "gridadd")
		$bienes_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($bienes->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($bienes_list->Recordset)
	$bienes_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($bienes->CurrentAction <> "gridadd" && $bienes->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($bienes_list->Pager)) $bienes_list->Pager = new cPrevNextPager($bienes_list->StartRec, $bienes_list->DisplayRecs, $bienes_list->TotalRecs) ?>
<?php if ($bienes_list->Pager->RecordCount > 0 && $bienes_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($bienes_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $bienes_list->PageUrl() ?>start=<?php echo $bienes_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($bienes_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $bienes_list->PageUrl() ?>start=<?php echo $bienes_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $bienes_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($bienes_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $bienes_list->PageUrl() ?>start=<?php echo $bienes_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($bienes_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $bienes_list->PageUrl() ?>start=<?php echo $bienes_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $bienes_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $bienes_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $bienes_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $bienes_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($bienes_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($bienes_list->TotalRecs == 0 && $bienes->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($bienes_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fbieneslistsrch.FilterList = <?php echo $bienes_list->GetFilterList() ?>;
fbieneslistsrch.Init();
fbieneslist.Init();
</script>
<?php
$bienes_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$bienes_list->Page_Terminate();
?>
