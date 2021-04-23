# Cronjob Package Installation Plugin

Registers new cronjobs.
The cronjob schedular works similar to the `cron(8)` daemon, which might not available to web applications on regular webspaces.
The main difference is that WoltLab Suite’s cronjobs do not guarantee execution at the specified points in time:
WoltLab Suite’s cronjobs are triggered by regular visitors in an AJAX request, once the next execution point lies in the past.

## Components

Each cronjob is described as an `<cronjob>` element with the mandatory attribute `name`.

### `<classname>`

The name of the class providing the cronjob's behaviour,
the class has to implement the `wcf\system\cronjob\ICronjob` interface.

### `<description>`

!!! info "The `language` attribute is optional and should specify the [ISO-639-1](https://en.wikipedia.org/wiki/ISO_639-1) language code."

Provides a human readable description for the administrator.

### `<start*>`

All of the five `startMinute`, `startHour`, `startDom` (Day Of Month), `startMonth`, `startDow` (Day Of Week) are required.
They correspond to the fields in `crontab(5)` of a cron daemon and accept the same syntax.

### `<canBeEdited>`

Controls whether the administrator may edit the fields of the cronjob.

### `<canBeDisabled>`

Controls whether the administrator may disable the cronjob.

### `<options>`

The options element can contain a comma-separated list of options of which at least one needs to be enabled for the template listener to be executed.

## Example

{jinja{ codebox(
  title="cronjob.xml",
  language="xml",
  filepath="package/pip/cronjob.xml"
) }}
