<?php
// Note: This file is included from the library/Vanilla/Vanilla.Control.Menu.php class.
// TODO: Figure out a more Vanilla-like way to do this.

$this->CallDelegate('PreHeadRender');
echo '<div id="Header">';
echo '<a name="pgtop" id="pgtop"></a>';
echo '<br /><ul>';
while (list($Key, $Tab) = each($this->Tabs)) {
	echo '<li'.$this->TabClass($this->CurrentTab, $Tab['Value']).'><a href="'.$Tab['Url'].'" '.$Tab['Attributes'].'>'.$Tab['Text'].'</a></li>';
}
echo '</ul>
</div>';
$this->CallDelegate('PreBodyRender');
echo '<div id="Body">';
?>