<?php
// Note: This file is included from the library/People/People.Control.Leave.php class.

	echo '<div id="SignOutForm">';
	$this->Render_PostBackForm();
	$this->Render_Warnings();
	echo '<fieldset>
			<div class="Submit">
				<input type="submit" name="sign-out" value="'.$this->Context->GetDefinition('SignOut').'" class="Button" />
			</div>
		</form>
		</fieldset>
	</div>';
?>