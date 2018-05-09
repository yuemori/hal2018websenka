#!/bin/bash

echo ${1}

if [ -f "${1}.php" ];
then
    echo "___ file exists."
fi


cat ./Welcome.php | \
    sed -e 's/welcome_message/'${1}'.php/g' | \
    sed -e 's/Welcome/'${1}'/g' > ${1}.php

cat <<EOF >../views/${1}.php
<html>
  <head>
    <link href="<?=base_url();?>css/default.css" type="text/css" rel="stylesheet" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>${1}</title>
  </head>
  <body>
    <p class="alert">${1} test view </p>
    このファイルは自動生成されました。<br />
  </body>
</html>
EOF
