{cms_validation_errors for=$template}

{form}

{label for='template[name]'}Name{/label}: {textbox name='template[name]' value=$template.name}<br />
{label for='template[name]'}Content{/label}: {textarea name='template[content]' value=$template.content cols='80' rows='10'}<br />
{label for='template[active]'}Active{/label}: {checkbox name='template[active]' checked=$template.active}<br />
{hidden name='template[id]' value=$template.id}{submit name='submit' value="Submit"}{submit name='cancel' value="Cancel"}{submit name='apply' value="Apply" remote="true"}

{/form}