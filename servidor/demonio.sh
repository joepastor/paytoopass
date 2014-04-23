#!/bin/bash
proceso="agente.py"

array=( agente.py server.py messenger.py cobrador.py )
for i in "${array[@]}"
do
	if [ -z "$pid" ]; then
        echo "$i caido. Levantando..."
        python /paytoopass/servidor/$i &
	else
        echo "El Agente $i esta levantado"
fi
done