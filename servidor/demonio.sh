#!/bin/bash
proceso="agente.py"
pid=`ps auxw | grep $proceso | grep -v grep`
if [ -z "$pid" ]; then
        echo "Agente caido. Levantando..."
        /root/virlocServer/agente.py &
else
        echo "El Agente esta levantado"
fi

proceso="server.py"
pid=`ps auxw | grep $proceso | grep -v grep`
if [ -z "$pid" ]; then
        echo "Server caido. Levantando..."
        /root/virlocServer/agente.py &
else
        echo "El Server esta levantado"
fi

cp /root/virlocServer/*.php /var/www/
