# Lever plugin for Craft CMS

[Lever Hire](https://www.lever.co/) is a powerful recruiting platform to track applicants. Now you can integrate with Lever directly from your [Craft](https://craftcms.com/) site.

## Installation

To install Lever, follow these steps:

1. Download & unzip the file and place the `lever` directory into your `craft/plugins` directory
1. Install plugin in the Craft Control Panel under Settings > Plugins
1. The plugin folder should be named `lever` for Craft to see it.  GitHub recently started appending `-master` (the branch name) to the name of the folder for zip file downloads.

Lever works on Craft 2.4.x and Craft 2.5.x.

## Lever Overview

Instead of sending applicants to apply on your Lever site, you can create a form to save applicants right on your Craft site.

## Configuring Lever

1. Copy `lever/config.php`
1. Save the file as `lever.php`
1. Place `lever.php` into your `craft/config` directory.
1. Add your Lever values for `apiKey` and `site`

### Finding Your API Key

![Screenshot](resources/lever-api-key.png)

[Visit Sections > Integrations > API to find your API Key](https://hire.lever.co/settings/integrations?tab=api)

### Finding Your Site

![Screenshot](resources/lever-site.png)

[Visit Sections > Job site to find your Site](https://hire.lever.co/settings/site). The value you need to use in the config is everything that comes after `https://jobs.lever.co/`. In the screenshot above, the site value is `viget`.

## Using Lever

For applicants to apply to jobs in Lever, you will need to build a form to process these requests. Here is the form in its simplest state:

```
<form method="post" enctype="multipart/form-data">
	{{ getCsrfInput() }}
	<input type="hidden" name="action" value="lever/saveApplicant">
	<input type="hidden" name="redirect" value="careers/thanks">
	<input type="hidden" name="position" value="1">

	<label for="name">Name</label>
	<input type="text" name="name" id="name" required>

	<label for="email">Email</label>
	<input type="email" name="email" id="email" required>

	...
</form>
```

### Fields

#### `redirect`
The URL to redirect to after submitting.

#### `position`
The Lever ID of the position to apply to.

#### `name`
This is a required field.

#### `email`
This is a required field.

#### `urls`
This is an optional field. It will split a `<textarea>` on new lines and submit each URL separately.

#### Optional Fields
You can see additional optional fields in the [Lever Postings API documentation](https://github.com/lever/postings-api#apply-to-a-job-posting).

## Field Type
A Lever Field Type is also available in this plugin. If you want a control panel user to select which position(s) can be applied to, this will provide a list of open positions from Lever in the Craft control panel for a control panel user to select from.

![Screenshot](resources/lever-field-type.png)

### Front-End Example
```
<label for="position">Position</label>
<select name="position" id="position" required>
	<option value="">Select Position</option>
	{% for opening in entry.leverPositions %}
		<option value="{{ opening.leverId }}">{{ opening.leverTitle }}</option>
	{% endfor %}
</select>
```
