<?php

// Global variable for table object
$bienes = NULL;

//
// Table class for bienes
//
class cbienes extends cTable {
	var $idbienes;
	var $direccion;
	var $precio;
	var $superficie;
	var $fechainicio;
	var $fechaexpira;
	var $banios;
	var $dormitorios;
	var $informacion;
	var $idtipo;
	var $idtipoin;
	var $idper;
	var $imagen;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'bienes';
		$this->TableName = 'bienes';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`bienes`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// idbienes
		$this->idbienes = new cField('bienes', 'bienes', 'x_idbienes', 'idbienes', '`idbienes`', '`idbienes`', 3, -1, FALSE, '`idbienes`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->idbienes->Sortable = TRUE; // Allow sort
		$this->idbienes->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idbienes'] = &$this->idbienes;

		// direccion
		$this->direccion = new cField('bienes', 'bienes', 'x_direccion', 'direccion', '`direccion`', '`direccion`', 201, -1, FALSE, '`direccion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->direccion->Sortable = TRUE; // Allow sort
		$this->fields['direccion'] = &$this->direccion;

		// precio
		$this->precio = new cField('bienes', 'bienes', 'x_precio', 'precio', '`precio`', '`precio`', 201, -1, FALSE, '`precio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->precio->Sortable = TRUE; // Allow sort
		$this->fields['precio'] = &$this->precio;

		// superficie
		$this->superficie = new cField('bienes', 'bienes', 'x_superficie', 'superficie', '`superficie`', '`superficie`', 3, -1, FALSE, '`superficie`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->superficie->Sortable = TRUE; // Allow sort
		$this->superficie->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['superficie'] = &$this->superficie;

		// fechainicio
		$this->fechainicio = new cField('bienes', 'bienes', 'x_fechainicio', 'fechainicio', '`fechainicio`', 'DATE_FORMAT(`fechainicio`, \'%Y/%m/%d\')', 133, 2, FALSE, '`fechainicio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fechainicio->Sortable = TRUE; // Allow sort
		$this->fechainicio->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['fechainicio'] = &$this->fechainicio;

		// fechaexpira
		$this->fechaexpira = new cField('bienes', 'bienes', 'x_fechaexpira', 'fechaexpira', '`fechaexpira`', 'DATE_FORMAT(`fechaexpira`, \'%Y/%m/%d\')', 133, 0, FALSE, '`fechaexpira`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fechaexpira->Sortable = TRUE; // Allow sort
		$this->fechaexpira->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['fechaexpira'] = &$this->fechaexpira;

		// banios
		$this->banios = new cField('bienes', 'bienes', 'x_banios', 'banios', '`banios`', '`banios`', 3, -1, FALSE, '`banios`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->banios->Sortable = TRUE; // Allow sort
		$this->banios->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['banios'] = &$this->banios;

		// dormitorios
		$this->dormitorios = new cField('bienes', 'bienes', 'x_dormitorios', 'dormitorios', '`dormitorios`', '`dormitorios`', 3, -1, FALSE, '`dormitorios`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->dormitorios->Sortable = TRUE; // Allow sort
		$this->dormitorios->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['dormitorios'] = &$this->dormitorios;

		// informacion
		$this->informacion = new cField('bienes', 'bienes', 'x_informacion', 'informacion', '`informacion`', '`informacion`', 201, -1, FALSE, '`informacion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->informacion->Sortable = TRUE; // Allow sort
		$this->fields['informacion'] = &$this->informacion;

		// idtipo
		$this->idtipo = new cField('bienes', 'bienes', 'x_idtipo', 'idtipo', '`idtipo`', '`idtipo`', 3, -1, FALSE, '`idtipo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->idtipo->Sortable = TRUE; // Allow sort
		$this->idtipo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idtipo'] = &$this->idtipo;

		// idtipoin
		$this->idtipoin = new cField('bienes', 'bienes', 'x_idtipoin', 'idtipoin', '`idtipoin`', '`idtipoin`', 3, -1, FALSE, '`idtipoin`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->idtipoin->Sortable = TRUE; // Allow sort
		$this->idtipoin->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idtipoin'] = &$this->idtipoin;

		// idper
		$this->idper = new cField('bienes', 'bienes', 'x_idper', 'idper', '`idper`', '`idper`', 3, -1, FALSE, '`idper`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->idper->Sortable = TRUE; // Allow sort
		$this->idper->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idper'] = &$this->idper;

		// imagen
		$this->imagen = new cField('bienes', 'bienes', 'x_imagen', 'imagen', '`imagen`', '`imagen`', 201, -1, TRUE, '`imagen`', FALSE, FALSE, FALSE, 'IMAGE', 'FILE');
		$this->imagen->Sortable = TRUE; // Allow sort
		$this->fields['imagen'] = &$this->imagen;
	}

	// Set Field Visibility
	function SetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`bienes`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('idbienes', $rs))
				ew_AddFilter($where, ew_QuotedName('idbienes', $this->DBID) . '=' . ew_QuotedValue($rs['idbienes'], $this->idbienes->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`idbienes` = @idbienes@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->idbienes->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@idbienes@", ew_AdjustSql($this->idbienes->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "bieneslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "bieneslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("bienesview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("bienesview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "bienesadd.php?" . $this->UrlParm($parm);
		else
			$url = "bienesadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("bienesedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("bienesadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("bienesdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "idbienes:" . ew_VarToJson($this->idbienes->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->idbienes->CurrentValue)) {
			$sUrl .= "idbienes=" . urlencode($this->idbienes->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			if ($isPost && isset($_POST["idbienes"]))
				$arKeys[] = ew_StripSlashes($_POST["idbienes"]);
			elseif (isset($_GET["idbienes"]))
				$arKeys[] = ew_StripSlashes($_GET["idbienes"]);
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->idbienes->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
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
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// idbienes
		$this->idbienes->EditAttrs["class"] = "form-control";
		$this->idbienes->EditCustomAttributes = "";
		$this->idbienes->EditValue = $this->idbienes->CurrentValue;
		$this->idbienes->ViewCustomAttributes = "";

		// direccion
		$this->direccion->EditAttrs["class"] = "form-control";
		$this->direccion->EditCustomAttributes = "";
		$this->direccion->EditValue = $this->direccion->CurrentValue;
		$this->direccion->PlaceHolder = ew_RemoveHtml($this->direccion->FldCaption());

		// precio
		$this->precio->EditAttrs["class"] = "form-control";
		$this->precio->EditCustomAttributes = "";
		$this->precio->EditValue = $this->precio->CurrentValue;
		$this->precio->PlaceHolder = ew_RemoveHtml($this->precio->FldCaption());

		// superficie
		$this->superficie->EditAttrs["class"] = "form-control";
		$this->superficie->EditCustomAttributes = "";
		$this->superficie->EditValue = $this->superficie->CurrentValue;
		$this->superficie->PlaceHolder = ew_RemoveHtml($this->superficie->FldCaption());

		// fechainicio
		$this->fechainicio->EditAttrs["class"] = "form-control";
		$this->fechainicio->EditCustomAttributes = "";
		$this->fechainicio->EditValue = ew_FormatDateTime($this->fechainicio->CurrentValue, 2);
		$this->fechainicio->PlaceHolder = ew_RemoveHtml($this->fechainicio->FldCaption());

		// fechaexpira
		$this->fechaexpira->EditAttrs["class"] = "form-control";
		$this->fechaexpira->EditCustomAttributes = "";
		$this->fechaexpira->EditValue = ew_FormatDateTime($this->fechaexpira->CurrentValue, 8);
		$this->fechaexpira->PlaceHolder = ew_RemoveHtml($this->fechaexpira->FldCaption());

		// banios
		$this->banios->EditAttrs["class"] = "form-control";
		$this->banios->EditCustomAttributes = "";
		$this->banios->EditValue = $this->banios->CurrentValue;
		$this->banios->PlaceHolder = ew_RemoveHtml($this->banios->FldCaption());

		// dormitorios
		$this->dormitorios->EditAttrs["class"] = "form-control";
		$this->dormitorios->EditCustomAttributes = "";
		$this->dormitorios->EditValue = $this->dormitorios->CurrentValue;
		$this->dormitorios->PlaceHolder = ew_RemoveHtml($this->dormitorios->FldCaption());

		// informacion
		$this->informacion->EditAttrs["class"] = "form-control";
		$this->informacion->EditCustomAttributes = "";
		$this->informacion->EditValue = $this->informacion->CurrentValue;
		$this->informacion->PlaceHolder = ew_RemoveHtml($this->informacion->FldCaption());

		// idtipo
		$this->idtipo->EditAttrs["class"] = "form-control";
		$this->idtipo->EditCustomAttributes = "";
		$this->idtipo->EditValue = $this->idtipo->CurrentValue;
		$this->idtipo->PlaceHolder = ew_RemoveHtml($this->idtipo->FldCaption());

		// idtipoin
		$this->idtipoin->EditAttrs["class"] = "form-control";
		$this->idtipoin->EditCustomAttributes = "";
		$this->idtipoin->EditValue = $this->idtipoin->CurrentValue;
		$this->idtipoin->PlaceHolder = ew_RemoveHtml($this->idtipoin->FldCaption());

		// idper
		$this->idper->EditAttrs["class"] = "form-control";
		$this->idper->EditCustomAttributes = "";
		$this->idper->EditValue = $this->idper->CurrentValue;
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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->idbienes->Exportable) $Doc->ExportCaption($this->idbienes);
					if ($this->direccion->Exportable) $Doc->ExportCaption($this->direccion);
					if ($this->precio->Exportable) $Doc->ExportCaption($this->precio);
					if ($this->superficie->Exportable) $Doc->ExportCaption($this->superficie);
					if ($this->fechainicio->Exportable) $Doc->ExportCaption($this->fechainicio);
					if ($this->fechaexpira->Exportable) $Doc->ExportCaption($this->fechaexpira);
					if ($this->banios->Exportable) $Doc->ExportCaption($this->banios);
					if ($this->dormitorios->Exportable) $Doc->ExportCaption($this->dormitorios);
					if ($this->informacion->Exportable) $Doc->ExportCaption($this->informacion);
					if ($this->idtipo->Exportable) $Doc->ExportCaption($this->idtipo);
					if ($this->idtipoin->Exportable) $Doc->ExportCaption($this->idtipoin);
					if ($this->idper->Exportable) $Doc->ExportCaption($this->idper);
					if ($this->imagen->Exportable) $Doc->ExportCaption($this->imagen);
				} else {
					if ($this->idbienes->Exportable) $Doc->ExportCaption($this->idbienes);
					if ($this->direccion->Exportable) $Doc->ExportCaption($this->direccion);
					if ($this->precio->Exportable) $Doc->ExportCaption($this->precio);
					if ($this->superficie->Exportable) $Doc->ExportCaption($this->superficie);
					if ($this->fechainicio->Exportable) $Doc->ExportCaption($this->fechainicio);
					if ($this->fechaexpira->Exportable) $Doc->ExportCaption($this->fechaexpira);
					if ($this->banios->Exportable) $Doc->ExportCaption($this->banios);
					if ($this->dormitorios->Exportable) $Doc->ExportCaption($this->dormitorios);
					if ($this->idtipo->Exportable) $Doc->ExportCaption($this->idtipo);
					if ($this->idtipoin->Exportable) $Doc->ExportCaption($this->idtipoin);
					if ($this->idper->Exportable) $Doc->ExportCaption($this->idper);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->idbienes->Exportable) $Doc->ExportField($this->idbienes);
						if ($this->direccion->Exportable) $Doc->ExportField($this->direccion);
						if ($this->precio->Exportable) $Doc->ExportField($this->precio);
						if ($this->superficie->Exportable) $Doc->ExportField($this->superficie);
						if ($this->fechainicio->Exportable) $Doc->ExportField($this->fechainicio);
						if ($this->fechaexpira->Exportable) $Doc->ExportField($this->fechaexpira);
						if ($this->banios->Exportable) $Doc->ExportField($this->banios);
						if ($this->dormitorios->Exportable) $Doc->ExportField($this->dormitorios);
						if ($this->informacion->Exportable) $Doc->ExportField($this->informacion);
						if ($this->idtipo->Exportable) $Doc->ExportField($this->idtipo);
						if ($this->idtipoin->Exportable) $Doc->ExportField($this->idtipoin);
						if ($this->idper->Exportable) $Doc->ExportField($this->idper);
						if ($this->imagen->Exportable) $Doc->ExportField($this->imagen);
					} else {
						if ($this->idbienes->Exportable) $Doc->ExportField($this->idbienes);
						if ($this->direccion->Exportable) $Doc->ExportField($this->direccion);
						if ($this->precio->Exportable) $Doc->ExportField($this->precio);
						if ($this->superficie->Exportable) $Doc->ExportField($this->superficie);
						if ($this->fechainicio->Exportable) $Doc->ExportField($this->fechainicio);
						if ($this->fechaexpira->Exportable) $Doc->ExportField($this->fechaexpira);
						if ($this->banios->Exportable) $Doc->ExportField($this->banios);
						if ($this->dormitorios->Exportable) $Doc->ExportField($this->dormitorios);
						if ($this->idtipo->Exportable) $Doc->ExportField($this->idtipo);
						if ($this->idtipoin->Exportable) $Doc->ExportField($this->idtipoin);
						if ($this->idper->Exportable) $Doc->ExportField($this->idper);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
