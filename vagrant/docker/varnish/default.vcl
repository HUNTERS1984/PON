vcl 4.0;
acl invalidators {
    "localhost";
    # Add any other IP addresses that your application runs on and that you
    # want to allow invalidation requests from. For instance:
    # "192.168.1.0"/24;
}

backend default {
    .host = "pon-webserver";
    .port = "8080";
}

sub vcl_recv {

    set req.http.cookie = ";" + req.http.cookie;
    set req.http.cookie = regsuball(req.http.cookie, "; +", ";");
    set req.http.cookie = regsuball(req.http.cookie, ";(PHPSESSID)=", "; \1=");
    set req.http.cookie = regsuball(req.http.cookie, ";[^ ][^;]*", "");
    set req.http.cookie = regsuball(req.http.cookie, "^[; ]+|[; ]+$", "");

    if (req.http.Cache-Control ~ "no-cache" && client.ip ~ invalidators) {
            set req.hash_always_miss = true;
    }

    if (req.method == "PURGE") {
        if (!client.ip ~ invalidators) {
            return (synth(405, "Not allowed"));
        }
        return (purge);
    }
    ## Refresh

    if (req.method == "BAN") {
        if (!client.ip ~ invalidators) {
            return (synth(405, "Not allowed"));
        }

        if (req.http.X-Cache-Tags) {
            ban("obj.http.X-Host ~ " + req.http.X-Host
                + " && obj.http.X-Url ~ " + req.http.X-Url
                + " && obj.http.content-type ~ " + req.http.X-Content-Type
                + " && obj.http.X-Cache-Tags ~ " + req.http.X-Cache-Tags
            );
        } else {
            ban("obj.http.X-Host ~ " + req.http.X-Host
                + " && obj.http.X-Url ~ " + req.http.X-Url
                + " && obj.http.content-type ~ " + req.http.X-Content-Type
            );
        }

        return (synth(200, "Banned"));
    }

     # Prevent tampering attacks on the hash mechanism
        if (req.restarts == 0
            && (req.http.accept ~ "application/vnd.fos.user-context-hash"
                || req.http.X-User-Context-Hash
            )
        ) {
            return (synth(400));
        }

        # Lookup the context hash if there are credentials on the request
        # Only do this for cacheable requests. Returning a hash lookup discards the request body.
        # https://www.varnish-cache.org/trac/ticket/652
        if (req.restarts == 0
            && (req.http.cookie || req.http.authorization)
            && (req.method == "GET" || req.method == "HEAD")
        ) {
            # Backup accept header, if set
            if (req.http.accept) {
                set req.http.X-Fos-Original-Accept = req.http.accept;
            }
            set req.http.accept = "application/vnd.fos.user-context-hash";

            # Backup original URL
            set req.http.X-Fos-Original-Url = req.url;
            set req.url = "/_fos_user_context_hash";

            # Force the lookup, the backend must tell not to cache or vary on all
            # headers that are used to build the hash.
            return (hash);
        }

        # Rebuild the original request which now has the hash.
        if (req.restarts > 0
            && req.http.accept == "application/vnd.fos.user-context-hash"
        ) {
            set req.url = req.http.X-Fos-Original-Url;
            unset req.http.X-Fos-Original-Url;
            if (req.http.X-Fos-Original-Accept) {
                set req.http.accept = req.http.X-Fos-Original-Accept;
                unset req.http.X-Fos-Original-Accept;
            } else {
                # If accept header was not set in original request, remove the header here.
                unset req.http.accept;
            }

            # Force the lookup, the backend must tell not to cache or vary on the
            # user hash to properly separate cached data.

            return (hash);
        }
}

###Ban
sub vcl_backend_response {

    # Set ban-lurker friendly custom headers
    set beresp.http.X-Url = bereq.url;
    set beresp.http.X-Host = bereq.http.host;

    if (bereq.http.accept ~ "application/vnd.fos.user-context-hash"
        && beresp.status >= 500
    ) {
        return (abandon);
    }
}

sub vcl_deliver {

    # Keep ban-lurker headers only if debugging is enabled
    if (!resp.http.X-Cache-Debug) {
        # Remove ban-lurker friendly custom headers when delivering to client
        unset resp.http.X-Url;
        unset resp.http.X-Host;
        unset resp.http.X-Cache-Tags;
    }

    if (resp.http.X-Varnish ~ " ") {
        set resp.http.X-Cache = "HIT";
    } else {
        set resp.http.X-Cache = "MISS";
    }

    if (req.restarts == 0
        && resp.http.content-type ~ "application/vnd.fos.user-context-hash"
    ) {
        set req.http.X-User-Context-Hash = resp.http.X-User-Context-Hash;

        return (restart);
    }

    # If we get here, this is a real response that gets sent to the client.

    # Remove the vary on context user hash, this is nothing public. Keep all
    # other vary headers.
    set resp.http.Vary = regsub(resp.http.Vary, "(?i),? *X-User-Context-Hash *", "");
    set resp.http.Vary = regsub(resp.http.Vary, "^, *", "");
    if (resp.http.Vary == "") {
        unset resp.http.Vary;
    }

    # Sanity check to prevent ever exposing the hash to a client.
    unset resp.http.X-User-Context-Hash;
}