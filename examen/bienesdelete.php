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

$bienes_delete = NULL; // Initialize page object first

class cbienes_delete extends cbienes {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{33D340AA-42AB-4291-828D-AC1DA9E53193}";

	// Table name
	var $TableName = 'bienes';

	// Page object name
	var $PageObjName = 'bienes_delete';

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

		// Table object (personal)
		if (!isset($GLOBALS['personal'])) $GLOBALS['personal'] = new cpersonal();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("bieneslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
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

		// Create Token
		$this->CreateToken();
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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("bieneslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in bienes class, bienesinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("bieneslist.php"); // Return to list
			}
		}
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

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
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
				$this->imagen->LinkAttrs["data-rel"] = "bienes_x_imagen";
				ew_AppendClass($this->imagen->LinkAttrs["class"], "ewLightbox");
			}
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['idbienes'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("bieneslist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($bienes_delete)) $bienes_delete = new cbienes_delete();

// Page init
$bienes_delete->Page_Init();

// Page main
$bienes_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$bienes_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fbienesdelete = new ew_Form("fbienesdelete", "delete");

// Form_CustomValidate event
fbienesdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fbienesdelete.ValidateRequired = true;
<?php } else { ?>
fbienesdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $bienes_delete->ShowPageHeader(); ?>
<?php
$bienes_delete->ShowMessage();
?>
<form name="fbienesdelete" id="fbienesdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($bienes_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $bienes_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="bienes">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($bienes_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $bienes->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($bienes->idbienes->Visible) { // idbienes ?>
		<th><span id="elh_bienes_idbienes" class="bienes_idbienes"><?php echo $bienes->idbienes->FldCaption() ?></span></th>
<?php } ?>
<?php if ($bienes->direccion->Visible) { // direccion ?>
		<th><span id="elh_bienes_direccion" class="bienes_direccion"><?php echo $bienes->direccion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($bienes->precio->Visible) { // precio ?>
		<th><span id="elh_bienes_precio" class="bienes_precio"><?php echo $bienes->precio->FldCaption() ?></span></th>
<?php } ?>
<?php if ($bienes->superficie->Visible) { // superficie ?>
		<th><span id="elh_bienes_superficie" class="bienes_superficie"><?php echo $bienes->superficie->FldCaption() ?></span></th>
<?php } ?>
<?php if ($bienes->fechainicio->Visible) { // fechainicio ?>
		<th><span id="elh_bienes_fechainicio" class="bienes_fechainicio"><?php echo $bienes->fechainicio->FldCaption() ?></span></th>
<?php } ?>
<?php if ($bienes->fechaexpira->Visible) { // fechaexpira ?>
		<th><span id="elh_bienes_fechaexpira" class="bienes_fechaexpira"><?php echo $bienes->fechaexpira->FldCaption() ?></span></th>
<?php } ?>
<?php if ($bienes->banios->Visible) { // banios ?>
		<th><span id="elh_bienes_banios" class="bienes_banios"><?php echo $bienes->banios->FldCaption() ?></span></th>
<?php } ?>
<?php if ($bienes->dormitorios->Visible) { // dormitorios ?>
		<th><span id="elh_bienes_dormitorios" class="bienes_dormitorios"><?php echo $bienes->dormitorios->FldCaption() ?></span></th>
<?php } ?>
<?php if ($bienes->idtipo->Visible) { // idtipo ?>
		<th><span id="elh_bienes_idtipo" class="bienes_idtipo"><?php echo $bienes->idtipo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($bienes->idtipoin->Visible) { // idtipoin ?>
		<th><span id="elh_bienes_idtipoin" class="bienes_idtipoin"><?php echo $bienes->idtipoin->FldCaption() ?></span></th>
<?php } ?>
<?php if ($bienes->idper->Visible) { // idper ?>
		<th><span id="elh_bienes_idper" class="bienes_idper"><?php echo $bienes->idper->FldCaption() ?></span></th>
<?php } ?>
<?php if ($bienes->imagen->Visible) { // imagen ?>
		<th><span id="elh_bienes_imagen" class="bienes_imagen"><?php echo $bienes->imagen->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$bienes_delete->RecCnt = 0;
$i = 0;
while (!$bienes_delete->Recordset->EOF) {
	$bienes_delete->RecCnt++;
	$bienes_delete->RowCnt++;

	// Set row properties
	$bienes->ResetAttrs();
	$bienes->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$bienes_delete->LoadRowValues($bienes_delete->Recordset);

	// Render row
	$bienes_delete->RenderRow();
?>
	<tr<?php echo $bienes->RowAttributes() ?>>
<?php if ($bienes->idbienes->Visible) { // idbienes ?>
		<td<?php echo $bienes->idbienes->CellAttributes() ?>>
<span id="el<?php echo $bienes_delete->RowCnt ?>_bienes_idbienes" class="bienes_idbienes">
<span<?php echo $bienes->idbienes->ViewAttributes() ?>>
<?php echo $bienes->idbienes->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($bienes->direccion->Visible) { // direccion ?>
		<td<?php echo $bienes->direccion->CellAttributes() ?>>
<span id="el<?php echo $bienes_delete->RowCnt ?>_bienes_direccion" class="bienes_direccion">
<span<?php echo $bienes->direccion->ViewAttributes() ?>>
<?php echo $bienes->direccion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($bienes->precio->Visible) { // precio ?>
		<td<?php echo $bienes->precio->CellAttributes() ?>>
<span id="el<?php echo $bienes_delete->RowCnt ?>_bienes_precio" class="bienes_precio">
<span<?php echo $bienes->precio->ViewAttributes() ?>>
<?php echo $bienes->precio->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($bienes->superficie->Visible) { // superficie ?>
		<td<?php echo $bienes->superficie->CellAttributes() ?>>
<span id="el<?php echo $bienes_delete->RowCnt ?>_bienes_superficie" class="bienes_superficie">
<span<?php echo $bienes->superficie->ViewAttributes() ?>>
<?php echo $bienes->superficie->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($bienes->fechainicio->Visible) { // fechainicio ?>
		<td<?php echo $bienes->fechainicio->CellAttributes() ?>>
<span id="el<?php echo $bienes_delete->RowCnt ?>_bienes_fechainicio" class="bienes_fechainicio">
<span<?php echo $bienes->fechainicio->ViewAttributes() ?>>
<?php echo $bienes->fechainicio->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($bienes->fechaexpira->Visible) { // fechaexpira ?>
		<td<?php echo $bienes->fechaexpira->CellAttributes() ?>>
<span id="el<?php echo $bienes_delete->RowCnt ?>_bienes_fechaexpira" class="bienes_fechaexpira">
<span<?php echo $bienes->fechaexpira->ViewAttributes() ?>>
<?php echo $bienes->fechaexpira->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($bienes->banios->Visible) { // banios ?>
		<td<?php echo $bienes->banios->CellAttributes() ?>>
<span id="el<?php echo $bienes_delete->RowCnt ?>_bienes_banios" class="bienes_banios">
<span<?php echo $bienes->banios->ViewAttributes() ?>>
<?php echo $bienes->banios->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($bienes->dormitorios->Visible) { // dormitorios ?>
		<td<?php echo $bienes->dormitorios->CellAttributes() ?>>
<span id="el<?php echo $bienes_delete->RowCnt ?>_bienes_dormitorios" class="bienes_dormitorios">
<span<?php echo $bienes->dormitorios->ViewAttributes() ?>>
<?php echo $bienes->dormitorios->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($bienes->idtipo->Visible) { // idtipo ?>
		<td<?php echo $bienes->idtipo->CellAttributes() ?>>
<span id="el<?php echo $bienes_delete->RowCnt ?>_bienes_idtipo" class="bienes_idtipo">
<span<?php echo $bienes->idtipo->ViewAttributes() ?>>
<?php echo $bienes->idtipo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($bienes->idtipoin->Visible) { // idtipoin ?>
		<td<?php echo $bienes->idtipoin->CellAttributes() ?>>
<span id="el<?php echo $bienes_delete->RowCnt ?>_bienes_idtipoin" class="bienes_idtipoin">
<span<?php echo $bienes->idtipoin->ViewAttributes() ?>>
<?php echo $bienes->idtipoin->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($bienes->idper->Visible) { // idper ?>
		<td<?php echo $bienes->idper->CellAttributes() ?>>
<span id="el<?php echo $bienes_delete->RowCnt ?>_bienes_idper" class="bienes_idper">
<span<?php echo $bienes->idper->ViewAttributes() ?>>
<?php echo $bienes->idper->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($bienes->imagen->Visible) { // imagen ?>
		<td<?php echo $bienes->imagen->CellAttributes() ?>>
<span id="el<?php echo $bienes_delete->RowCnt ?>_bienes_imagen" class="bienes_imagen">
<span>
<?php echo ew_GetFileViewTag($bienes->imagen, $bienes->imagen->ListViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$bienes_delete->Recordset->MoveNext();
}
$bienes_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $bienes_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fbienesdelete.Init();
</script>
<?php
$bienes_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$bienes_delete->Page_Terminate();
?>
