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

$bienes_add = NULL; // Initialize page object first

class cbienes_add extends cbienes {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{33D340AA-42AB-4291-828D-AC1DA9E53193}";

	// Table name
	var $TableName = 'bienes';

	// Page object name
	var $PageObjName = 'bienes_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("bieneslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->direccion->SetVisibility();
		$this->precio->SetVisibility();
		$this->superficie->SetVisibility();
		$this->fechainicio->SetVisibility();
		$this->fechaexpira->SetVisibility();
		$this->banios->SetVisibility();
		$this->dormitorios->SetVisibility();
		$this->informacion->SetVisibility();
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

			// Handle modal response
			if ($this->IsModal) {
				$row = array();
				$row["url"] = $url;
				echo ew_ArrayToJson(array($row));
			} else {
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["idbienes"] != "") {
				$this->idbienes->setQueryStringValue($_GET["idbienes"]);
				$this->setKey("idbienes", $this->idbienes->CurrentValue); // Set up key
			} else {
				$this->setKey("idbienes", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else {
			if ($this->CurrentAction == "I") // Load default values for blank record
				$this->LoadDefaultValues();
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("bieneslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "bieneslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "bienesview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->imagen->Upload->Index = $objForm->Index;
		$this->imagen->Upload->UploadFile();
		$this->imagen->CurrentValue = $this->imagen->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->direccion->CurrentValue = NULL;
		$this->direccion->OldValue = $this->direccion->CurrentValue;
		$this->precio->CurrentValue = NULL;
		$this->precio->OldValue = $this->precio->CurrentValue;
		$this->superficie->CurrentValue = NULL;
		$this->superficie->OldValue = $this->superficie->CurrentValue;
		$this->fechainicio->CurrentValue = NULL;
		$this->fechainicio->OldValue = $this->fechainicio->CurrentValue;
		$this->fechaexpira->CurrentValue = NULL;
		$this->fechaexpira->OldValue = $this->fechaexpira->CurrentValue;
		$this->banios->CurrentValue = NULL;
		$this->banios->OldValue = $this->banios->CurrentValue;
		$this->dormitorios->CurrentValue = NULL;
		$this->dormitorios->OldValue = $this->dormitorios->CurrentValue;
		$this->informacion->CurrentValue = NULL;
		$this->informacion->OldValue = $this->informacion->CurrentValue;
		$this->idtipo->CurrentValue = NULL;
		$this->idtipo->OldValue = $this->idtipo->CurrentValue;
		$this->idtipoin->CurrentValue = NULL;
		$this->idtipoin->OldValue = $this->idtipoin->CurrentValue;
		$this->idper->CurrentValue = NULL;
		$this->idper->OldValue = $this->idper->CurrentValue;
		$this->imagen->Upload->DbValue = NULL;
		$this->imagen->OldValue = $this->imagen->Upload->DbValue;
		$this->imagen->CurrentValue = NULL; // Clear file related field
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->direccion->FldIsDetailKey) {
			$this->direccion->setFormValue($objForm->GetValue("x_direccion"));
		}
		if (!$this->precio->FldIsDetailKey) {
			$this->precio->setFormValue($objForm->GetValue("x_precio"));
		}
		if (!$this->superficie->FldIsDetailKey) {
			$this->superficie->setFormValue($objForm->GetValue("x_superficie"));
		}
		if (!$this->fechainicio->FldIsDetailKey) {
			$this->fechainicio->setFormValue($objForm->GetValue("x_fechainicio"));
			$this->fechainicio->CurrentValue = ew_UnFormatDateTime($this->fechainicio->CurrentValue, 2);
		}
		if (!$this->fechaexpira->FldIsDetailKey) {
			$this->fechaexpira->setFormValue($objForm->GetValue("x_fechaexpira"));
			$this->fechaexpira->CurrentValue = ew_UnFormatDateTime($this->fechaexpira->CurrentValue, 0);
		}
		if (!$this->banios->FldIsDetailKey) {
			$this->banios->setFormValue($objForm->GetValue("x_banios"));
		}
		if (!$this->dormitorios->FldIsDetailKey) {
			$this->dormitorios->setFormValue($objForm->GetValue("x_dormitorios"));
		}
		if (!$this->informacion->FldIsDetailKey) {
			$this->informacion->setFormValue($objForm->GetValue("x_informacion"));
		}
		if (!$this->idtipo->FldIsDetailKey) {
			$this->idtipo->setFormValue($objForm->GetValue("x_idtipo"));
		}
		if (!$this->idtipoin->FldIsDetailKey) {
			$this->idtipoin->setFormValue($objForm->GetValue("x_idtipoin"));
		}
		if (!$this->idper->FldIsDetailKey) {
			$this->idper->setFormValue($objForm->GetValue("x_idper"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->direccion->CurrentValue = $this->direccion->FormValue;
		$this->precio->CurrentValue = $this->precio->FormValue;
		$this->superficie->CurrentValue = $this->superficie->FormValue;
		$this->fechainicio->CurrentValue = $this->fechainicio->FormValue;
		$this->fechainicio->CurrentValue = ew_UnFormatDateTime($this->fechainicio->CurrentValue, 2);
		$this->fechaexpira->CurrentValue = $this->fechaexpira->FormValue;
		$this->fechaexpira->CurrentValue = ew_UnFormatDateTime($this->fechaexpira->CurrentValue, 0);
		$this->banios->CurrentValue = $this->banios->FormValue;
		$this->dormitorios->CurrentValue = $this->dormitorios->FormValue;
		$this->informacion->CurrentValue = $this->informacion->FormValue;
		$this->idtipo->CurrentValue = $this->idtipo->FormValue;
		$this->idtipoin->CurrentValue = $this->idtipoin->FormValue;
		$this->idper->CurrentValue = $this->idper->FormValue;
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

		// informacion
		$this->informacion->ViewValue = $this->informacion->CurrentValue;
		$this->informacion->ViewCustomAttributes = "";

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

			// informacion
			$this->informacion->LinkCustomAttributes = "";
			$this->informacion->HrefValue = "";
			$this->informacion->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// direccion
			$this->direccion->EditAttrs["class"] = "form-control";
			$this->direccion->EditCustomAttributes = "";
			$this->direccion->EditValue = ew_HtmlEncode($this->direccion->CurrentValue);
			$this->direccion->PlaceHolder = ew_RemoveHtml($this->direccion->FldCaption());

			// precio
			$this->precio->EditAttrs["class"] = "form-control";
			$this->precio->EditCustomAttributes = "";
			$this->precio->EditValue = ew_HtmlEncode($this->precio->CurrentValue);
			$this->precio->PlaceHolder = ew_RemoveHtml($this->precio->FldCaption());

			// superficie
			$this->superficie->EditAttrs["class"] = "form-control";
			$this->superficie->EditCustomAttributes = "";
			$this->superficie->EditValue = ew_HtmlEncode($this->superficie->CurrentValue);
			$this->superficie->PlaceHolder = ew_RemoveHtml($this->superficie->FldCaption());

			// fechainicio
			$this->fechainicio->EditAttrs["class"] = "form-control";
			$this->fechainicio->EditCustomAttributes = "";
			$this->fechainicio->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fechainicio->CurrentValue, 2));
			$this->fechainicio->PlaceHolder = ew_RemoveHtml($this->fechainicio->FldCaption());

			// fechaexpira
			$this->fechaexpira->EditAttrs["class"] = "form-control";
			$this->fechaexpira->EditCustomAttributes = "";
			$this->fechaexpira->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fechaexpira->CurrentValue, 8));
			$this->fechaexpira->PlaceHolder = ew_RemoveHtml($this->fechaexpira->FldCaption());

			// banios
			$this->banios->EditAttrs["class"] = "form-control";
			$this->banios->EditCustomAttributes = "";
			$this->banios->EditValue = ew_HtmlEncode($this->banios->CurrentValue);
			$this->banios->PlaceHolder = ew_RemoveHtml($this->banios->FldCaption());

			// dormitorios
			$this->dormitorios->EditAttrs["class"] = "form-control";
			$this->dormitorios->EditCustomAttributes = "";
			$this->dormitorios->EditValue = ew_HtmlEncode($this->dormitorios->CurrentValue);
			$this->dormitorios->PlaceHolder = ew_RemoveHtml($this->dormitorios->FldCaption());

			// informacion
			$this->informacion->EditAttrs["class"] = "form-control";
			$this->informacion->EditCustomAttributes = "";
			$this->informacion->EditValue = ew_HtmlEncode($this->informacion->CurrentValue);
			$this->informacion->PlaceHolder = ew_RemoveHtml($this->informacion->FldCaption());

			// idtipo
			$this->idtipo->EditAttrs["class"] = "form-control";
			$this->idtipo->EditCustomAttributes = "";
			$this->idtipo->EditValue = ew_HtmlEncode($this->idtipo->CurrentValue);
			$this->idtipo->PlaceHolder = ew_RemoveHtml($this->idtipo->FldCaption());

			// idtipoin
			$this->idtipoin->EditAttrs["class"] = "form-control";
			$this->idtipoin->EditCustomAttributes = "";
			$this->idtipoin->EditValue = ew_HtmlEncode($this->idtipoin->CurrentValue);
			$this->idtipoin->PlaceHolder = ew_RemoveHtml($this->idtipoin->FldCaption());

			// idper
			$this->idper->EditAttrs["class"] = "form-control";
			$this->idper->EditCustomAttributes = "";
			$this->idper->EditValue = ew_HtmlEncode($this->idper->CurrentValue);
			$this->idper->PlaceHolder = ew_RemoveHtml($this->idper->FldCaption());

			// imagen
			$this->imagen->EditAttrs["class"] = "form-control";
			$this->imagen->EditCustomAttributes = "";
			if (!ew_Empty($this->imagen->Upload->DbValue)) {
				$this->imagen->ImageWidth = 150;
				$this->imagen->ImageHeight = 150;
				$this->imagen->ImageAlt = $this->imagen->FldAlt();
				$this->imagen->EditValue = $this->imagen->Upload->DbValue;
			} else {
				$this->imagen->EditValue = "";
			}
			if (!ew_Empty($this->imagen->CurrentValue))
				$this->imagen->Upload->FileName = $this->imagen->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->imagen);

			// Add refer script
			// direccion

			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";

			// precio
			$this->precio->LinkCustomAttributes = "";
			$this->precio->HrefValue = "";

			// superficie
			$this->superficie->LinkCustomAttributes = "";
			$this->superficie->HrefValue = "";

			// fechainicio
			$this->fechainicio->LinkCustomAttributes = "";
			$this->fechainicio->HrefValue = "";

			// fechaexpira
			$this->fechaexpira->LinkCustomAttributes = "";
			$this->fechaexpira->HrefValue = "";

			// banios
			$this->banios->LinkCustomAttributes = "";
			$this->banios->HrefValue = "";

			// dormitorios
			$this->dormitorios->LinkCustomAttributes = "";
			$this->dormitorios->HrefValue = "";

			// informacion
			$this->informacion->LinkCustomAttributes = "";
			$this->informacion->HrefValue = "";

			// idtipo
			$this->idtipo->LinkCustomAttributes = "";
			$this->idtipo->HrefValue = "";

			// idtipoin
			$this->idtipoin->LinkCustomAttributes = "";
			$this->idtipoin->HrefValue = "";

			// idper
			$this->idper->LinkCustomAttributes = "";
			$this->idper->HrefValue = "";

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
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!ew_CheckInteger($this->superficie->FormValue)) {
			ew_AddMessage($gsFormError, $this->superficie->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->fechainicio->FormValue)) {
			ew_AddMessage($gsFormError, $this->fechainicio->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->fechaexpira->FormValue)) {
			ew_AddMessage($gsFormError, $this->fechaexpira->FldErrMsg());
		}
		if (!ew_CheckInteger($this->banios->FormValue)) {
			ew_AddMessage($gsFormError, $this->banios->FldErrMsg());
		}
		if (!ew_CheckInteger($this->dormitorios->FormValue)) {
			ew_AddMessage($gsFormError, $this->dormitorios->FldErrMsg());
		}
		if (!ew_CheckInteger($this->idtipo->FormValue)) {
			ew_AddMessage($gsFormError, $this->idtipo->FldErrMsg());
		}
		if (!ew_CheckInteger($this->idtipoin->FormValue)) {
			ew_AddMessage($gsFormError, $this->idtipoin->FldErrMsg());
		}
		if (!ew_CheckInteger($this->idper->FormValue)) {
			ew_AddMessage($gsFormError, $this->idper->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// direccion
		$this->direccion->SetDbValueDef($rsnew, $this->direccion->CurrentValue, NULL, FALSE);

		// precio
		$this->precio->SetDbValueDef($rsnew, $this->precio->CurrentValue, NULL, FALSE);

		// superficie
		$this->superficie->SetDbValueDef($rsnew, $this->superficie->CurrentValue, NULL, FALSE);

		// fechainicio
		$this->fechainicio->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechainicio->CurrentValue, 2), NULL, FALSE);

