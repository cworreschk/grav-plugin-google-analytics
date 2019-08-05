# Google Analytics (gtag.js) Plugin

The **Google Analytics** Plugin is an extension for [Grav CMS](http://github.com/getgrav/grav).
You can easily integrate and configure Google Analytics in your Grav CMS site using the *global site tag (gtag.js)* framework and API without the need to touch any code.

> **Important:** This plugin uses the *global site tag* (*gtag.js*) framework and API for sending data to Google Analytics.
> If you prefer the deprecated *Universal Analytics* (*analytics.js*) library, please install the [Grav Google Analytics Plugin](https://github.com/escopecz/grav-ganalytics) of [John Linhart](https://github.com/escopecz) and me.

### Features
- Easily integrate and configure Google Analytics (gtag.js) in your Grav CMS site
- Disable Google Analytics for different IP addresses
- [Rename the global gtag() object](https://developers.google.com/analytics/devguides/collection/gtagjs/renaming-the-gtag-object)
- [Disable Analytics for opted-out users](https://developers.google.com/analytics/devguides/collection/gtagjs/user-opt-out)
- [Disable advertising features](https://developers.google.com/analytics/devguides/collection/gtagjs/display-features)
- [IP anonymization](https://developers.google.com/analytics/devguides/collection/gtagjs/ip-anonymization)
- [Configure cookie settings](https://developers.google.com/analytics/devguides/collection/gtagjs/cookies-user-id)
- [Do Not Track (DNT) support](https://allaboutdnt.com) 
- Supported Languages: `EN`,`DE`

## Installation

Installing the Google Analytics plugin can be done in one of three ways: The GPM (Grav Package Manager) installation method lets you quickly install the plugin with a simple terminal command, the manual method lets you do so via a zip file, and the admin method lets you do so via the Admin Plugin.

### GPM Installation (Preferred)

To install the plugin via the [GPM](http://learn.getgrav.org/advanced/grav-gpm), through your system's terminal (also called the command line), navigate to the root of your Grav-installation, and enter:

    bin/gpm install grav-plugin-google-analytics

This will install the Google Analytics plugin into your `/user/plugins`-directory within Grav. Its files can be found under `/your/site/grav/user/plugins/Google Analytics`.

### Manual Installation

To install the plugin manually, download the zip-version of this repository and unzip it under `/your/site/grav/user/plugins`. Then rename the folder to `Google Analytics`. You can find these files on [GitHub](https://github.com/cworreschk/grav-plugin-google-analytics) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/grav-plugin-google-analytics
	
> **Note:** This plugin is a modular component for Grav which may require other plugins to operate, please see its [blueprints.yaml-file on GitHub](https://github.com/cworreschk/grav-plugin-google-analytics/blob/master/blueprints.yaml).

### Admin Plugin

If you use the Admin Plugin, you can install the plugin directly by browsing the `Plugins`-menu and clicking on the `Add` button.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/grav-plugin-google-analytics/google-analytics.yaml` to `user/config/plugins/google-analytics.yaml` and only edit that copy.

Here is the default configuration and an explanation of available options:

```yaml
enabled: true
tracking_id: ""

advertising_features: true
anonymize_ip: false
cookie_domain: ""
cookie_expires: ~
cookie_prefix: ""
cookie_update: true
blocked_ips: []
do_not_track: false
object_name: ""
opt_out: false
```

 
| Option                 | Description                                                                  |
|------------------------|------------------------------------------------------------------------------|
| **`enabled`**          | Toggles if the Google Analytics plugin is turned on or off                   |
| **`tracking_id`**      | Google Analytics Tracking ID like `UA-00000000-1`                            |
| `advertising_features` | Disable the advertising features, when they are enabled in the GA admin area | 
| `anonymize_ip`         | Anonymize the IP addresses of hits sent to Google Analytics                  |
| `blocked_ips`          | For the given IP addresses the GA code will not be embedded                  |
| `cookie_domain`        | Override the automatic cookie domain configuration                           |
| `cookie_expires`       | Cookie expiration time in seconds. Default is 28 days                        | 
| `cookie_prefix`        | To avoid name conflicts you can change the prefix of the cookie              | 
| `cookie_update`        | When set to false, cookies are not updated on each page load                 |
| `do_not_track`         | Toggles if `Do Not Track (DNT)` is supported                              |
| `object_name`          | To avoid name conflicts the global gtag() object can be renamed              |
| `opt_out`              | Disable Google Analytics without removing the gtag.js tag                    | 


> **Note:** If you use the Admin Plugin, a file with your configuration named `google-analytics.yaml` will be saved in the `user/config/plugins/`-folder once the configuration is saved in the Admin.

## Usage

1. Sign in to your [Google Analytics account](https://www.google.com/analytics/web/#home).
2. Select the **Admin** tab.
3. Select an account from the dropdown in the **Account** column.
4. Select a property from the dropdown in the **Property** column.
5. Under **Property**, click **Tracking Info > Tracking Code**.
6. Copy the **Tracking ID** (a string like _UA-000000-01_)
7. Add it to the configuration of this plugin.


## Contributing
The **Google Analytics Plugin** follows the [GitFlow branching model](https://www.atlassian.com/git/tutorials/comparing-workflows/gitflow-workflow), from development to release. The ```master``` branch always reflects a production-ready state while the latest development is taking place in the ```develop``` branch.

Each time you want to work on a fix or a new feature, create a new branch based on the ```develop``` branch: ```git checkout -b BRANCH_NAME develop```. Only pull requests to the ```develop``` branch will be merged.

## Copyright and license

Copyright &copy; 2019 Christian Worreschk under the [MIT Licence](http://opensource.org/licenses/MIT). See [README](LICENSE).
