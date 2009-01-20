{validation_errors for=$stylesheet}

{form}

{label for='stylesheet[name]'}Name{/label}: {textbox name='stylesheet[name]' value=$stylesheet.name}<br />
{label for='stylesheet[value]'}Content{/label}: {textarea name='stylesheet[value]' value=$stylesheet.value cols='80' rows='10'}<br />
{label for='stylesheet[active]'}Active{/label}: {checkbox name='stylesheet[active]' checked=$stylesheet.active}<br />
{hidden name='stylesheet[id]' value=$stylesheet.id}{submit name='submit' value="Submit"}{submit name='cancel' value="Cancel"}

{/form}