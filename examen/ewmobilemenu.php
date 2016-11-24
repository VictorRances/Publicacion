<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(1, "mmi_bienes", $Language->MenuPhrase("1", "MenuText"), "bieneslist.php", -1, "", IsLoggedIn() || AllowListMenu('{33D340AA-42AB-4291-828D-AC1DA9E53193}bienes'), FALSE, FALSE);
$RootMenu->AddMenuItem(2, "mmi_bienespagos", $Language->MenuPhrase("2", "MenuText"), "bienespagoslist.php", -1, "", IsLoggedIn() || AllowListMenu('{33D340AA-42AB-4291-828D-AC1DA9E53193}bienespagos'), FALSE, FALSE);
$RootMenu->AddMenuItem(3, "mmi_personal", $Language->MenuPhrase("3", "MenuText"), "personallist.php", -1, "", IsLoggedIn() || AllowListMenu('{33D340AA-42AB-4291-828D-AC1DA9E53193}personal'), FALSE, FALSE);
$RootMenu->AddMenuItem(4, "mmi_tipoinmueble", $Language->MenuPhrase("4", "MenuText"), "tipoinmueblelist.php", -1, "", IsLoggedIn() || AllowListMenu('{33D340AA-42AB-4291-828D-AC1DA9E53193}tipoinmueble'), FALSE, FALSE);
$RootMenu->AddMenuItem(5, "mmi_tipopublicacion", $Language->MenuPhrase("5", "MenuText"), "tipopublicacionlist.php", -1, "", IsLoggedIn() || AllowListMenu('{33D340AA-42AB-4291-828D-AC1DA9E53193}tipopublicacion'), FALSE, FALSE);
$RootMenu->AddMenuItem(6, "mmi_tiposdepago", $Language->MenuPhrase("6", "MenuText"), "tiposdepagolist.php", -1, "", IsLoggedIn() || AllowListMenu('{33D340AA-42AB-4291-828D-AC1DA9E53193}tiposdepago'), FALSE, FALSE);
$RootMenu->AddMenuItem(-1, "mmi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mmi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
