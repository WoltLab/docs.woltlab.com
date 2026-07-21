## WoltLab Suite 6.2 Documentation

This repository is used to build the documentation at [https://docs.woltlab.com](https://docs.woltlab.com), contributions are welcome.

## Contributing

Please create an issue before starting any work on new content or updates/fixes to existing ones. This ensures that the same topic isn't worked on independently by multiple people and that your additions and modifications do not conflict with any other plans.

The documentation uses the [Material theme](https://squidfunk.github.io/mkdocs-material/) for [MkDocs](https://www.mkdocs.org/).

## Reminder: Updating `latest`

Execute in the branch that will be come the new `latest` branch!

```sh
python3 -m venv mike
source mike/bin/activate
pip install -r requirements.txt
mike alias -u -b target <new-version> latest
```
