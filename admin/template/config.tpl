<div class="titrePage">
  <h2>Casify</h2>
</div>

<form method="post" action="" class="properties">
<fieldset id="commentsConf">
  <ul>
    <li>
      <label>
	<b>CAS host</b>
        <input type="text" name="cas_host" value="{$cas_host}" />
      </label>
    </li>
    <li>
      <label>
	<b>CAS port</b>
        <input type="number" name="cas_port" value="{$cas_port}" />
      </label>
    </li>
  </ul>
</fieldset>

<p style="text-align:left;"><input type="submit" name="save_config" value="{'Save Settings'|translate}"></p>
</form>
