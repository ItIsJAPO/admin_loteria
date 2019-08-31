<?php

header("Location: " . $dataAndView->getData('url'), $dataAndView->getData('code'));

// no exit or die here. the code after including the template will be executed, if there is...