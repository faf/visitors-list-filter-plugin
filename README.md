# Visitors list filter plugin

It removes duplicates from the visitors list. A visitor is considered a
duplicate when its name, IP address, and user agent are the same as of another
visitor.


## Installation

1. Get the archive with the plugin sources (actually, one need to build it).

2. Untar/unzip the plugin's archive.

3. Put files of the plugin into the `<Mibew root>/plugins` folder.

4. Add plugin's config to "plugins" structure in "`<Mibew root>`/configs/config.yml". If the "plugins" stucture looks like `plugins: []` it should become:
    ```yaml
    plugins:
        "FAF:VisitorsListFilter": # Plugin's configurations are described below
            filter_mode: "filtration mode"
    ```

5. Navigate to "`<Mibew Base URL>`/operator/plugin" page and enable the plugin.

### alternative way

1. Download the file `Plugin.php` from this repo.

2. Create folder "`<Mibew root>`/plugins/FAF/Mibew/Plugin/VisitorsListFilter".

3. Put `Plugin.php` into the created folder.

4. Take the same steps 4 and 5 as in the first way.

## Plugin's configurations

The plugin can be configured with values in "`<Mibew root>`/configs/config.yml" file.

### config.filter_mode

Type: `String`

At the time only `strict` and `light` filtration modes are supported.


## Build from sources

There are several actions one should do before use the latest version of the plugin from the repository:

1. Obtain a copy of the repository using `git clone`, download button, or another way.
2. Install [node.js](http://nodejs.org/) and [npm](https://www.npmjs.org/).
3. Install [Gulp](http://gulpjs.com/).
4. Install npm dependencies using `npm install`.
5. Run Gulp to build the sources using `gulp default`.

Finally `.tar.gz` and `.zip` archives of the ready-to-use Plugin will be available in `release` directory.


## License

[Apache License 2.0](http://www.apache.org/licenses/LICENSE-2.0.html)
