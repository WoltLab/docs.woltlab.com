import materialx.emoji
from markdown import markdown

def define_env(env):
    @env.macro
    def codebox(title = None, language = "", filepath = None, contents = ""):
        if title is not None:
            if filepath is not None:
                editLink = f"""{env.variables['config']['repo_url']}tree/{env.variables['config']['edit_uri'].split("/")[1]}/snippets/{filepath}"""
                icon = markdown(':material-link:',
                    extensions=['pymdownx.emoji'],
                    extension_configs={
                        'pymdownx.emoji': {
                            'emoji_index': materialx.emoji.twemoji,
                            'emoji_generator': materialx.emoji.to_svg
                        }
                    }
                ).replace('<p>', '').replace('</p>', '')
                
                return f"""
```{language} title='{title} <a class="codeBoxTitleGitHubLink" href="{editLink}" title="View on GitHub">{icon}</a>'
--8<-- "{filepath}"
```
"""
            else:
                return f"""
```{language} title="{title}"
{contents}
```
"""
        else:
            if filepath is not None:
                return f"""
```{language}
--8<-- "{filepath}"
```
"""
            else:
                return f"""
```{language}
{contents}
```
"""
