<?php
$bds=array(
'<%%%=(.*)%>',"'.\\1.'",
'<%%=(.*)%>',"',\\1,'",
'<%=(.*)%>','<?php echo \\1; ?>',
'<%%(.*)%>','\\1;',
'<%(.*)%>','<?php \\1; ?>',
'<\((.*)\)>','[\\1]',
);
?>