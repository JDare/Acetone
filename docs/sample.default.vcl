
backend default {
     .host = "127.0.0.1";
     .port = "8080";
}

acl purgers {
        "localhost";
}

sub vcl_recv {
	# allow PURGE from localhost
	if (req.request == "PURGE") {
		if (!client.ip ~ purgers) {
            error 405 "Not allowed.";
        }
        return (lookup);
       }
	}
    if (req.request == "BAN") {
		if (!client.ip ~ purgers) {
            error 405 "Not allowed.";
        }

        ban("obj.http.x-url ~ " + req.http.x-ban-url +
            " && obj.http.x-host ~ " + req.http.x-ban-host);
        error 200 "Banned";

    }
    if (req.request == "REFRESH") {
		if (!client.ip ~ purgers) {
            error 405 "Not allowed.";
        }
        set req.request = "GET";
        set req.hash_always_miss = true;

    }
}

sub vcl_fetch {
   if (req.url ~ "\.(png|gif|jpg)$") {
     unset beresp.http.set-cookie;
     set beresp.ttl = 1h;
	}
}
sub vcl_hit {
        if (req.request == "PURGE") {
                purge;
                error 200 "Purged";
        }
}
sub vcl_miss {
        if (req.request == "PURGE") {
                purge;
                error 404 "Not in cache";
        }
}
sub vcl_pass {
        if (req.request == "PURGE") {
                error 502 "PURGE on a passed object";
        }
}

#This is for debugging, remove for production site.
sub vcl_deliver {
	if (obj.hits > 0) {
		set resp.http.X-Varnish-Cache = "HIT";
	}
	else {
		set resp.http.X-Varnish-Cache = "MISS";
	}
     return (deliver);
}

