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

$bienes_view = NULL; // Initialize page object first

class cbienes_view extends cbienes {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{33D340AA-42AB-4291-828D-AC1DA9E53193}";

	// Table name
	var $TableName = 'bienes';

	// Page object name
	var $PageObjName = 'bienes_view';

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
		$KeyUrl = "";
		if (@$_GET["idbienes"] <> "") {
			$this->RecKey["idbienes"] = $_GET["idbienes"];
			$KeyUrl .= "&amp;idbienes=" . urlencode($this->RecKey["idbienes"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (personal)
		if (!isset($GLOBALS['personal'])) $GLOBALS['personal'] = new cpersonal();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

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

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
		if (!$Security->CanView()) {
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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $IsModal = FALSE;
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["idbienes"] <> "") {
				$this->idbienes->setQueryStringValue($_GET["idbienes"]);
				$this->RecKey["idbienes"] = $this->idbienes->QueryStringValue;
			} elseif (@$_POST["idbienes"] <> "") {
				$this->idbienes->setFormValue($_POST["idbienes"]);
				$this->RecKey["idbienes"] = $this->idbienes->FormValue;
			} else {
				$sReturnUrl = "bieneslist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "bieneslist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "bieneslist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("ViewPageAddLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->AddUrl) . "',caption:'" . $addcaption . "'});\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Edit
		$item = &$option->Add("edit");
		$editcaption = ew_HtmlTitle($Language->Phrase("ViewPageEditLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->EditUrl) . "',caption:'" . $editcaption . "'});\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit());

		// Copy
		$item = &$option->Add("copy");
		$copycaption = ew_HtmlTitle($Language->Phrase("ViewPageCopyLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->CopyUrl) . "',caption:'" . $copycaption . "'});\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->CanAdd());

		// Delete
		$item = &$option->Add("delete");
		if ($this->IsModal) // Handle as inline delete
			$item->Body = "<a onclick=\"return ew_ConfirmDelete(this);\" class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode(ew_AddQueryStringToUrl($this->DeleteUrl, "a_delete=1")) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->CanDelete());

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = FALSE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("bieneslist.php"), "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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
if (!isset($bienes_view)) $bienes_view = new cbienes_view();

// Page init
$bienes_view->Page_Init();

// Page main
$bienes_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$bienes_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fbienesview = new ew_Form("fbienesview", "view");

// Form_CustomValidate event
fbienesview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fbienesview.ValidateRequired = true;
<?php } else { ?>
fbienesview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if (!$bienes_view->IsModal) { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php $bienes_view->ExportOptions->Render("body") ?>
<?php
	foreach ($bienes_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if (!$bienes_view->IsModal) { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php $bienes_view->ShowPageHeader(); ?>
<?php
$bienes_view->ShowMessage();
?>
<form name="fbienesview" id="fbienesview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($bienes_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $bienes_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="bienes">
<?php if ($bienes_view->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($bienes->idbienes->Visible) { // idbienes ?>
	<tr id="r_idbienes">
		<td><span id="elh_bienes_idbienes"><?php echo $bienes->idbienes->FldCaption() ?></span></td>
		<td data-name="idbienes"<?php echo $bienes->idbienes->CellAttributes() ?>>
<span id="el_bienes_idbienes">
<span<?php echo $bienes->idbienes->ViewAttributes() ?>>
<?php echo $bienes->idbienes->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($bienes->direccion->Visible) { // direccion ?>
	<tr id="r_direccion">
		<td><span id="elh_bienes_direccion"><?php echo $bienes->direccion->FldCaption() ?></span></td>
		<td data-name="direccion"<?php echo $bienes->direccion->CellAttributes() ?>>
<span id="el_bienes_direccion">
<span<?php echo $bienes->direccion->ViewAttributes() ?>>
<?php echo $bienes->direccion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($bienes->precio->Visible) { // precio ?>
	<tr id="r_precio">
		<td><span id="elh_bienes_precio"><?php echo $bienes->precio->FldCaption() ?></span></td>
		<td data-name="precio"<?php echo $bienes->precio->CellAttributes() ?>>
<span id="el_bienes_precio">
<span<?php echo $bienes->precio->ViewAttributes() ?>>
<?php echo $bienes->precio->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($bienes->superficie->Visible) { // superficie ?>
	<tr id="r_superficie">
		<td><span id="elh_bienes_superficie"><?php echo $bienes->superficie->FldCaption() ?></span></td>
		<td data-name="superficie"<?php echo $bienes->superficie->CellAttributes() ?>>
<span id="el_bienes_superficie">
<span<?php echo $bienes->superficie->ViewAttributes() ?>>
<?php echo $bienes->superficie->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($bienes->fechainicio->Visible) { // fechainicio ?>
	<tr id="r_fechainicio">
		<td><span id="elh_bienes_fechainicio"><?php echo $bienes->fechainicio->FldCaption() ?></span></td>
		<td data-name="fechainicio"<?php echo $bienes->fechainicio->CellAttributes() ?>>
<span id="el_bienes_fechainicio">
<span<?php echo $bienes->fechainicio->ViewAttributes() ?>>
<?php echo $bienes->fechainicio->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($bienes->fechaexpira->Visible) { // fechaexpira ?>
	<tr id="r_fechaexpira">
		<td><span id="elh_bienes_fechaexpira"><?php echo $bienes->fechaexpira->FldCaption() ?></span></td>
		<td data-name="fechaexpira"<?php echo $bienes->fechaexpira->CellAttributes() ?>>
<span id="el_bienes_fechaexpira">
<span<?php echo $bienes->fechaexpira->ViewAttributes() ?>>
<?php echo $bienes->fechaexpira->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($bienes->banios->Visible) { // banios ?>
	<tr id="r_banios">
		<td><span id="elh_bienes_banios"><?php echo $bienes->banios->FldCaption() ?></span></td>
		<td data-name="banios"<?php echo $bienes->banios->CellAttributes() ?>>
<span id="el_bienes_banios">
<span<?php echo $bienes->banios->ViewAttributes() ?>>
<?php echo $bienes->banios->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($bienes->dormitorios->Visible) { // dormitorios ?>
	<tr id="r_dormitorios">
		<td><span id="elh_bienes_dormitorios"><?php echo $bienes->dormitorios->FldCaption() ?></span></td>
		<td data-name="dormitorios"<?php echo $bienes->dormitorios->CellAttributes() ?>>
<span id="el_bienes_dormitorios">
<span<?php echo $bienes->dormitorios->ViewAttributes() ?>>
<?php echo $bienes->dormitorios->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($bienes->informacion->Visible) { // informacion ?>
	<tr id="r_informacion">
		<td><span id="elh_bienes_informacion"><?php echo $bienes->informacion->FldCaption() ?></span></td>
		<td data-name="informacion"<?php echo $bienes->informacion->CellAttributes() ?>>
<span id="el_bienes_informacion">
<span<?php echo $bienes->informacion->ViewAttributes() ?>>
<?php echo $bienes->informacion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($bienes->idtipo->Visible) { // idtipo ?>
	<tr id="r_idtipo">
		<td><span id="elh_bienes_idtipo"><?php echo $bienes->idtipo->FldCaption() ?></span></td>
		<td data-name="idtipo"<?php echo $bienes->idtipo->CellAttributes() ?>>
<span id="el_bienes_idtipo">
<span<?php echo $bienes->idtipo->ViewAttributes() ?>>
<?php echo $bienes->idtipo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($bienes->idtipoin->Visible) { // idtipoin ?>
	<tr id="r_idtipoin">
		<td><span id="elh_bienes_idtipoin"><?php echo $bienes->idtipoin->FldCaption() ?></span></td>
		<td data-name="idtipoin"<?php echo $bienes->idtipoin->CellAttributes() ?>>
<span id="el_bienes_idtipoin">
<span<?php echo $bienes->idtipoin->ViewAttributes() ?>>
<?php echo $bienes->idtipoin->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($bienes->idper->Visible) { // idper ?>
	<tr id="r_idper">
		<td><span id="elh_bienes_idper"><?php echo $bienes->idper->FldCaption() ?></span></td>
		<td data-name="idper"<?php echo $bienes->idper->CellAttributes() ?>>
<span id="el_bienes_idper">
<span<?php echo $bienes->idper->ViewAttributes() ?>>
<?php echo $bienes->idper->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($bienes->imagen->Visible) { // imagen ?>
	<tr id="r_imagen">
		<td><span id="elh_bienes_imagen"><?php echo $bienes->imagen->FldCaption() ?></span></td>
		<td data-name="imagen"<?php echo $bienes->imagen->CellAttributes() ?>>
<span id="el_bienes_imagen">
<span>
<?php echo ew_GetFileViewTag($bienes->imagen, $bienes->imagen->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
fbienesview.Init();
</script>
<?php
$bienes_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$bienes_view->Page_Terminate();
?>