		// fechaexpira
		$this->fechaexpira->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechaexpira->CurrentValue, 0), NULL, FALSE);

		// banios
		$this->banios->SetDbValueDef($rsnew, $this->banios->CurrentValue, NULL, FALSE);

		// dormitorios
		$this->dormitorios->SetDbValueDef($rsnew, $this->dormitorios->CurrentValue, NULL, FALSE);

		// informacion
		$this->informacion->SetDbValueDef($rsnew, $this->informacion->CurrentValue, NULL, FALSE);

		// idtipo
		$this->idtipo->SetDbValueDef($rsnew, $this->idtipo->CurrentValue, NULL, FALSE);

		// idtipoin
		$this->idtipoin->SetDbValueDef($rsnew, $this->idtipoin->CurrentValue, NULL, FALSE);

		// idper
		$this->idper->SetDbValueDef($rsnew, $this->idper->CurrentValue, NULL, FALSE);

		// imagen
		if ($this->imagen->Visible && !$this->imagen->Upload->KeepFile) {
			$this->imagen->Upload->DbValue = ""; // No need to delete old file
			if ($this->imagen->Upload->FileName == "") {
				$rsnew['imagen'] = NULL;
			} else {
				$rsnew['imagen'] = $this->imagen->Upload->FileName;
			}
		}
		if ($this->imagen->Visible && !$this->imagen->Upload->KeepFile) {
			if (!ew_Empty($this->imagen->Upload->Value)) {
				$rsnew['imagen'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->imagen->UploadPath), $rsnew['imagen']); // Get new file name
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->idbienes->setDbValue($conn->Insert_ID());
				$rsnew['idbienes'] = $this->idbienes->DbValue;
				if ($this->imagen->Visible && !$this->imagen->Upload->KeepFile) {
					if (!ew_Empty($this->imagen->Upload->Value)) {
						$this->imagen->Upload->SaveToFile($this->imagen->UploadPath, $rsnew['imagen'], TRUE);
					}
				}
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// imagen
		ew_CleanUploadTempPath($this->imagen, $this->imagen->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("bieneslist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($bienes_add)) $bienes_add = new cbienes_add();

// Page init
$bienes_add->Page_Init();

// Page main
$bienes_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$bienes_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fbienesadd = new ew_Form("fbienesadd", "add");

// Validate form
fbienesadd.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_superficie");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($bienes->superficie->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fechainicio");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($bienes->fechainicio->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fechaexpira");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($bienes->fechaexpira->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_banios");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($bienes->banios->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dormitorios");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($bienes->dormitorios->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_idtipo");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($bienes->idtipo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_idtipoin");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($bienes->idtipoin->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_idper");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($bienes->idper->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fbienesadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fbienesadd.ValidateRequired = true;
<?php } else { ?>
fbienesadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$bienes_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $bienes_add->ShowPageHeader(); ?>
<?php
$bienes_add->ShowMessage();
?>
<form name="fbienesadd" id="fbienesadd" class="<?php echo $bienes_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($bienes_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $bienes_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="bienes">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($bienes_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($bienes->direccion->Visible) { // direccion ?>
	<div id="r_direccion" class="form-group">
		<label id="elh_bienes_direccion" for="x_direccion" class="col-sm-2 control-label ewLabel"><?php echo $bienes->direccion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $bienes->direccion->CellAttributes() ?>>
<span id="el_bienes_direccion">
<input type="text" data-table="bienes" data-field="x_direccion" name="x_direccion" id="x_direccion" placeholder="<?php echo ew_HtmlEncode($bienes->direccion->getPlaceHolder()) ?>" value="<?php echo $bienes->direccion->EditValue ?>"<?php echo $bienes->direccion->EditAttributes() ?>>
</span>
<?php echo $bienes->direccion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($bienes->precio->Visible) { // precio ?>
	<div id="r_precio" class="form-group">
		<label id="elh_bienes_precio" for="x_precio" class="col-sm-2 control-label ewLabel"><?php echo $bienes->precio->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $bienes->precio->CellAttributes() ?>>
<span id="el_bienes_precio">
<input type="text" data-table="bienes" data-field="x_precio" name="x_precio" id="x_precio" placeholder="<?php echo ew_HtmlEncode($bienes->precio->getPlaceHolder()) ?>" value="<?php echo $bienes->precio->EditValue ?>"<?php echo $bienes->precio->EditAttributes() ?>>
</span>
<?php echo $bienes->precio->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($bienes->superficie->Visible) { // superficie ?>
	<div id="r_superficie" class="form-group">
		<label id="elh_bienes_superficie" for="x_superficie" class="col-sm-2 control-label ewLabel"><?php echo $bienes->superficie->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $bienes->superficie->CellAttributes() ?>>
<span id="el_bienes_superficie">
<input type="text" data-table="bienes" data-field="x_superficie" name="x_superficie" id="x_superficie" size="30" placeholder="<?php echo ew_HtmlEncode($bienes->superficie->getPlaceHolder()) ?>" value="<?php echo $bienes->superficie->EditValue ?>"<?php echo $bienes->superficie->EditAttributes() ?>>
</span>
<?php echo $bienes->superficie->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($bienes->fechainicio->Visible) { // fechainicio ?>
	<div id="r_fechainicio" class="form-group">
		<label id="elh_bienes_fechainicio" for="x_fechainicio" class="col-sm-2 control-label ewLabel"><?php echo $bienes->fechainicio->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $bienes->fechainicio->CellAttributes() ?>>
<span id="el_bienes_fechainicio">
<input type="text" data-table="bienes" data-field="x_fechainicio" data-format="2" name="x_fechainicio" id="x_fechainicio" placeholder="<?php echo ew_HtmlEncode($bienes->fechainicio->getPlaceHolder()) ?>" value="<?php echo $bienes->fechainicio->EditValue ?>"<?php echo $bienes->fechainicio->EditAttributes() ?>>
</span>
<?php echo $bienes->fechainicio->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($bienes->fechaexpira->Visible) { // fechaexpira ?>
	<div id="r_fechaexpira" class="form-group">
		<label id="elh_bienes_fechaexpira" for="x_fechaexpira" class="col-sm-2 control-label ewLabel"><?php echo $bienes->fechaexpira->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $bienes->fechaexpira->CellAttributes() ?>>
<span id="el_bienes_fechaexpira">
<input type="text" data-table="bienes" data-field="x_fechaexpira" name="x_fechaexpira" id="x_fechaexpira" placeholder="<?php echo ew_HtmlEncode($bienes->fechaexpira->getPlaceHolder()) ?>" value="<?php echo $bienes->fechaexpira->EditValue ?>"<?php echo $bienes->fechaexpira->EditAttributes() ?>>
</span>
<?php echo $bienes->fechaexpira->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($bienes->banios->Visible) { // banios ?>
	<div id="r_banios" class="form-group">
		<label id="elh_bienes_banios" for="x_banios" class="col-sm-2 control-label ewLabel"><?php echo $bienes->banios->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $bienes->banios->CellAttributes() ?>>
<span id="el_bienes_banios">
<input type="text" data-table="bienes" data-field="x_banios" name="x_banios" id="x_banios" size="30" placeholder="<?php echo ew_HtmlEncode($bienes->banios->getPlaceHolder()) ?>" value="<?php echo $bienes->banios->EditValue ?>"<?php echo $bienes->banios->EditAttributes() ?>>
</span>
<?php echo $bienes->banios->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($bienes->dormitorios->Visible) { // dormitorios ?>
	<div id="r_dormitorios" class="form-group">
		<label id="elh_bienes_dormitorios" for="x_dormitorios" class="col-sm-2 control-label ewLabel"><?php echo $bienes->dormitorios->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $bienes->dormitorios->CellAttributes() ?>>
<span id="el_bienes_dormitorios">
<input type="text" data-table="bienes" data-field="x_dormitorios" name="x_dormitorios" id="x_dormitorios" size="30" placeholder="<?php echo ew_HtmlEncode($bienes->dormitorios->getPlaceHolder()) ?>" value="<?php echo $bienes->dormitorios->EditValue ?>"<?php echo $bienes->dormitorios->EditAttributes() ?>>
</span>
<?php echo $bienes->dormitorios->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($bienes->informacion->Visible) { // informacion ?>
	<div id="r_informacion" class="form-group">
		<label id="elh_bienes_informacion" for="x_informacion" class="col-sm-2 control-label ewLabel"><?php echo $bienes->informacion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $bienes->informacion->CellAttributes() ?>>
<span id="el_bienes_informacion">
<textarea data-table="bienes" data-field="x_informacion" name="x_informacion" id="x_informacion" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($bienes->informacion->getPlaceHolder()) ?>"<?php echo $bienes->informacion->EditAttributes() ?>><?php echo $bienes->informacion->EditValue ?></textarea>
</span>
<?php echo $bienes->informacion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($bienes->idtipo->Visible) { // idtipo ?>
	<div id="r_idtipo" class="form-group">
		<label id="elh_bienes_idtipo" for="x_idtipo" class="col-sm-2 control-label ewLabel"><?php echo $bienes->idtipo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $bienes->idtipo->CellAttributes() ?>>
<span id="el_bienes_idtipo">
<input type="text" data-table="bienes" data-field="x_idtipo" name="x_idtipo" id="x_idtipo" size="30" placeholder="<?php echo ew_HtmlEncode($bienes->idtipo->getPlaceHolder()) ?>" value="<?php echo $bienes->idtipo->EditValue ?>"<?php echo $bienes->idtipo->EditAttributes() ?>>
</span>
<?php echo $bienes->idtipo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($bienes->idtipoin->Visible) { // idtipoin ?>
	<div id="r_idtipoin" class="form-group">
		<label id="elh_bienes_idtipoin" for="x_idtipoin" class="col-sm-2 control-label ewLabel"><?php echo $bienes->idtipoin->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $bienes->idtipoin->CellAttributes() ?>>
<span id="el_bienes_idtipoin">
<input type="text" data-table="bienes" data-field="x_idtipoin" name="x_idtipoin" id="x_idtipoin" size="30" placeholder="<?php echo ew_HtmlEncode($bienes->idtipoin->getPlaceHolder()) ?>" value="<?php echo $bienes->idtipoin->EditValue ?>"<?php echo $bienes->idtipoin->EditAttributes() ?>>
</span>
<?php echo $bienes->idtipoin->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($bienes->idper->Visible) { // idper ?>
	<div id="r_idper" class="form-group">
		<label id="elh_bienes_idper" for="x_idper" class="col-sm-2 control-label ewLabel"><?php echo $bienes->idper->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $bienes->idper->CellAttributes() ?>>
<span id="el_bienes_idper">
<input type="text" data-table="bienes" data-field="x_idper" name="x_idper" id="x_idper" size="30" placeholder="<?php echo ew_HtmlEncode($bienes->idper->getPlaceHolder()) ?>" value="<?php echo $bienes->idper->EditValue ?>"<?php echo $bienes->idper->EditAttributes() ?>>
</span>
<?php echo $bienes->idper->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($bienes->imagen->Visible) { // imagen ?>
	<div id="r_imagen" class="form-group">
		<label id="elh_bienes_imagen" class="col-sm-2 control-label ewLabel"><?php echo $bienes->imagen->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $bienes->imagen->CellAttributes() ?>>
<span id="el_bienes_imagen">
<div id="fd_x_imagen">
<span title="<?php echo $bienes->imagen->FldTitle() ? $bienes->imagen->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($bienes->imagen->ReadOnly || $bienes->imagen->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="bienes" data-field="x_imagen" name="x_imagen" id="x_imagen"<?php echo $bienes->imagen->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_imagen" id= "fn_x_imagen" value="<?php echo $bienes->imagen->Upload->FileName ?>">
<input type="hidden" name="fa_x_imagen" id= "fa_x_imagen" value="0">
<input type="hidden" name="fs_x_imagen" id= "fs_x_imagen" value="65535">
<input type="hidden" name="fx_x_imagen" id= "fx_x_imagen" value="<?php echo $bienes->imagen->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_imagen" id= "fm_x_imagen" value="<?php echo $bienes->imagen->UploadMaxFileSize ?>">
</div>
<table id="ft_x_imagen" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $bienes->imagen->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$bienes_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $bienes_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fbienesadd.Init();
</script>
<?php
$bienes_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$bienes_add->Page_Terminate();
?>
