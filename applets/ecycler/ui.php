<div class="vbx-applet">

	<h2>ecyler API Configuration</h2>

	<div class="vbx-full-pane">
		<h3>ecycler API Key</h3>
		<p>The ecycler API key is available to any registered ecycler user. You'll find it under "My Account" via your ecycler Dashboard.</p>
		<fieldset class="vbx-input-container">
			<input type="text" name="apikey" class="medium" value="<?php echo AppletInstance::getValue('apikey'); ?>" />
		</fieldset>
	</div>

<?php $materialID = AppletInstance::getValue('mid'); ?>

	<div class="vbx-full-pane">
		<h3>Material Type</h3>
		<fieldset class="vbx-input-container">
			<select class="medium" name="mid">
				<option value="0" <?php echo ($materialID == "0" ? " selected" : ""); ?>>Select a Material Type</option>
				<option value="1" <?php echo ($materialID == "1" ? " selected" : ""); ?>>Newspapers</option>
				<option value="2" <?php echo ($materialID == "2" ? " selected" : ""); ?>>Aluminum Cans</option>
				<option value="3" <?php echo ($materialID == "3" ? " selected" : ""); ?>>PET Bottles</option>
				<option value="4" <?php echo ($materialID == "4" ? " selected" : ""); ?>>Glass Containers</option>
				<option value="5" <?php echo ($materialID == "5" ? " selected" : ""); ?>>5 cent CRV</option>
			</select>
		</fieldset>
	</div>

<?php if(AppletInstance::getFlowType() == 'voice'): ?>
	<h2>Next</h2>
	<p>After reading the number of materials you've recycled via ecycler.com, continue to the next applet</p>
	<div class="vbx-full-pane">
		<?php echo AppletUI::DropZone('next'); ?>
	</div><!-- .vbx-full-pane -->
<?php endif; ?>
</div><!-- .vbx-applet -->
