cnxcc-db-sync
=============

XMLRPC client for cnxcc to synchronize in-memory information to persistent storage. This script works as a helper for
https://github.com/caruizdiaz/cnxcc-web.

Dependencies
=============
xmlrpc module for php


Usage
=============

Database configuration should be put in "dbconfig.include.php"

<pre>
    php cnxcc-db-sync.php &#60;sip-server-ip&#62; &#60;xmlrpc-port&#62;
</pre>

To check and update the database in a more real-time fashion, it is recommended to run to script every 1 second

<pre>
    while true; do php cnxcc-db-sync.php 127.0.0.1 5060; sleep 1; done;
</pre>

