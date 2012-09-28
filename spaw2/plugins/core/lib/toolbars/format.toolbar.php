<?php
$items = array
(
  new SpawTbButton("core", "bold", "isStandardFunctionEnabled", "isStandardFunctionPushed", "standardFunctionClick"),
  new SpawTbButton("core", "italic", "isStandardFunctionEnabled", "isStandardFunctionPushed", "standardFunctionClick"),
  new SpawTbButton("core", "superscript", "isStandardFunctionEnabled", "isStandardFunctionPushed", "standardFunctionClick"),
  new SpawTbButton("core", "fore_color", "isForeColorEnabled", "", "foreColorClick", SPAW_AGENT_ALL, true),
  new SpawTbImage("core", "separator"),
    new SpawTbButton("core", "justifyleft", "isStandardFunctionEnabled", "isStandardFunctionPushed", "standardFunctionClick", SPAW_AGENT_ALL, true),
  new SpawTbButton("core", "justifycenter", "isStandardFunctionEnabled", "isStandardFunctionPushed", "standardFunctionClick", SPAW_AGENT_ALL, true),
  new SpawTbButton("core", "justifyright", "isStandardFunctionEnabled", "isStandardFunctionPushed", "standardFunctionClick", SPAW_AGENT_ALL, true),
  new SpawTbButton("core", "justifyfull", "isStandardFunctionEnabled", "isStandardFunctionPushed", "standardFunctionClick", SPAW_AGENT_ALL, true),
  new SpawTbImage("core", "separator"),
  new SpawTbButton("core", "insertorderedlist", "isStandardFunctionEnabled", "isStandardFunctionPushed", "standardFunctionClick", SPAW_AGENT_ALL, true),
  new SpawTbButton("core", "insertunorderedlist", "isStandardFunctionEnabled", "isStandardFunctionPushed", "standardFunctionClick", SPAW_AGENT_ALL, true),
  new SpawTbImage("core", "separator"),
    new SpawTbButton("core", "inserthorizontalrule", "isStandardFunctionEnabled", "", "insertHorizontalRuleClick"),
  new SpawTbImage("core", "separator"),
);
?>
