Development HTTP server.
========================

This webserver is for use with WebEngine projects. Starting the server executes the PHP inbuilt server and uses the WebEngine `go.php` entry point as the router script.

When requiring this project directly using Composer, run the server by calling `vendor/bin/serve`. If you're using the [PHP.Gt Installer](https://www.php.gt/installer) you can start the server on its own with the `gt serve` command, or with the other background processes with the `gt run` command.